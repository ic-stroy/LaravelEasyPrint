<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants;


use function response;

class OrderController extends Controller
{
    public function setWarehouse(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        if(isset($user->orderBasket->id)){
            $order = $user->orderBasket;
            $order->price = (int)$order->price + (int)$request->image_price;
        }else{
            $order = new Order();
            $order->user_id = $user->id;
            $order->status = 1;
            $order->price = $request->image_price;
        }
        $order->save();
        $message = translate_api('Success', $language);
        if ($request->warehouse_product_id && DB::table('warehouses')->where('id',$request->warehouse_product_id)->exists()) {

            // dd($message);

            $order_detail = DB::table('order_details')->where('order_id', $order->id)->where('warehouse_id', $request->warehouse_product_id)->where('color_id', $request->color_id)->where('size_id', $request->size_id);

            if ($order_detail->exists()) {
                $order_detail->update([
                    'quantity' =>$request->quantity,
                    'price'=>($request->quantity * $request->price)
                ]);
            } else {
                DB::table('order_details')->insert([
                    'order_id' => $order->id,
                    'quantity' => $request->quantity,
                    'color_id' => $request->color_id,
                    'size_id' => $request->size_id,
                    'price' => $request->price
                    // Add more columns and their respective default values
                ]);
            }

             return response()->json([
                        'status'=>true,
                        'message'=>$message
                    ]);

            // return $this->success($message, 200);
        }




        $order_detail = new OrderDetail();
        $order_detail->product_id = $request->product_id ?? null;
        //warehouse_product_id:45
        $order_detail->quantity = $request->quantity;
        $order_detail->color_id = $request->color_id;
        $order_detail->size_id = $request->size_id;
        $images_print = $request->file('imagesPrint');

        $order_detail->price = $request->image_price;
        $image_front = $request->file('image_front');
        $image_back = $request->file('image_back');
        $order_detail->image_front = $this->saveImage($image_front, 'warehouse');
        $order_detail->image_back = $this->saveImage($image_back, 'warehouse');
        $order_detail->order_id = $order->id;
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

        return response()->json([
            'status'=>true,
            'message'=>$message
        ]);
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

    public function getBasket(Request $request){
        $user = Auth::user();

        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        // dd($user->orderBasket->orderDetail);
        $data = [];
        if(isset($user->orderBasket->orderDetail)){
            foreach ($user->orderBasket->orderDetail as $order_detail){

                if ($order_detail->warehouse_id != null) {

                    $warehouse_product = DB::table('order_details as dt1')
                        ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        // ->leftJoin('coupons as dt5', 'dt5.warehouse_product_id', '=', 'dt2.id')
                        ->where('dt1.id' , $order_detail->id)
                        ->select('dt2.name as warehouse_product_name','dt2.quantity as max_quantity', 'dt2.images as images', 'dt2.description as description', 'dt2.product_id as product_id', 'dt2.company_id as company_id','dt3.id as size_id','dt3.name as size_name','dt4.id as color_id','dt4.name as color_name','dt4.code as color_code')
                        ->first();
                    // dd($warehouse_product->company_id);


                    $sizes = DB::table('warehouses as dt1')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                        // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->select('dt1.id as id','dt3.id as size_id', 'dt3.name as size_name')
                        ->distinct('size_id')
                        ->get();
                        // dd($sizes);

                        $size_list=[];
                        foreach ($sizes as $size) {
                            $colors = DB::table('warehouses as dt1')
                                ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                                ->where('dt1.product_id', $warehouse_product->product_id)
                                ->where('dt1.company_id', $warehouse_product->company_id)
                                ->where('dt1.size_id', $size->size_id)
                                ->select('dt4.id as color_id','dt4.code as color_code', 'dt4.name as color_name','dt1.images as images')
                                // ->distinct('color_id')
                                ->get();

                                $color_list=[];
                                foreach ($colors as $color) {
                                    $aa_color=[
                                        'id'=>$color->color_id,
                                        'code'=>$color->color_code,
                                        'name'=>$color->color_name,
                                    ];
                                    // dd($aa_color);
                                    array_push($color_list,$aa_color);
                                }
                                // dd($color_list);

                                $aa_size=[
                                    'id'=>$size->size_id,
                                    'name'=>$size->size_name,
                                    'color'=>$color_list
                                ];
                                array_push($size_list,$aa_size);

                                // dd($colors);

                        }
                        // dd($size_list);


                    $colors = DB::table('warehouses as dt1')
                        ->join('colors as dt3', 'dt3.id', '=', 'dt1.color_id')
                        // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->select('dt1.id as id','dt3.id as color_id','dt3.code as color_code', 'dt3.name as color_name')
                        ->distinct('color_id')
                        ->get();
                        // dd($color);

                        $aaa_color_list=[];
                        foreach ($colors as $color) {
                            $sizes = DB::table('warehouses as dt1')
                                ->join('sizes as dt4', 'dt4.id', '=', 'dt1.size_id')
                                ->where('dt1.product_id', $warehouse_product->product_id)
                                ->where('dt1.company_id', $warehouse_product->company_id)
                                ->where('dt1.color_id', $color->color_id)
                                ->select('dt4.id as size_id','dt4.name as size_name')
                                // ->distinct('color_id')
                                ->get();
                                // dd($sizes);

                                $aaa_size_list=[];
                                foreach ($sizes as $size) {
                                    $aas_size=[
                                        'id'=>$size->size_id,
                                        'name'=>$size->size_name,
                                    ];
                                    // dd($aa_color);
                                    array_push($aaa_size_list,$aas_size);
                                }
                                // dd($aaa_size_list);

                                $aaa_color=[
                                    'id'=>$color->color_id,
                                    'code'=>$color->color_code,
                                    'name'=>$color->color_name,
                                    'sizes'=>$aaa_size_list
                                ];
                                array_push($aaa_color_list,$aaa_color);

                                // dd($colors);

                        }
                    $relation_type='warehouse_product';
                    $relation_id=$order_detail->warehouse_id;

                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
                        "price"=>$order_detail->price,
                        "quantity"=>$order_detail->quantity,
                        "max_quantity"=>$warehouse_product->max_quantity,
                        "description"=>$warehouse_product->description,
                        "images"=>$warehouse_product->images,
                        "color"=>[
                           "id"=>$warehouse_product->color_id,
                           "code"=>$warehouse_product->color_code,
                           "name"=>$warehouse_product->color_name,
                        ],
                        "size"=>[
                            "id"=>$warehouse_product->size_id,
                            "name"=>$warehouse_product->size_name,
                        ],
                        "color_by_size"=>$size_list,
                        "size_by_color"=>$aaa_color_list
                    ];
                    //  dd($list);
                    //  return response()->json([
                    //     'data'=>$list,
                    //     'status'=>true,
                    //     'message'=>'Success'
                    // ]);

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



                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
                        "price"=>$order_detail->price,
                        "quantity"=>$order_detail->quantity,
                        "description"=>$product->description,
                        "images"=>$product->images,
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
                    // dd($list);



                }

                array_push($data,$list);

            }

            $message=translate_api('success',$language);
            return $this->success($message, 200,$data);
        }

    }

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
            foreach ($order->orderDetail as $order_detail){

                if ($order_detail->warehouse_id != null) {

                    $warehouse_product = DB::table('order_details as dt1')
                        ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        ->where('dt1.id' , $order_detail->id)
                        ->select('dt2.name as warehouse_product_name','dt2.quantity as max_quantity', 'dt2.images as images', 'dt2.description as description', 'dt2.product_id as product_id', 'dt2.company_id as company_id','dt3.id as size_id','dt3.name as size_name','dt4.id as color_id','dt4.name as color_name','dt4.code as color_code')
                        ->first();
                    // dd($warehouse_product->company_id);

                    $relation_type='warehouse_product';
                    $relation_id=$order_detail->warehouse_id;

                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
                        'name'=>$warehouse_product->warehouse_product_name,
                        "price"=>$order_detail->price,
                        "quantity"=>$order_detail->quantity,
                        "description"=>$warehouse_product->description,
                        "images"=>$warehouse_product->images,
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



                    $list=[
                        "id"=>$order_detail->id,
                        "relation_type"=>$relation_type,
                        "relation_id"=>$relation_id,
                        "price"=>$order_detail->price,
                        "quantity"=>$order_detail->quantity,
                        "description"=>$product->description,
                        "images"=>$product->images,
                        "color_name"=>$product->color_name,
                        "size_name"=>$product->size_name
                    ];
                    // dd($list);



                }

                array_push($order_detail_list,$list);
            }
            $addresses=DB::table('addresses as dt1')
                ->join('yy_cities as dt2', 'dt2.id', '=', 'dt1.city_id')
                ->join('yy_cities as dt3', 'dt3.id', '=', 'dt2.parent_id')
                ->where('user_id',Auth::user()->id)
                ->select('dt1.id as address_id','dt1.name as name','dt2.name as city_name','dt3.name as region_name')
                ->get();


