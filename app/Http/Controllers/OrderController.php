<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getBasket(){

    }
    public function setWarehouse(Request $request){
        $user = Auth::user();
        $order = new Order();
        $order->user_id = $user->id;
        $order->save();
        $warehouse = new Warehouse();
        $warehouse->product_id = $request->product_id;
        //warehouse_product_id:45
        $warehouse->quantity = $request->quantity;
        $warehouse->color_id = $request->color_id;
        $warehouse->size_id = $request->size_id;
        $warehouse->imagesPrint = $request->imagesPrint;
        $warehouse->image_price = $request->image_price;
        $warehouse->image_front = $request->image_front;
        $warehouse->image_back = $request->image_back;
        $warehouse->order_id = $order->id;
        $warehouse->save();
    }
}
