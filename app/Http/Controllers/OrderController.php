<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Uploads;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getBasket(){

    }
    public function setWarehouse(Request $request){
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
        $order_detail = new OrderDetail();
        $order_detail->product_id = $request->product_id;
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
        foreach ($images_print as $image_print){
            $uploads = new Uploads();
            $uploads->image = $this->saveImage($image_print, 'print');
            $uploads->relation_type = 1;
            $uploads->relation_id = $order_detail->id;
            $uploads->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>'Success'
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
}
