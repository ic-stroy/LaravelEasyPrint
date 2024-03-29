<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index($id){
        $user = Auth::user();
        $order_ids = OrderDetail::select('order_id')->groupBy('order_id')->distinct()->get()->toArray();
        $orders_id = [];
        foreach ($order_ids as $order_id_){
            foreach ($order_id_ as $order_id){
                $orders_id[] = $order_id;
            }
        }
        $orders = Order::where('status', $id)->where('user_id', $user->id)->whereIn('id', $orders_id)->get();
        return view('admin.order.index', ['orders'=>$orders, 'id'=>$id]);
    }

    public function category(){

        return view('admin.order.category');
    }

    public function show($id){
        $order = Order::find($id);
        return view('admin.order.show', ['order'=>$order]);
    }

    public function destroy($id){
        return view('admin.order.show');
    }
}
