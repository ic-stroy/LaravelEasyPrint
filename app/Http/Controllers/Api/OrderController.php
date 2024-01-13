<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Uploads;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants;


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
        if (isset($request->image_price) && $request->image_price !='') {
            if(!isset($request->warehouse_product_id) || $request->warehouse_product_id == ""){
                if ($request->discount != null) {
                    $discount_price = (($request->price + $request->image_price)/100)*$request->discount*$request->quantity;
                }
                else {
                    $discount_price = 0;
                }
                $order_price =(int)($request->price + $request->image_price)*$request->quantity;
                $order_all_price=$order_price - $discount_price;
            }else{
                if ($request->discount != null && $request->discount != "") {
                    $discount_price = (($request->price)/100)*$request->discount*$request->quantity;
                }
                else {
                    $discount_price = 0;
                }
                $order_price =(int)(($request->price)*($request->quantity));
                $order_all_price=$order_price - $discount_price;
            }
        }else {
            if ($request->discount != null && $request->discount != "") {
                $discount_price = (($request->price)/100)*$request->discount*$request->quantity;
            }
            else {
                $discount_price = 0;
            }
            $order_price =(int)(($request->price)*($request->quantity));
            $order_all_price=$order_price - $discount_price;
        }

        if(isset($user->orderBasket->id)){
            $order = $user->orderBasket;
            $order->price = $order->price + $order_price;
            $order->all_price = $order->all_price + $order_all_price;
            $order->discount_price = $order->discount_price + ($order_price - $order_all_price);
        }else{
            $order = new Order();
            $order->user_id = $user->id;
            $order->status = Constants::BASKED;
            $order->price = (int)$order_price;
            $order->discount_price=(int)($order_price-$order_all_price);
            $order->all_price=(int)$order_all_price;
        }
        $order->save();
        if(!$order->code){
            $length = 8;
            $order_code = str_pad($order->id, $length, '0', STR_PAD_LEFT);
            $order->code=$order_code;
            $order->save();
        }

        $message = translate_api('Success', $language);
        if (isset($request->warehouse_product_id) && $request->warehouse_product_id != "") {
            if(!DB::table('warehouses')->where('id', $request->warehouse_product_id)->exists()){
                return $this->error(translate_api('warehouse not found', $language), 400);
            }
            if ($order_detail = OrderDetail::where('order_id', $order->id)->where('warehouse_id', $request->warehouse_product_id)->where('color_id', $request->color_id)->where('size_id', $request->size_id)->first()) {

                $quantity=$order_detail->quantity + $request->quantity;
                $discount_price = ($request->price/100)*$request->discount*$quantity;
                $order_detail->update([
                    'quantity' =>$quantity,
                    'price'=>($quantity * ($request->price)),
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
                    'discount_price'=>$discount_price,
                    'warehouse_id'=>$request->warehouse_product_id,
                    'created_at'=>date("Y-m-d h:i:s"),
                    'updated_at'=>date("Y-m-d h:i:s")
                    // Add more columns and their respective default values
                ]);
            }
             return $this->success($message, 200);
        }else{
            $order_detail = new OrderDetail();
            $order_detail->product_id = $request->product_id ?? null;
            //warehouse_product_id:45
            $order_detail->quantity = $request->quantity;
            $order_detail->color_id = $request->color_id;
            $order_detail->size_id = $request->size_id;
            $images_print = $request->file('imagesPrint');
            $order_detail->price = $request->price + $request->image_price ;
            $order_detail->image_price = $request->image_price;
            $image_front = $request->file('image_front');
            $image_back = $request->file('image_back');
            $order_detail->image_front = $this->saveImage($image_front, 'warehouse');
            $order_detail->image_back = $this->saveImage($image_back, 'warehouse');
            $order_detail->order_id = $order->id;
            $order_detail->discount = $request->discount;
            $order_detail->discount_price = $discount_price;
            $order_detail->save();
            if (isset($images_print)) {
                foreach ($images_print as $image_print){
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
        if (isset($file)) {
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/'.$url.'/', $image_name);
            return $image_name;
        }
    }

    public function getImages($model, $text){
        if(isset($model->images)){
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
    public function getBasket(Request $request){
        $user = Auth::user();

        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        // dd($user->orderBasket->orderDetail);
        $order=$user->orderBasket;
        $order_detail_list = [];
        if(isset($user->orderBasket->orderDetail)){
            foreach ($user->orderBasket->orderDetail as $order_detail){
                if ($order_detail->warehouse_id != null) {
                    $warehouse_product = DB::table('order_details as dt1')
                        ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        // ->leftJoin('coupons as dt5', 'dt5.warehouse_product_id', '=', 'dt2.id')
                        ->where('dt1.id' , $order_detail->id)
                        ->join('companies as dt5', 'dt5.id', '=','dt2.company_id')
                        ->select('dt2.id as warehouse_product_id','dt2.name as warehouse_product_name','dt2.quantity as max_quantity',
                            'dt2.images as images', 'dt2.description as description', 'dt2.product_id as product_id',
                            'dt2.company_id as company_id','dt3.id as size_id','dt3.name as size_name',
                            'dt4.id as color_id','dt4.name as color_name','dt4.code as color_code','dt5.name as company_name')
                        ->first();

                    // $sizes = DB::table('warehouses as dt1')
                    //     ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                    //     // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                    //     ->where('dt1.product_id', $warehouse_product->product_id)
                    //     ->where('dt1.company_id', $warehouse_product->company_id)
                    //     ->select('dt1.id as id','dt3.id as size_id', 'dt3.name as size_name')
                    //     ->distinct('size_id')
                    //     ->get();
                    //     // dd($sizes);

                    //     $size_list=[];
                    //     foreach ($sizes as $size) {
                    //         $colors = DB::table('warehouses as dt1')
                    //             ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                    //             ->where('dt1.product_id', $warehouse_product->product_id)
                    //             ->where('dt1.company_id', $warehouse_product->company_id)
                    //             ->where('dt1.size_id', $size->size_id)
                    //             ->select('dt4.id as color_id','dt4.code as color_code', 'dt4.name as color_name','dt1.images as images')
                    //             ->distinct('color_id')
                    //             ->get();
                    //             // dd($colors);

                    //             $color_list=[];
                    //             foreach ($colors as $color) {
                    //                 $aa_color=[
                    //                     'id'=>$color->color_id,
                    //                     'code'=>$color->color_code,
                    //                     'name'=>$color->color_name,
                    //                 ];
                    //                 // dd($aa_color);
                    //                 array_push($color_list,$aa_color);
                    //             }
                    //             // dd($color_list);

                    //             $aa_size=[
                    //                 'id'=>$size->size_id,
                    //                 'name'=>$size->size_name,
                    //                 'color'=>$color_list
                    //             ];
                    //             array_push($size_list,$aa_size);

                    //             // dd($colors);
                    //     }
                    //     // dd($size_list);

                    // $colors = DB::table('warehouses as dt1')
                    //     ->join('colors as dt3', 'dt3.id', '=', 'dt1.color_id')
                    //     // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                    //     ->where('dt1.product_id', $warehouse_product->product_id)
                    //     ->where('dt1.company_id', $warehouse_product->company_id)
                    //     ->select('dt1.id as id','dt3.id as color_id','dt3.code as color_code', 'dt3.name as color_name')
                    //     ->distinct('color_id')
                    //     ->get();
                    //     // dd($color);

                    //     $aaa_color_list=[];
                    //     foreach ($colors as $color) {
                    //         $sizes = DB::table('warehouses as dt1')
                    //             ->join('sizes as dt4', 'dt4.id', '=', 'dt1.size_id')
                    //             ->where('dt1.product_id', $warehouse_product->product_id)
                    //             ->where('dt1.company_id', $warehouse_product->company_id)
                    //             ->where('dt1.color_id', $color->color_id)
                    //             ->select('dt4.id as size_id','dt4.name as size_name')
                    //             ->distinct('size_id')
                    //             ->get();
                    //             // dd($sizes);

                    //             $aaa_size_list=[];
                    //             foreach ($sizes as $size) {
                    //                 $aas_size=[
                    //                     'id'=>$size->size_id,
                    //                     'name'=>$size->size_name,
                    //                 ];
                    //                 // dd($aa_color);
                    //                 array_push($aaa_size_list,$aas_size);
                    //             }
                    //             // dd($aaa_size_list);

                    //             $aaa_color=[
                    //                 'id'=>$color->color_id,
                    //                 'code'=>$color->color_code,
                    //                 'name'=>$color->color_name,
                    //                 'sizes'=>$aaa_size_list
                    //             ];
                    //             array_push($aaa_color_list,$aaa_color);

                    //             // dd($colors);

                    //     }
                    $relation_type='warehouse_product';
                    $relation_id=$order_detail->warehouse_id;
                    $list_product = Products::find($warehouse_product->product_id);
                    $list_images = count($this->getImages($warehouse_product, 'warehouses'))>0?$this->getImages($warehouse_product, 'warehouses'):$this->getImages($list_product, 'product');

                    $translate_name=table_translate($warehouse_product,'warehouse',$language);
                    $total_price=$order_detail->price - $order_detail->discount_price;

                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
                        'name'=>$translate_name,
                        "price"=>$order_detail->price,
                        "quantity"=>$order_detail->quantity,
                        "max_quantity"=>$warehouse_product->max_quantity,
                        "description"=>$warehouse_product->description,
                        "discount"=>$order_detail->discount,
                        "discount_price"=>$order_detail->discount_price,
                        "total_price"=>$total_price,
                        "company_name"=>$warehouse_product->company_name,
                        "images"=>$list_images,
                        "color"=>[
                           "id"=>$warehouse_product->color_id,
                           "code"=>$warehouse_product->color_code,
                           "name"=>$warehouse_product->color_name,
                        ],
                        "size"=>[
                            "id"=>$warehouse_product->size_id,
                            "name"=>$warehouse_product->size_name,
                        ]
                        // "color_by_size"=>$size_list,
                        // "size_by_color"=>$aaa_color_list
                    ];

                }
                else {
                    $relation_type='product';
                    $relation_id=$order_detail->product_id;

                    $product = DB::table('order_details as dt1')
                        ->join('products as dt2', 'dt2.id', '=', 'dt1.product_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                        ->where('dt1.id' , $order_detail->id)
                        ->select('dt2.id','dt2.name','dt2.images as images', 'dt2.description as description','dt3.id as size_id','dt3.name as size_name','dt4.id as color_id','dt4.code as color_code','dt4.name as color_name',)
                        ->first();

                    if(isset($product->id)){

                        $translate_name=table_translate($product,'product',$language);
                        $total_price=$order_detail->price - $order_detail->discount_price;

                        $list=[
                            "id"=>$order_detail->id,
                            "relation_type"=>$relation_type,
                            "relation_id"=>$relation_id,
                            "price"=>$order_detail->price,
                            "name"=>$translate_name ,
                            "quantity"=>$order_detail->quantity,
                            "discount"=>$order_detail->discount,
                            "discount_price"=>$order_detail->discount_price,
                            "total_price"=>$total_price,
                            "description"=>$product->description??'',
                            "company_name"=>null,
                            "images"=>$this->getImages($product, 'product'),
                            "color"=>[
                                "id"=>$product->color_id,
                                "code"=>$product->color_code,
                                "name"=>$product->color_name,
                            ],
                            "size"=>[
                                "id"=>$product->size_id,
                                "name"=>$product->size_name,
                            ]
                        ];
                    }else{
                        $list = [];
                    }

                    // dd($list);
                }

                array_push($order_detail_list,$list);
            }

            $data=[
                'id'=>$order->id,
                'coupon_price'=>$order->coupon_price,
                'price'=>$order->price,
                'discount_price'=>$order->discount_price,
                'grant_total'=>$order->all_price,
                'list'=>$order_detail_list
            ];

            $message=translate_api('success',$language);
            return $this->success($message, 200,$data);
        }
        else {
            return $this->error(translate_api('You do not have an order', $language), 400);
        }

    }
    /**
     * bu funksiya savatchaga qo'shilgan products va warehouses larni frontga chiqarib berishda qollaniladi (Order status Ordered bo'ladi Get zapros) Farqi bunda Orderni ichidagi productlarni o'zgartirib bo'lmaydi
     */
    public function getOrder(Request $request){

        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        // dd($request->order_id);
        $order_id=$request->order_id;

        if ($order_id  && $order=Order::where('id',$order_id)->first()) {
            // dd($order->orderDetail);
            $data=[];
            $order_detail_list=[];
            // $order_price=0;
            // $order_discount_price=0;
            foreach ($order->orderDetail as $order_detail){

                if ($order_detail->warehouse_id != null) {

                    $warehouse_product = DB::table('order_details as dt1')
                        ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        ->where('dt1.id' , $order_detail->id)
                        ->select('dt2.name as warehouse_product_name','dt2.quantity as max_quantity', 'dt2.images as images', 'dt2.description as description', 'dt2.product_id as product_id', 'dt2.company_id as company_id','dt3.id as size_id','dt3.name as size_name','dt4.id as color_id','dt4.name as color_name','dt4.code as color_code')
                        ->first();
                    $product = Products::find($warehouse_product->product_id);
                    // dd($warehouse_product->company_id);

                    $relation_type='warehouse_product';
                    $relation_id=$order_detail->warehouse_id;
                    $images = count($this->getImages($order_detail, 'warehouses'))>0?$this->getImages($order_detail, 'warehouses'):$this->getImages($product, 'product');
                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
                        'name'=>$warehouse_product->warehouse_product_name,
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
                }
                else {
                    $relation_type='product';
                    $relation_id=$order_detail->product_id;

                    $product = DB::table('order_details as dt1')
                        ->join('products as dt2', 'dt2.id', '=', 'dt1.product_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                        ->where('dt1.id' , $order_detail->id)
                        ->select('dt2.name as product_name','dt2.images as images', 'dt2.description as description','dt3.id as size_id','dt3.name as size_name','dt4.id as color_id','dt4.code as color_code','dt4.name as color_name',)
                        ->first();
                        // dd($product);
                    $images = $this->getImages($product, 'product');

                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
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
                    // dd($list);



                }
                array_push($order_detail_list,$list);
            }

            $data=[
                'id'=>$order->id,
                'coupon_price'=>$order->coupon_price,
                'price'=>$order->price,
                'discount_price'=>$order->discount_price,
                'grant_total'=>$order->all_price,
                'list'=>$order_detail_list
            ];

            // array_push($data,$addresses);

            // dd($data);


            $message=translate_api('success',$language);
            return $this->success($message, 200,$data);
        }
        else {
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
        $order_inner=$data['data'];
        // dd($data['data']);
        $order_id=$data['order_id'];
        // return response()->json($data, 200);
        if ($order_id  && $order=Order::where('id',$order_id)->where('status', Constants::BASKED)->first()) {
            $order_price=0;
            $order_discount_price=0;

            foreach ($order_inner as  $update_order_detail) {
                if ($order_detail=OrderDetail::where('id',$update_order_detail['order_detail_id'])->where('order_id',$order_id)->first()) {
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
                    ]);

                    $order_price +=(($order_detail->price)*($order_detail->quantity));
                    $order_discount_price +=(($order_detail->discount_price));
                }else {
                    $message=translate_api('order detail not found',$language);
                    return $this->error($message, 400);
                }
            }

            $order->price=$order_price;
            $order->discount_price=$order_discount_price;
            $order->all_price=$order_price-$order_discount_price;
            $order->status=Constants::ORDERED;
            $order->save();
            $message=translate_api('success',$language);
            return $this->success($message, 200);
        }
        else {
            $message=translate_api('this order not in the basket or not exist',$language);
            return $this->error($message, 400);
        }

    }
    /**
     * bu funksiya  orderga Coupon qo'shishda qollaniladi (Order status Ordered bo'ladi Post zapros) productlarga tegishli faqat bitta coupon active bo'ladi
     */
    public function addCoupon(Request $request){
        // dd($request->all());
        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        if ($coupon=DB::table('coupons')->where('name',$request->coupon_name)->where('status',1)->first()) {
            // dd('dfhsdg');
            if ($order=Order::where('id',$request->order_id)->first()) {
                // dd($order);
                $order_count = Order::where('user_id', $order->user_id)->where('status', '!=', Constants::BASKED)->count();
                if ($order->coupon_id == null) {
                    // dd($order->orderDetail);
                    if ($coupon->company_id != null) {
                        foreach ($order->orderDetail as $order_detail) {
                            if ($order_detail->warehouse_id) {
                                // dd($order_detail->warehouse_id);
                               $company_id=DB::table('warehouses')->where('id',$order_detail->warehouse_id)->first()->company_id;
                               if ($coupon->company_id == $company_id) {
                                // dd($coupon);
                                    if ($coupon->min_price && $order->all_price > $coupon->min_price && $coupon->type == 0 && $order_count <= $coupon->order_count) {
                                        $order->coupon_id = $coupon->id;
                                    }
                                    elseif ($coupon->min_price && $order->all_price > $coupon->min_price && $coupon->type == 1 && ($order_count - 1) == $coupon->order_count) {
                                        $order->coupon_id = $coupon->id;
                                    }
                                    elseif ($coupon->type == 0 && $order_count <= $coupon->order_count) {
                                        $order->coupon_id = $coupon->id;
                                    }
                                    elseif ($coupon->type == 1 && ($order_count - 1) == $coupon->order_count) {
                                        $order->coupon_id = $coupon->id;
                                    }
                                    else {
                                        $message=translate_api('This coupon has not been verified',$language);
                                        return $this->error($message, 400);
                                    }
                                    // $order->coupon_id = $coupon->id;
                                // dd($order);
                               }
                            }

                        }

                    }
                    elseif ($order->all_price > $coupon->min_price && $coupon->type == 0 && $order_count <= $coupon->order_count) {
                        $order->coupon_id = $coupon->id;
                    }
                    elseif ($order->all_price > $coupon->min_price && $coupon->type == 1 && ($order_count - 1) == $coupon->order_count) {
                        $order->coupon_id = $coupon->id;
                    }
                    elseif ($coupon->type == 0 && $order_count <= $coupon->order_count) {
                        $order->coupon_id = $coupon->id;
                    }
                    elseif ($coupon->type == 1 && ($order_count - 1) == $coupon->order_count) {
                        $order->coupon_id = $coupon->id;
                    }
                    else {
                        $message=translate_api('This coupon has not been verified',$language);
                        return $this->error($message, 400);
                    }

                    // dd($order);
                    if ($order->coupun_id != null) {
                        // dd('have a coupons');
                         if ($coupon->percent != null) {
                            // dd($order);
                            $order_coupon_price=(($order->all_price)/100)*($coupon->percent);
                            $order->coupon_price=$order_coupon_price;
                            $order->all_price=$order->all_price - $order_coupon_price;
                         }
                         else {
                            $order->all_price=$order->all_price - $coupon->price;
                            $order->coupon_price=$coupon->price;
                         }
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
                }
                else {
                    $message=translate_api('this order has a coupon',$language);
                    return $this->error($message, 400);
                }

            }
            else {
                $message=translate_api('order not found',$language);
                return $this->error($message, 400);
            }
        }
        $message=translate_api('coupon not found',$language);
        return $this->error($message, 400);
    }

    /**
     * bu funksiya Buyurtmani tastiqlash uchun qollaniladi (Order status ACCEPTED bo'ladi Post zapros)
     */
    public function acceptedOrder(Request $request){
        $language = $request->header('language');
        $data=$request->all();
        $order_id=$data['order_id'];

        if ($order_id  && $order=Order::where('id',$order_id)->where('status', Constants::ORDERED)->first()) {
            $address = Address::find($data['address_id']);
            if(!isset($address->id)){
                $message=translate_api('Address not found', $language);
                return $this->error($message, 400);
            }
            $order->update([
                'address_id'=>$data['address_id'],
                'receiver_name'=>$data['receiver_name'],
                'phone_number'=>$data['receiver_phone'],
                // 'payment_method'=>$data['payment_method'],
                // 'user_card_id'=>$data['user_card_id'],
                'status'=>Constants::ACCEPTED,
            ]);

            $message=translate_api('success',$language);
            return $this->success($message, 200);
        }
        else {
            $message=translate_api('this order not ordered or not exist',$language);
            return $this->error($message, 400);
        }
    }
    /**
     * bu funksiya Orderning ichidagi orderDetail ni o'chirishda qo'llaniladi  (Order status ACCEPTED gacha ishlaydi Post zapros)
     */
    public function deleteOrderDetail(Request $request){
        $language = $request->header('language');

        $order_detail_id=$request->order_detail_id;

        if ($order_detail=OrderDetail::where('id',$order_detail_id)->first()) {


            $order=$order_detail->order;
            $order->price=$order->price-($order_detail->price * $order_detail->quantity);
            $order->discount_price=$order->discount_price - $order_detail->discount_price;
            $order->all_price= $order->all_price - ($order_detail->price * $order_detail->quantity) + $order_detail->discount_price;
            if ($order->coupon_id) {
                if ($order_detail->warehouse_id) {
                    if (DB::table('warehouses')->where('id',$order_detail->warehouse_id)->first()->company_id == $coupon=DB::table('coupons')->where('id',$order->coupon_id)->first()->company_id) {
                        $order->all_price = $order->all_price + $order->coupon_price;
                        $order->coupon_price = null;
                    }
                }
            }
            // if ($order->coupon_id) {

            // }
            // $order->save();
            if ($order_detail->warehouse_id != null) {
               $warehouse=Warehouse::where('id',$order_detail->warehouse_id)->first();
               $warehouse->quantity=$warehouse->quantity + $order_detail->quantity;
               if ($warehouse->save()) {
                   $order_detail->delete();
               }
            //    $message=translate_api('order detail deleted',$language);
            //    return $this->success($message, 200);

            }


           if ($upload=Uploads::where('relation_type',Constants::PRODUCT)->where('relation_id',$order_detail->product_id)->first()) {
            $upload->delete();
           }
           //    dd($upload);

           $order_detail->delete();

           $test_order_detail=DB::table('order_details')->where('order_id',$order->id)->first();
        //    dd($test_order_detail);
           if ($test_order_detail) {
                $order->save();
           }
           else {
                $order->delete();
           }

           $message=translate_api('order detail deleted',$language);
           return $this->success($message, 200);
            // dd($order_detail);
        }

        $message=translate_api('order_detail not found',$language);
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
            $product_quantity = 0;
            foreach ($data->orderDetail as $order_detail){
                $product_quantity = $product_quantity + $order_detail->quantity;
            }
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
                "delivery_date" => $data->delivery_date,
                "delivery_price" => $data->delivery_price,
                "all_price" => $data->all_price,
                "updated_at" => $data->updated_at,
                "coupon_id" => $data->coupon_id,
                "address" => $address,
                "receiver_name" => $data->receiver_name,
                "phone_number" => $data->phone_number,
                "payment_method" => $data->payment_method,
                "user_card_id" => $data->user_card_id,
                "discount_price" => $data->discount_price?(int)$data->discount_price:0,
                "coupon_price" => $data->coupon_price?(int)$data->coupon_price:0,
                "product_quantity" => $product_quantity,
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
                $status = 'Accepted';
                break;
            case 4:
                $status = 'On the way';
                break;
            case 5:
                $status = 'Finished';
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
        if(isset($order->id)){
            $order_details = $order->orderDetail;
            $response = [];
            foreach ($order_details as $order_detail){
                $warehouse = $this->getWarehouseByOrderDetail($order_detail->warehouse_id, $language);
                $product = $this->getProductByOrderDetail($order_detail->product_id, $language);
                if(isset($order_detail->image_front)){
                    $image_front = asset('storage/warehouse/'.$order_detail->image_front);
                }else{
                    $image_front = null;
                }
                if(isset($order_detail->image_back)){
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
                    "size_id"=>$order_detail->size_id,
                    "color_id"=>$order_detail->color_id,
                    "image_price"=>$order_detail->image_price,
                    "total_price"=>$order_detail->total_price,
                    "discount"=>$order_detail->discount,
                    "discount_price"=>$order_detail->discount_price,
                    "warehouse"=>count($warehouse)>0?$warehouse:null,
                    "product"=>count($product)>0?$product:null,
                ];
            }
            $message=translate_api('Success',$language);
            return $this->success($message, 500, $response);
        }else{
            $message=translate_api('Order not found',$language);
            return $this->error($message, 500);
        }

    }

    public function getWarehouseByOrderDetail($warehouse_id, $language){
        $warehouse = Warehouse::find($warehouse_id);
        if (isset($warehouse->id)) {
            if(isset($warehouse->color_id)) {
                $warehouse_color = Color::select('id', 'name', 'code')->find($warehouse->color_id);
                $color_translate_name=table_translate($warehouse_color,'color', $language);
                if(isset($warehouse_color->id)){
                    $color = [
                        "id" => $warehouse_color->id,
                        "code" => $warehouse_color->code,
                        "name" => $color_translate_name??'',
                    ];
                }
            }
            if(isset($warehouse->size_id)) {
                $warehouse_size = Sizes::select('id', 'name')->find($warehouse->size_id);
                if(isset($warehouse_size->id)){
                    $size = [
                        "id" => $warehouse_size->id,
                        "name" => $warehouse_size->name??'',
                    ];
                }
            }
            if($warehouse->name){
                $warehouse_translate_name=table_translate($warehouse,'warehouse', $language);
            }
            if (isset($warehouse->images)) {
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
        if (isset($product->id)) {
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

}
