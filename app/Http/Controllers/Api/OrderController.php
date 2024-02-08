<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Uploads;
use App\Models\User;
use App\Models\UserCard;
use App\Models\Warehouse;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants;


use Illuminate\Support\Facades\Notification;
use function response;

class OrderController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Tashkent");
    }
    /**
     * bu funksiya savatchaga qo'shilgan product va warehouse larni saxranit qilishda qollaniladi (Order status Backet bo'ladi Post zapros)
     */
    public function setWarehouse(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        if(!Color::where('id', $request->color_id)->exists()){
            return $this->error(translate_api('Color not found', $language), 400);
        }
        if(!Sizes::where('id', $request->size_id)->exists()){
            return $this->error(translate_api('Size not found', $language), 400);
        }
        if(isset($request->product_id)){
            if(!Products::where('id', $request->product_id)->exists()){
                return $this->error(translate_api('Product not found', $language), 400);
            }
        }
        $request_price = $request->price + (int)$request->image_price;
        $request_discount = $request->discount??0;
        $request_order_discount_price = ($request_price/100)*$request_discount*$request->quantity;
        $request_order_price = $request_price*$request->quantity;
        if($user->orderBasket){
            $order = $user->orderBasket;
            $orderDetailWarehouses_id = [];
            $orderDetailProducts_id = [];
            $orderDetailsDiscountPrice = 0;
            $orderDetailsPrice = 0;
            foreach($order->orderDetail as $orderDetail){
                if($orderDetail->warehouse_id && $request->warehouse_product_id && $request->warehouse_product_id == $orderDetail->warehouse_id){
                    $orderDetailWarehouses_id[] = $orderDetail->warehouse_id;
                }
                if($orderDetail->product_id && $request->product_id && $request->product_id == $orderDetail->product_id){
                    $orderDetailProducts_id[] = $orderDetail->product_id;
                }
                if(in_array($request->warehouse_product_id, $orderDetailWarehouses_id) || in_array($request->product_id, $orderDetailProducts_id)) {
                    $orderDetail->price = $request->price;
                    $orderDetail->discount = $request->discount??0;
                }else{
                    $orderDetail->discount = $orderDetail->discount??0;
                }
                $orderDetail->discount_price = ($orderDetail->price/100)*$orderDetail->discount*$orderDetail->quantity;
                $orderDetailsPrice = $orderDetailsPrice + $orderDetail->price*$orderDetail->quantity;
                $orderDetailsDiscountPrice = $orderDetailsDiscountPrice + $orderDetail->discount_price;
            }
            $order->price = $request_order_price + $orderDetailsPrice;
            $order->discount_price = $orderDetailsDiscountPrice + $request_order_discount_price;
            $order->all_price = $order->price - $order->discount_price;
        }else{
            $order = new Order();
            $order->user_id = $user->id;
            $order->status = Constants::BASKED;
            $order->price = (int)$request_order_price;
            $order->discount_price = (int)$request_order_discount_price;
            $order->all_price = (int)$request_order_price - $request_order_discount_price;
            $order->save();
        }
        if(!$order->code){
            $length = 8;
            $order_code = str_pad($order->id, $length, '0', STR_PAD_LEFT);
            $order->code=$order_code;
        }

        if($order->coupon_price && (int)$order->coupon_price>0){
            if($order->coupon->start_date < date('Y-m-d H:i:s') || date('Y-m-d H:i:s') < $order->coupon->end_date){
                $order->coupon_price = NULL;
                $order->coupon_id = NULL;
            }else{
                $order->all_price = $order->price - $order->coupon_price;
                $order->coupon_id = 1;
            }
        }

        $order->save();
        $message = translate_api('Success', $language);
        if ($request->warehouse_product_id) {
            if(!DB::table('warehouses')->where('id', $request->warehouse_product_id)->exists()){
                return $this->error(translate_api('warehouse not found', $language), 400);
            }
            $order_detail = OrderDetail::where('order_id', $order->id)->where('warehouse_id', $request->warehouse_product_id)->where('color_id', $request->color_id)->where('size_id', $request->size_id)->first();
            if ($order_detail) {
                $quantity=$order_detail->quantity + $request->quantity;
                $discount_price = ($request->price/100)*$request->discount*$quantity;
                $order_detail->update([
                    'quantity' =>$quantity,
                    'price'=>$request->price,
                    'discount'=>$request->discount,
                    'discount_price'=>$discount_price
                ]);
            } else {
                DB::table('order_details')->insert([
                    'order_id' => $order->id,
                    'quantity' => $request->quantity,
                    'color_id' => $request->color_id,
                    'size_id' => $request->size_id,
                    'price' => $request->price,
                    'discount'=>$request->discount,
                    'discount_price'=>$request_order_discount_price,
                    'warehouse_id'=>$request->warehouse_product_id,
                    'created_at'=>date("Y-m-d h:i:s"),
                    'updated_at'=>date("Y-m-d h:i:s")
                    // Add more columns and their respective default values
                ]);
            }
             return $this->success($message, 200);
        }else{
            $order_details = OrderDetail::where('order_id', $order->id)->where('product_id', $request->product_id)->get();
            foreach ($order_details as $order_detail) {
                $discount_price = ($request->price / 100) * $request->discount * $order_detail->quantity;
                $order_detail->update([
                    'price'=>$request->price,
                    'discount'=>$request->discount,
                    'discount_price'=>$discount_price
                ]);
            }
            $discount_price = ($request->price / 100) * $request->discount * $request->quantity;
            $order_detail = new OrderDetail();
            $category_type = Category::where('step', 0)->find($request->category_id);
            if($category_type){
                switch($category_type->name){
                    case 'T-shirts':
                        $t_shirts = Products::where('category_id', $request->category_id)->where('name', 'Футболка')->first();
                        $order_detail->product_id = $t_shirts->id;
                        break;
                    case 'Sweatshirts':
                        $t_shirts = Products::where('category_id', $request->category_id)->where('name', 'Свитшот')->first();
                        $order_detail->product_id = $t_shirts->id;
                        break;
                    case 'Hoodies':
                        $t_shirts = Products::where('category_id', $request->category_id)->where('name', 'Худи')->first();
                        $order_detail->product_id = $t_shirts->id;
                        break;
                    default:
                        $order_detail->product_id = $request->product_id ?? null;
                }
            }else{
                $order_detail->product_id = $request->product_id ?? null;
            }
            //warehouse_product_id:45
            $order_detail->quantity = $request->quantity;
            $order_detail->color_id = $request->color_id;
            $order_detail->size_id = $request->size_id;
            $images_print = $request->file('imagesPrint');
            $order_detail->price = $request->price + (int)$request->image_price;
            $order_detail->image_price = $request->image_price;
            $image_front = $request->file('image_front');
            $image_back = $request->file('image_back');
            $order_detail->image_front = $this->saveImage($image_front, 'warehouse');
            $order_detail->image_back = $this->saveImage($image_back, 'warehouse');
            $order_detail->order_id = $order->id;
            $order_detail->discount = $request->discount;
            $order_detail->discount_price = $discount_price;
            $order_detail->save();
            if ($images_print) {
                foreach ($images_print as $image_print) {
                    $uploads = new Uploads();
                    $uploads->image = $this->saveImage($image_print, 'print');
                    $uploads->relation_type = Constants::PRODUCT;
                    $uploads->relation_id = $request->product_id;
                    $uploads->save();
                }
            }
        }
        return $this->success($message, 200);
    }

    public function saveImage($file, $url){
        if ($file) {
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/'.$url.'/', $image_name);
            return $image_name;
        }
    }

    public function getImages($model, $text){
        if($model->images){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                switch($text){
                    case 'warehouse':
                        $images[] = asset('storage/warehouse/'.$image_);
                        break;
                    case 'product':
                        $images[] = asset('storage/products/'.$image_);
                        break;
                    case 'warehouses':
                        $images[] = asset('storage/warehouses/'.$image_);
                        break;
                    default:
                }
            }
        }else{
            $images = [];
        }
        return $images;
    }

    /**
     * bu funksiya savatchaga qo'shilgan products va warehouses larni frontga chiqarib berishda qollaniladi (Order status Backet bo'ladi Get zapros)
     */
    public function getBasket(Request $request)
    {
        $user = Auth::user();
        $language = $request->language;
        if ($language == null) {
            $language = env("DEFAULT_LANGUAGE", 'ru');
        }
        $order = $user->orderBasket;
        $order_detail_list = [];
        if (isset($user->orderBasket->orderDetail)) {
            $order_price = 0;
            $order_discount_price = 0;
            foreach ($user->orderBasket->orderDetail as $order_detail) {
                $list_images = [];
                if ($order_detail->warehouse_id) {
                    $list = [];
                    $warehouse_product = DB::table('order_details as dt1')
                        ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                         ->leftJoin('discounts as dt6', function($join){
                             $join->on(function ($join){
                                 $join->on([
                                     ['dt6.warehouse_id', '=', 'dt2.id'],
                                     ['dt6.company_id', 'is', DB::raw('NULL')]
                                 ])
                                 ->orOn(function ($join){
                                     $join->on([
                                         ['dt6.warehouse_id', '=', 'dt2.id'],
                                         ['dt6.company_id', 'is not', DB::raw('NULL')],
                                         ['dt2.company_id', '=', 'dt6.company_id'],
                                     ]);
                                 });
                             })
                             ->orOn(function ($join){
                                 $join->on([
                                     ['dt6.product_id', '=', 'dt2.product_id'],
                                     ['dt6.product_id', 'is not', DB::raw('NULL')],
                                     ['dt6.warehouse_id', 'is', DB::raw('NULL')],
                                     ['dt6.company_id', 'is', DB::raw('NULL')]
                                 ])
                                 ->orOn(function ($join){
                                     $join->on([
                                         ['dt6.product_id', '=', 'dt2.product_id'],
                                         ['dt6.product_id', 'is not', DB::raw('NULL')],
                                         ['dt6.warehouse_id', 'is', DB::raw('NULL')],
                                         ['dt6.company_id', 'is not', DB::raw('NULL')],
                                         ['dt2.company_id', '=', 'dt6.company_id'],
                                     ]);
                                 });
                             })
                             ->where('start_date', '<=', date('Y-m-d H:i:s'))
                             ->where('end_date', '>=', date('Y-m-d H:i:s'));
                         })
                        ->where('dt1.id', $order_detail->id)
                        ->join('companies as dt5', 'dt5.id', '=', 'dt2.company_id')
                        ->select('dt1.image_front as image_front', 'dt1.image_back as image_back', 'dt2.id as warehouse_product_id', 'dt2.name as warehouse_product_name', 'dt2.quantity as max_quantity',
                            'dt2.images as images', 'dt2.description as description', 'dt2.product_id as product_id',
                            'dt2.company_id as company_id', 'dt2.price as warehouse_price', 'dt3.id as size_id', 'dt3.name as size_name',
                            'dt4.id as color_id', 'dt4.name as color_name', 'dt4.code as color_code', 'dt5.name as company_name',
                        'dt6.percent as discount_percent')
                        ->first();

                    $relation_type = 'warehouse_product';
                    $relation_id = $order_detail->warehouse_id;
                    $list_product = Products::find($warehouse_product->product_id);
                    $list_images = count($this->getImages($warehouse_product, 'warehouses')) > 0 ? $this->getImages($warehouse_product, 'warehouses') : $this->getImages($list_product, 'product');
                    $translate_name = table_translate($warehouse_product, 'warehouse', $language);

                    if(!empty($warehouse_product) && $warehouse_product->warehouse_product_id){
                        if($warehouse_product->discount_percent){
                            $order_detail->discount = $warehouse_product->discount_percent;
                        }else{
                            $order_detail->discount = 0;
                        }
                        if($order_detail->price != $warehouse_product->warehouse_price){
                            $order_detail->price = $warehouse_product->warehouse_price;
                            $order_price = $order_price + $order_detail->price * $order_detail->quantity;
                            if($order_detail->discount && $order_detail->discount != 0){
                                $order_detail->discount_price = ($order_detail->price*$order_detail->discount)/100*$order_detail->quantity;
                                $order_discount_price = $order_discount_price + $order_detail->discount_price;
                            }else{
                                $order_detail->discount_price = 0;
                            }
                        }else{
                            $order_price = $order_price + $order_detail->price * $order_detail->quantity;
                            if($order_detail->discount && $order_detail->discount != 0) {
                                $order_detail->discount_price = ($order_detail->price*$order_detail->discount)/100*$order_detail->quantity;
                                $order_discount_price = $order_discount_price + $order_detail->discount_price;
                            }else{
                                $order_detail->discount_price = 0;
                            }
                        }
                        $order_detail->save();
                    }
                    $total_price = $order_detail->price*$order_detail->quantity - (int)$order_detail->discount_price;

                    $list = [
                        "id" => $order_detail->id,
                        "relation_type" => $relation_type,
                        "relation_id" => $relation_id,
                        'name' => $translate_name,
                        "price" => $order_detail->price,
                        "quantity" => $order_detail->quantity,
                        "max_quantity" => $warehouse_product->max_quantity,
                        "description" => $warehouse_product->description,
                        "discount" => $order_detail->discount,
                        "discount_price" => $order_detail->discount_price,
                        "total_price" => $total_price,
                        "company_name" => $warehouse_product->company_name,
                        "images" => $list_images,
                        "color" => [
                            "id" => $warehouse_product->color_id,
                            "code" => $warehouse_product->color_code,
                            "name" => $warehouse_product->color_name,
                        ],
                        "size" => [
                            "id" => $warehouse_product->size_id,
                            "name" => $warehouse_product->size_name,
                        ]
                    ];

                } else {
                    $relation_type = 'product';
                    $relation_id = $order_detail->product_id;
                    $product = DB::table('order_details as dt1')
                        ->join('products as dt2', 'dt2.id', '=', 'dt1.product_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                        ->leftJoin('discounts as dt5', function($join){
                            $join->on([
                                ['dt5.product_id', '=', 'dt2.id'],
                                ['dt5.product_id', 'is not', DB::raw('NULL')],
                                ['dt5.warehouse_id', 'is', DB::raw('NULL')],
                                ['dt5.company_id', 'is', DB::raw('NULL')]
                            ])
                            ->where('start_date', '<=', date('Y-m-d H:i:s'))
                            ->where('end_date', '>=', date('Y-m-d H:i:s'));
                        })
                        ->where('dt1.id', $order_detail->id)
                        ->select('dt1.image_front as image_front', 'dt1.image_back as image_back', 'dt2.id', 'dt2.name', 'dt2.images as images',
                            'dt2.description as description', 'dt3.id as size_id', 'dt3.name as size_name', 'dt4.id as color_id',
                            'dt4.code as color_code', 'dt4.name as color_name', 'dt2.price as price',
                        'dt5.percent as discount_percent')
                        ->first();

                    if ($product) {
                        $translate_name = table_translate($product, 'product', $language);
                        if($product->image_front || $product->image_back){
                            $list_images = [
                                asset('storage/warehouse/'.$product->image_front),
                                asset('storage/warehouse/'.$product->image_back),
                            ];
                        }else{
                            $list_images = $this->getImages($product, 'product');
                        }
                        if($product->discount_percent){
                            $order_detail->discount = $product->discount_percent;
                        }else{
                            $order_detail->discount = 0;
                        }
                        if($order_detail->price != $product->price){
                            $order_detail->price = $product->price;
                            $order_price = $order_price + $order_detail->price * $order_detail->quantity;
                            if($order_detail->discount && $order_detail->discount != 0){
                                $order_detail->discount_price = ($product->price*$order_detail->discount)/100*$order_detail->quantity;
                                $order_discount_price = $order_discount_price + $order_detail->discount_price;
                            }else{
                                $order_detail->discount_price = 0;
                            }
                        }else{
                            $order_price = $order_price + $order_detail->price * $order_detail->quantity;
                            if($order_detail->discount && $order_detail->discount != 0) {
                                $order_detail->discount_price = ($product->price*$order_detail->discount)/100*$order_detail->quantity;
                                $order_discount_price = $order_discount_price + $order_detail->discount_price;
                            }else{
                                $order_detail->discount_price = 0;
                            }
                        }

                        $order_detail->save();

                        $total_price = $order_detail->price*$order_detail->quantity - (int)$order_detail->discount_price;

                        $list = [
                            "id" => $order_detail->id,
                            "relation_type" => $relation_type,
                            "relation_id" => $relation_id,
                            "price" => $order_detail->price,
                            "name" => $translate_name,
                            "quantity" => $order_detail->quantity,
                            "discount" => $order_detail->discount,
                            "discount_price" => $order_detail->discount_price,
                            "total_price" => $total_price,
                            "description" => $product->description ?? '',
                            "company_name" => null,
                            "images" => $list_images,
                            "color" => [
                                "id" => $product->color_id,
                                "code" => $product->color_code,
                                "name" => $product->color_name,
                            ],
                            "size" => [
                                "id" => $product->size_id,
                                "name" => $product->size_name,
                            ]
                        ];
                    } else {
                        $list = [];
                    }
                }
                array_push($order_detail_list, $list);
            }
            $order->price = $order_price;
            $order->discount_price = $order_discount_price;
            if(!empty($order->coupon)){
                if($order->coupon->start_date > date('Y-m-d H:i:s') || date('Y-m-d H:i:s') > $order->coupon->end_date){
                    $order->all_price = $order->price - (int)$order->discount_price;
                    $order->coupon_id = NULL;
                    $order->coupon_price = NULL;
                }else{
                    $order->all_price = $order->price - (int)$order->discount_price - (int)$order->coupon_price;
                }
            }
            $order->save();
            $data = [
                'id' => $order->id,
                'coupon_id' => $order->coupon_id,
                'coupon_price' => (int)$order->coupon_price,
                'price' => $order->price,
                'discount_price' => $order->discount_price,
                'grant_total' => $order->all_price,
                'list' => $order_detail_list
            ];
            $message = translate_api('success', $language);
            return $this->success($message, 200, $data);
        } else {
            return $this->error(translate_api('You do not have an order', $language), 400);
        }
    }
    /**
     * bu funksiya savatchaga qo'shilgan products va warehouses larni frontga chiqarib berishda qollaniladi (Order status Ordered bo'ladi Get zapros) Farqi bunda Orderni ichidagi productlarni o'zgartirib bo'lmaydi
     */
    public function getOrder(Request $request){
        $language = $request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        $order_id = $request->order_id;

        if ($order_id  && $order = Order::where('id', $order_id)->first()) {
            $data=[];
            $order_detail_list=[];
            $coupon = $order->coupon;
            $order_coupon_price = 0;
            $order_all_price = 0;
            $order_discount_price = 0;
            $company_id = 'no';
            foreach ($order->orderDetail as $order_detail){
                if($order_detail->status == Constants::ORDER_DETAIL_ORDERED){
                    $order_all_price = $order_all_price + $order_detail->price*$order_detail->quantity;
                    $order_discount_price = $order_discount_price + $order_detail->discount_price;
                    $list = [];
                    if ($order_detail->warehouse_id != null) {
                        $warehouse_product = DB::table('order_details as dt1')
                            ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                            ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                            ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                            ->join('products as dt5', 'dt5.id', '=', 'dt2.product_id')
                            ->where('dt1.id' , $order_detail->id)
                            ->select('dt2.name as warehouse_product_name', 'dt2.id as warehouse_id',
                                'dt2.quantity as max_quantity', 'dt2.images as images', 'dt2.description as description',
                                'dt2.product_id as product_id', 'dt2.company_id as company_id', 'dt2.images as images',
                                'dt3.id as size_id', 'dt3.name as size_name','dt4.id as color_id','dt4.name as color_name', 'dt4.code as color_code',
                                'dt5.name as product_name'
                            )
                            ->first();
                        $product = Products::find($warehouse_product->product_id);
                        $relation_type='warehouse_product';
                        $relation_id=$order_detail->warehouse_id;
                        $images = count($this->getImages($warehouse_product, 'warehouses'))>0?$this->getImages($warehouse_product, 'warehouses'):$this->getImages($product, 'product');
                        $list=[
                            "id"=>$order_detail->id,
                            "relation_type"=>$relation_type,
                            "relation_id"=>$relation_id,
                            'name'=>$warehouse_product->warehouse_product_name??$warehouse_product->product_name,
                            "price"=>$order_detail->price,
                            "discount"=>$order_detail->discount,
                            "discount_price"=>$order_detail->discount_price,
                            "quantity"=>$order_detail->quantity,
                            "description"=>$warehouse_product->description,
                            "images"=>$images,
                            "color_code"=>$warehouse_product->color_code,
                            "color_name"=>$warehouse_product->color_name,
                            "size_name"=>$warehouse_product->size_name
                        ];
                    }else {
                        $relation_type='product';
                        $relation_id=$order_detail->product_id;
                        $product = DB::table('order_details as dt1')
                            ->join('products as dt2', 'dt2.id', '=', 'dt1.product_id')
                            ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                            ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                            ->where('dt1.id' , $order_detail->id)
                            ->select('dt1.quantity as order_detail_quantity', 'dt1.price as order_detail_price',
                                'dt1.image_front as order_detail_image_front', 'dt1.image_back as order_detail_image_back',
                                'dt1.discount_price as order_detail_discount_price',
                                'dt2.name as product_name','dt2.images as images', 'dt2.description as description',
                                'dt3.id as size_id','dt3.name as size_name','dt4.id as color_id', 'dt4.code as color_code',
                                'dt4.name as color_name')
                            ->first();

                        if($product){
                            if(!$product->order_detail_image_front){
                                $product->order_detail_image_front = 'no';
                            }
                            if(!$product->order_detail_image_back){
                                $product->order_detail_image_back = 'no';
                            }

                            $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$product->order_detail_image_front);
                            if(file_exists($order_detail_image_front_exists)){
                                $order_detail_image_front = asset('storage/warehouse/'.$product->order_detail_image_front);
                            }else{
                                $order_detail_image_front = null;
                            }

                            $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$product->order_detail_image_back);
                            if(file_exists($order_detail_image_back_exists)){
                                $order_detail_image_back= asset('storage/warehouse/'.$product->order_detail_image_back);
                            }else{
                                $order_detail_image_back = null;
                            }

                            if(!$order_detail_image_front || !$order_detail_image_back){
                                $images = $this->getImages($product, 'product');
                            }else{
                                $images = [$order_detail_image_front, $order_detail_image_back];
                            }

                            $list=[
                                "id"=>$order_detail->id,
                                "relation_type"=>$relation_type,
                                "relation_id"=>$relation_id,
                                'name'=>$product->product_name,
                                "price"=>$order_detail->price,
                                "discount"=>$order_detail->discount,
                                "discount_price"=>$order_detail->discount_price,
                                "quantity"=>$order_detail->quantity,
                                "description"=>$product->description,
                                "images"=>$images,
                                "color_name"=>$product->color_name,
                                "color_code"=>$product->color_code,
                                "size_name"=>$product->size_name
                            ];
                        }
                    }
                    if(!empty($list)){
                        array_push($order_detail_list, $list);
                    }
                }
            }
            if(!empty($coupon) && $coupon->start_date <= date('Y-m-d H:i:s') && $coupon->end_date >= date('Y-m-d H:i:s')){
//                if($order->product_id || !$order->coupon->company_id){
                    $order_coupon_price = $order_coupon_price + $this->setOrderCoupon($coupon, $order_all_price);
                    $order_all_price = $order_all_price;
//                }
            }

            $grant_total = $order_all_price - $order_discount_price - $order_coupon_price;

            $data=[
                'id'=>$order->id,
                'coupon_price'=>$order_coupon_price,
                'price'=>$order_all_price,
                'discount_price'=>$order_discount_price,
                'grant_total'=>$grant_total,
                'list'=>$order_detail_list
            ];

            $message=translate_api('success',$language);
            return $this->success($message, 200, $data);
        } else {
            $message=translate_api('order not found ',$language);
            return $this->error($message, 400);
        }
    }

    /**
     * bu funksiya savatchaga qo'shilgan product va warehouse larni Order xolatiga o'tkazishda   qollaniladi (Order status Ordered bo'ladi Post zapros)
     */
    public function connectOrder(Request $request){
        $language = $request->header('language');
        $data=$request->all();
        $order_inner = $data['data'];
        $order_id = $data['order_id'];
        if ($order_id  && $order = Order::where('id', $order_id)->where('status', Constants::BASKED)->first()) {
            $order_price = 0;
            $order_discount_price = 0;
            $order_details_id = [];
            foreach ($order_inner as $update_order_detail) {
                if ($order_detail=OrderDetail::where('id', $update_order_detail['order_detail_id'])->where('order_id', $order_id)->first()) {
                    if(!Color::where('id', $update_order_detail['color_id'])->exists()){
                        return $this->error(translate_api('Color not found', $language), 400);
                    }
                    if(!Sizes::where('id', $update_order_detail['size_id'])->exists()){
                        return $this->error(translate_api('Size not found', $language), 400);
                    }
                    $one_order_detail_discount_price = $order_detail->discount_price/$order_detail->quantity;
                    $order_detail->update([
                        'color_id'=>$update_order_detail['color_id'],
                        'size_id'=>$update_order_detail['size_id'],
                        'quantity'=>$update_order_detail['quantity'],
                        'discount_price'=>$one_order_detail_discount_price*$update_order_detail['quantity'],
                        'status'=>Constants::ORDER_DETAIL_ORDERED
                    ]);
                }else {
                    $message=translate_api('order detail not found', $language);
                    return $this->error($message, 400);
                }
            }
            foreach($order->orderDetail as $order_detail_){
                $order_price = $order_price + $order_detail_->price*$order_detail_->quantity;
                $order_discount_price = $order_discount_price + $order_detail_->discount_price;
            }
            if($order->coupon){
                if($order->coupon->start_date > date('Y-m-d H:i:s') || date('Y-m-d H:i:s') > $order->coupon->end_date){
                    $order->all_price = $order_price - $order_discount_price;
                    $order->coupon_id = NULL;
                }else{
                    $order->all_price = $order_price - $order_discount_price - (int)$order->coupon_price;
                }
            }else{
                $order->all_price = $order_price - $order_discount_price;
            }

            $order->price=$order_price;
            $order->discount_price=$order_discount_price;
            $order->status=Constants::BASKED;
            $order->save();
            $message=translate_api('success',$language);
            return $this->success($message, 200);
        }else {
            $message=translate_api('this order not in the basket or not exist', $language);
            return $this->error($message, 400);
        }
    }
    /**
     * bu funksiya  orderga Coupon qo'shishda qollaniladi (Order status Ordered bo'ladi Post zapros) productlarga tegishli faqat bitta coupon active bo'ladi
     */
    public function addCoupon(Request $request){
        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }
        $order_coupon_price = 0;
        if ($coupon = Coupon::where('name', $request->coupon_name)
            ->where('status',1)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->where('end_date', '>=', date('Y-m-d H:i:s'))->first()) {
            if ($order=Order::where('id', $request->order_id)->first()) {
                $order_count = Order::where('user_id', $order->user_id)->where('status', '!=', Constants::BASKED)->count();
                if (!$order->coupon_id) {
                    if($order->all_price < $coupon->min_price){
                        $message=translate_api("this order sum isn't enough for coupon. Coupon min price $coupon->min_price", $language);
                        return $this->error($message, 400);
                    }
//                    if ($coupon->company_id != null) {
//                        $company_id = 'no';
//                        foreach ($order->orderDetail as $order_detail) {
//                            if ($order_detail->warehouse_id) {
//                                $company_id = DB::table('warehouses')->where('id', $order_detail->warehouse_id)->first()->company_id;
//                            }elseif($order_detail->product_id) {
//                                $order_coupon_price = $this->getCouponAndPrice($order_coupon_price, $order_detail, $coupon);
//                            }
//                            if ($coupon->company_id == $company_id) {
//                                $order_all_price = $order_all_price + $order_detail->price*$order_detail->quantity - $order_detail->discount_price;
//                               switch ($coupon->type){
//                                   case Constants::TO_ORDER_COUNT:
//                                        if($coupon->order_count > 0){
//                                           $coupon->order_count = $coupon->order_count - 1;
//                                            $order_coupon_price = $this->getCouponAndPrice($order_coupon_price, $order_detail, $coupon);
//                                        }else{
//                                            $message=translate_api("Coupon left 0 quantity", $language);
//                                            return $this->error($message, 400);
//                                        }
//                                       break;
//                                   case Constants::FOR_ORDER_NUMBER:
//                                       if($order_count == $coupon->order_count){
//                                           $order_coupon_price = $this->getCouponAndPrice($order_coupon_price, $order_detail, $coupon);
//                                       }else{
//                                           $message=translate_api("Coupon for your $coupon->order_count - order this is your $order_count - order", $language);
//                                           return $this->error($message, 400);
//                                       }
//                                       break;
//                                   default:
//                                       $order_coupon_price = $this->getCouponAndPrice($order_coupon_price, $order_detail, $coupon);
//                               }
//                            }
//                        }
//                    }else{
                    switch ($coupon->type){
                        case Constants::TO_ORDER_COUNT:
                            if($coupon->order_count > 0){
                                $coupon->order_count = $coupon->order_count - 1;
                                $order_coupon_price = $this->setOrderCoupon($coupon, $order->all_price);
                                $coupon->save();
                            }else{
                                $message=translate_api("Coupon left 0 quantity", $language);
                                return $this->error($message, 400);
                            }
                            break;
                        case Constants::FOR_ORDER_NUMBER:
                            if($order_count == $coupon->order_count){
                                $order_coupon_price = $this->setOrderCoupon($coupon, $order->all_price);
                            }else{
                                $message=translate_api("Coupon for your $coupon->order_count - order this is your $order_count - order", $language);
                                return $this->error($message, 400);
                            }
                            break;
                        default:
                            $order_coupon_price = $this->setOrderCoupon($coupon, $order->all_price);

                    }

//                    }

                    if((int)$order_coupon_price > 0){
                        $order->coupon_id = $coupon->id;
                        $order->coupon_price = $order_coupon_price;
                        $order->all_price = $order->all_price - $order_coupon_price;
                    }

                    $order->save();

                    $data=[
                        'id'=>$order->id,
                        'coupon_price'=>$order->coupon_price,
                        'price'=>$order->price,
                        'discount_price'=>$order->discount_price,
                        'grant_total'=>$order->all_price
                    ];

                    $message=translate_api('success',$language);
                    return $this->success($message, 200,$data);
                }else {
                    $message=translate_api('this order has a coupon',$language);
                    return $this->error($message, 400);
                }
            }
            else {
                $message=translate_api('order not found',$language);
                return $this->error($message, 400);
            }
        }
        $message=translate_api('coupon not found or expired or not active',$language);
        return $this->error($message, 400);
    }

    public function setOrderCoupon($coupon, $price){
        if ($coupon->percent) {
            $order_coupon_price = ($price/100)*($coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = $coupon->price;
        }
        return $order_coupon_price;
    }

    public function getCouponAndPrice($order_coupon_price, $order_detail, $coupon){
        if($coupon->percent){
            $order_coupon_price = $order_coupon_price + $this->setOrderCoupon($coupon, $order_detail->price*$order_detail->quantity - (int)$order_detail->discount_price);
        }elseif($coupon->price){
            $order_coupon_price = $this->setOrderCoupon($coupon, 0);
        }
        return $order_coupon_price;
    }
    /**
     * bu funksiya Buyurtmani tastiqlash uchun qollaniladi (Order status ORDERED bo'ladi Post zapros)
     */
    public function acceptedOrder(Request $request){
        $language = $request->header('language');
        $data = $request->all();
        $order_id = $data['order_id'];

        if ($order_id  && $order = Order::where('id',$order_id)->where('status', Constants::BASKED)->first()) {
            $address = Address::where('user_id', $order->user_id)->find($data['address_id']);
            if(!$address){
                $message=translate_api('Your address not found', $language);
                return $this->error($message, 400);
            }
            $user_card = UserCard::where('user_id', $order->user_id)->find($data['user_card_id']);
            if(!$user_card){
                $message=translate_api('your card not found', $language);
                return $this->error($message, 400);
            }
            $order->update([
                'address_id'=>$data['address_id'],
                'receiver_name'=>$data['receiver_name'],
                'phone_number'=>$data['receiver_phone'],
                'status'=>Constants::ORDERED,
                'payment_method'=>$data['payment_method'],
                'user_card_id'=>$data['user_card_id']
            ]);
            $warehouses_id = OrderDetail::where('order_id', $order->id)->pluck('warehouse_id');
            if(!empty($warehouses_id)){
                $companies_id = Warehouse::whereIn('id', $warehouses_id)->pluck('company_id')->unique()->values()->all();
            }else{
                $companies_id = [];
            }
            if(!empty($order)){
                $i = 0;
                $newOrderDetailPrice = 0;
                $newOrderDetailDiscountPrice = 0;
                $newOrderDetailCouponPrice = 0;
                $orderedOrderPrice = 0;
                $orderedOrderDiscountPrice = 0;
                $newOrderDetail = 0;
                $orderedOrderDetail = 0;
                $newOrderDetailQuantity = 0;
                $users = User::whereIn('company_id', $companies_id)->get();
                foreach($order->orderDetail as $orderDetail){
                    if($orderDetail->status == Constants::ORDER_DETAIL_ORDERED){
                        if(!empty($orderDetail->warehouse)) {
                            if(!empty($companies_id)){
                                if(count($users)>0){
                                    $list_images = !empty($this->getImages($orderDetail->warehouse, 'warehouses')) ? $this->getImages($orderDetail->warehouse, 'warehouses')[0] : $this->getImages($orderDetail->warehouse->product, 'product')[0];
                                    $data = [
                                        'order_id'=>$order->id,
                                        'order_detail_id'=>$orderDetail->id,
                                        'order_all_price'=>$orderDetail->price*$orderDetail->quantity-(int)$orderDetail->discount_price,
                                        'product'=>[
                                            'name'=>$orderDetail->warehouse->name,
                                            'images'=>$list_images
                                        ],
                                        'receiver_name'=>$order->receiver_name,
                                    ];
                                    Notification::send($users, new OrderNotification($data));
                                }
                            }
                        }elseif(!empty($orderDetail->product)){
                            $users = User::whereIn('role_id', [2, 3])->get();
                            if(count($users)>0) {
                                $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$orderDetail->image_front);
                                if(file_exists($order_detail_image_front_exists)){
                                    $order_detail_image_front = asset('storage/warehouse/'.$orderDetail->image_front);
                                }else{
                                    $order_detail_image_front = null;
                                }
                                if(!$order_detail_image_front){
                                    $images = $this->getImages($orderDetail->product, 'product')[0];
                                }else{
                                    $images = $order_detail_image_front;
                                }
                                $data = [
                                    'order_id' => $order->id,
                                    'order_detail_id' => $orderDetail->id,
                                    'order_all_price' => $orderDetail->price * $orderDetail->quantity - (int)$orderDetail->discount_price,
                                    'product' => [
                                        'name' => $orderDetail->product->name,
                                        'images' => $images
                                    ],
                                    'receiver_name' => $order->receiver_name,
                                ];
                                Notification::send($users, new OrderNotification($data));
                            }
                        }
                        $orderedOrderDetail = $orderedOrderDetail + $orderDetail->quantity;
                        $orderedOrderPrice = $orderedOrderPrice + $orderDetail->price*$orderDetail->quantity;
                        $orderedOrderDiscountPrice = $orderedOrderDiscountPrice + $orderDetail->discount_price;
                    }else{
                        $newOrderDetail = $newOrderDetail + $orderDetail;
                        $newOrderDetailQuantity = $newOrderDetailQuantity + $orderDetail->quantity;
                        $newOrderDetailPrice = $newOrderDetailPrice + $orderDetail->price*$orderDetail->quantity;
                        $newOrderDetailDiscountPrice = $newOrderDetailDiscountPrice + $orderDetail->discount_price;
                    }
                }

                $order->price = $orderedOrderPrice;
                $order->discount_price = $orderedOrderDiscountPrice;
                if($order->coupon && $order->coupon_price != 0){
                    if($orderedOrderDetail>0){
                        $newOrderDetailCouponPrice = $order->coupon_price/($orderedOrderDetail + $newOrderDetailQuantity)/$orderedOrderDetail;
                    }
                    $order->all_price = (int)$orderedOrderPrice - (int)$orderedOrderDiscountPrice - (int)$newOrderDetailCouponPrice;

                    if($newOrderDetailQuantity>0){
                        $newOrderDetailCouponPrice = $order->coupon_price/($orderedOrderDetail + $newOrderDetailQuantity)/$newOrderDetailQuantity;
                    }
                }else{
                    $order->all_price = $orderedOrderPrice - $orderedOrderDiscountPrice;
                }
                $order->save();

                $newOrder = new Order();
                $newOrder->price = $newOrderDetailPrice;
                $newOrder->discount_price = $newOrderDetailDiscountPrice;
                $newOrder->all_price = (int)$newOrderDetailPrice - (int)$newOrderDetailDiscountPrice - (int)$newOrderDetailCouponPrice;
                $newOrder->user_id = $order->user_id;
                $newOrder->status = Constants::BASKED;
                $newOrder->save();
                foreach($newOrderDetail as $new_order_detail){
                    $new_order_detail->order_id = $newOrder->id;
                    $new_order_detail->save();
                }
            }
            $message = translate_api('success', $language);
            return $this->success($message, 200);
        } else {
            $message = translate_api('this order not in the basket or not exist', $language);
            return $this->error($message, 400);
        }
    }
    /**
     * bu funksiya Orderning ichidagi orderDetail ni o'chirishda qo'llaniladi  (Order status ORDERED gacha ishlaydi Post zapros)
     */
    public function deleteOrderDetail(Request $request){
        $language = $request->header('language');
        $order_detail_id=$request->order_detail_id;

        if ($order_detail=OrderDetail::where('id',$order_detail_id)->where('status', Constants::BASKED)->first()) {
            $order = $order_detail->order;
            $quantity_products = OrderDetail::where('order_id', $order_detail->order_id)->pluck('quantity')->all();
            $order_detail_coupon_price = 0;
            $coupon_price = 0;
            if(!empty($order->coupon) && $order->coupon_price){
                $coupon_price = $order->coupon_price;
                if($order->coupon->percent){
                    $order_detail_coupon_price = $order->coupon_price/array_sum($quantity_products)/$order_detail->quantity;
                }
            }
            $order->price = (int)$order->price - ((int)$order_detail->price * (int)$order_detail->quantity);
            $order->discount_price = (int)$order->discount_price - (int)$order_detail->discount_price;
            $order_coupon_price = (int)$coupon_price - (int)$order_detail_coupon_price;
            $order->all_price = (int)$order->price - $order->discount_price - $order_coupon_price;
            if (!$order_detail->image_front) {
                $order_detail->image_front = 'no';
            }
            $order_detail_image_front = storage_path('app/public/warehouse/'.$order_detail->image_front);
            if (!$order_detail->image_back) {
                $order_detail->image_back = 'no';
            }
            $order_detail_image_back = storage_path('app/public/warehouse/'.$order_detail->image_back);
            if(file_exists($order_detail_image_front)){
                unlink($order_detail_image_front);
            }
            if(file_exists($order_detail_image_back)){
                unlink($order_detail_image_back);
            }
            if ($uploads=Uploads::where('relation_type', Constants::PRODUCT)->where('relation_id', $order_detail->product_id)->get()) {
                foreach ($uploads as $upload){
                    if (!$upload->image) {
                        $upload->image = 'no';
                    }
                    $order_detail_upload = storage_path('app/public/print/'.$upload->image);
                    if(file_exists($order_detail_upload)){
                        unlink($order_detail_upload);
                    }
                    $upload->delete();
                }
            }

            $order_detail->delete();
//            foreach($order->orderDetail as $orderDetail){
//                $order $orderDetail->price
//            }

            $test_order_detail=DB::table('order_details')->where('order_id', $order->id)->first();
           if ($test_order_detail) {
                $order->save();
           }else {
                $order->delete();
           }

           $message=translate_api('order detail deleted', $language);
           return $this->success($message, 200);
        }

        $message=translate_api('order_detail not found', $language);
        return $this->error($message, 400);

    }

    public function getMyOrders(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        $allOrders = $this->orderToArray($user->allOrders);
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $allOrders);
    }

    public function orderToArray($modal){
        $response = [];
        foreach ($modal as $data){
            $order_date_year = date('Y', strtotime($data->created_at));
            $order_date_month = date('F', strtotime($data->created_at));
            $order_date_week = date('l', strtotime($data->created_at));
            $order_date_day = date('d', strtotime($data->created_at));
            $order_date_hour = date('H', strtotime($data->created_at));
            $order_date_minute = date('i', strtotime($data->created_at));
            $order_date = "$order_date_week, $order_date_day $order_date_month $order_date_year ".translate('Y').'.'.translate('at').' '."$order_date_hour:$order_date_minute";

            $order_status_date_year = date('Y', strtotime($data->created_at));
            $order_status_date_month = date('F', strtotime($data->updated_at));
            $order_status_date_week = date('l', strtotime($data->created_at));
            $order_status_date_day = date('d', strtotime($data->created_at));
            $order_status_date_hour = date('H', strtotime($data->updated_at));
            $order_status_date_minute = date('i', strtotime($data->updated_at));
            $order_status_date = "$order_status_date_week, $order_status_date_day $order_status_date_month $order_status_date_year ".translate('Y').'.'.translate('at').' '."$order_status_date_hour:$order_status_date_minute";

            $region = null;
            $city = null;
            $street = null;
            if($data->address){
                $street = $data->address->name;
                if($data->address->cities){
                    if($data->address->cities->region){
                        $region = $data->address->cities->region?$data->address->cities->region->name:null;
                        $city = $data->address->cities->name;
                    }else{
                        $region = $data->address->cities->name;
                        $city = null;
                    }
                }
            }
            $address = [
                'street'=>$street,
                'region'=>$region,
                'city'=>$city,
            ];
            $response[] = [
                "id" => $data->id,
                "price" => $data->price,
                "status" => $this->getOrderStatus($data->status),
                "order_status_date"=>$order_status_date,
                "order_date"=>$order_date,
                "delivery_date" => $data->delivery_date,
                "delivery_price" => $data->delivery_price,
                "all_price" => $data->all_price,
                "coupon_id" => $data->coupon_id,
                "address" => $address,
                "receiver_name" => $data->receiver_name,
                "phone_number" => $data->phone_number,
                "payment_method" => $data->payment_method,
                "user_card_id" => $data->user_card_id,
                "discount_price" => $data->discount_price?(int)$data->discount_price:0,
                "coupon_price" => $data->coupon_price?(int)$data->coupon_price:0,
                "product_quantity" => count($data->orderDetail),
                "code" => $data->code
            ];
        }
        return $response;
    }

    public function getOrderStatus($id){
        switch ($id){
            case 1:
                $status = 'Basked';
                break;
            case 2:
                $status = 'Ordered';
                break;
            case 3:
                $status = 'Performed';
                break;
            case 4:
                $status = 'Cancelled';
                break;
            case 5:
                $status = 'Accepted by recipient';
                break;
            default:
                $status = null;
        }
        return $status;
    }

    public function getOrderDetailByOrderId(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->find($request->id);
        if($order){
            $order_details = $order->orderDetail;
            $response = [];
            foreach ($order_details as $order_detail){
                $warehouse = $this->getWarehouseByOrderDetail($order_detail->warehouse_id, $language);
                $product = $this->getProductByOrderDetail($order_detail->product_id, $language);
                if($order_detail->image_front){
                    $image_front = asset('storage/warehouse/'.$order_detail->image_front);
                }else{
                    $image_front = null;
                }
                if($order_detail->image_back){
                    $image_back = asset('storage/warehouse/'.$order_detail->image_back);
                }else{
                    $image_back = null;
                }
                $response[] = [
                    "id"=>$order_detail->id,
                    "order_id"=>$order_detail->order_id,
                    "quantity"=>$order_detail->quantity,
                    "price"=>$order_detail->price,
                    "image_front"=>$image_front,
                    "image_back"=>$image_back,
                    "created_at"=>$order_detail->created_at,
                    "updated_at"=>$order_detail->updated_at,
                    "coupon_id"=>$order_detail->coupon_id,
                    "size"=>$order_detail->size?[
                        "id"=>$order_detail->size->id,
                        "name"=>$order_detail->size->name,
                    ]:[],
                    "color"=>$order_detail->color?[
                        "id"=>$order_detail->color->id,
                        "code" => $order_detail->color->code,
                        "name"=>$order_detail->color->name,
                    ]:[],
                    "image_price"=>$order_detail->image_price,
                    "total_price"=>$order_detail->total_price,
                    "discount"=>$order_detail->discount,
                    "discount_price"=>$order_detail->discount_price,
                    "warehouse"=>count($warehouse)>0?$warehouse:null,
                    "product"=>count($product)>0?$product:null,
                ];
            }
            $message=translate_api('Success', $language);
            return $this->success($message, 500, $response);
        }else{
            $message=translate_api('Order not found', $language);
            return $this->error($message, 500);
        }

    }

    public function getWarehouseByOrderDetail($warehouse_id, $language){
        $warehouse = Warehouse::find($warehouse_id);
        if ($warehouse) {
            if($warehouse->color_id) {
                $warehouse_color = Color::select('id', 'name', 'code')->find($warehouse->color_id);
                $color_translate_name=table_translate($warehouse_color,'color', $language);
                if($warehouse_color){
                    $color = [
                        "id" => $warehouse_color->id,
                        "code" => $warehouse_color->code,
                        "name" => $color_translate_name??'',
                    ];
                }
            }
            if($warehouse->size_id) {
                $warehouse_size = Sizes::select('id', 'name')->find($warehouse->size_id);
                if($warehouse_size){
                    $size = [
                        "id" => $warehouse_size->id,
                        "name" => $warehouse_size->name??'',
                    ];
                }
            }
            if($warehouse->name){
                $warehouse_translate_name=table_translate($warehouse,'warehouse', $language);
            }
            if ($warehouse->images) {
                $images_ = json_decode($warehouse->images);
                $images = [];
                foreach ($images_ as $image_) {
                    $images[] = asset('storage/warehouses/' . $image_);
                }
            } elseif (isset($warehouse->product->images)) {
                $images_ = json_decode($warehouse->product->images);
                $images = [];
                foreach ($images_ as $image_){
                    $images[] = asset('storage/products/' . $image_);
                }
            } else {
                $images = [];
            }

            $list = [
                "id" => $warehouse->id,
                "name" => $warehouse_translate_name ?? $warehouse->product?$warehouse->product->name:null,
                "price" => $warehouse->price,
                "description" => $warehouse->description ?? $warehouse->product_description,
                "images" => $images,
                "color" => $color??null,
                "size" => $size??null,
            ];
        } else {
            $list = [];
        }

        return $list;
    }


    public function getProductByOrderDetail($product_id, $language){
        $product = Products::find($product_id);
        if ($product) {
            if($product->name){
                $product_translate_name=table_translate($product,'product', $language);
            }
            $images = $this->getImages($product, 'product');
            $list = [
                "id" => $product->id,
                "name" => $product_translate_name??null,
                "description" => $product->description??'',
                "images" => $images,
            ];
        } else {
            $list = [];
        }

        return $list;
    }

    public function performOrder(Request $request){
        $language = $request->header('language');
//        $orders = Order::where(['status' => Constants::ORDERED, 'company_id' != null])->get();
        $order = Order::first();
        $companies = [];
        $warehouses_id= OrderDetail::where('order_id', $order->id)->pluck('warehouse_id');
        if(!empty($warehouses_id)){
            $companies_id = Warehouse::whereIn('id', $warehouses_id)->pluck('company_id');
            $companies_id = array_unique($companies_id);
        }else{
            $companies_id = [];
        }
        if($order){
            foreach($order->orderDetail as $orderDetail){
                if($orderDetail->warehouse) {
                    if(!empty($companies_id)){
                        $users = User::whereIn('company_id', $companies_id)->get();
                        if(count($users)>0){
                            Notification::send($users, new OrderNotification($orderDetail));
                        }
                    }
                }elseif($orderDetail->product){
                    $users = User::whereIn('role_id', [2, 3])->get();
                    Notification::send($users, new OrderNotification($orderDetail));
                }
            }
        }

        $message = translate_api('Success', $language);
        return $this->success($message, 200, []);
    }

    public function AnimeCategorySizeColor(Request $request){
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        $white_black_colors = Color::whereIn('name', ['Black', 'White'])->get();
        foreach($white_black_colors as $white_black){
            $color_translate_name = table_translate($white_black,'category', $language);
            $white_black_[] = [
                'id'=>$white_black->id,
                'name'=>$color_translate_name,
                'code'=>$white_black->code
            ];
        }
        $categories_ = [];
        foreach ($categories as $category){
            if($category->name){
                $category_translate_name = table_translate($category,'category', $language);
            }
            $categories_[] = [
              'id'=>$category->id,
              'name'=>$category_translate_name,
              'sizes'=>$category->sizes,
              'type'=>count($category->sizes)>0?'active':'no active'
            ];
        }
        $data = [
            'category'=>$categories_,
            'colors'=>$white_black_
        ];
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }
}