            // $data
            // dd($addresses);
            $data=[
                'list'=>$order_detail_list,
                'addresses'=>$addresses,
                'price'=>null,
                'delivery_price'=>null,
                'discount_price'=>null,
                'grant_total'=>null
            ];

            // array_push($data,$addresses);

            // dd($data);


            $message=translate_api('success',$language);
            return $this->success($message, 200,$data);
        }


    }

    public function connectOrder(Request $request){
        $language = $request->header('language');
        $data=$request->all();
        $order_inner=$data['data'];
        // dd($data['data']);
        $order_id=$data['order_id'];

        if ($order_id  && $order=Order::where('id',$order_id)->first()) {

            foreach ($order_inner as  $update_order_detail) {
                // dd($update_order_detail['order_detail_id']);
                if ($order_detail=OrderDetail::where('id',$update_order_detail['order_detail_id'])->where('order_id',$order_id)->first()) {
                    // dd($order_detail);
                    $order_detail->update([
                            'color_id'=>$update_order_detail['color_id'],
                            'size_id'=>$update_order_detail['size_id'],
                            'quantity'=>$update_order_detail['quantity']
                    ]);
                }else {
                    $message=translate_api('order detail not found',$language);
                    return $this->error($message, 400);
                }
            }
            $message=translate_api('success',$language);
            return $this->success($message, 200);
        }
        else {
            $message=translate_api('order  not found',$language);
            return $this->error($message, 400);
        }


    }

    public function acceptedOrder(Request $request){
        $language = $request->header('language');
        $data=$request->all();

        // dd($data);
        $order_id=$data['order_id'];

        if ($order_id  && $order=Order::where('id',$order_id)->first()) {
            $order->update([
                'address_id'=>$data['address_id'],
                'receiver_name'=>$data['receiver_name'],
                'phone_number'=>$data['receiver_phone'],
                'payment_method'=>$data['payment_method'],
                'user_card_id'=>$data['user_card_id'],
                'status'=>Constants::ACCEPTED,
            ]);

            $message=translate_api('success',$language);
            return $this->success($message, 200);
        }
        else {
            $message=translate_api('order  not found',$language);
            return $this->error($message, 400);
        }


    }




}
