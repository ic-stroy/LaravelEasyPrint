<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyOrderController extends Controller
{
    public function index($id){
        $user = Auth::user();
//        $warehouses_id = Warehouse::where('company_id', $user->company_id)->pluck('id')->values()->all();

//        $order_ids = OrderDetail::select('order_id')->where('warehouse_id', NULL)->orWhereIn('warehouse_id', $warehouses_id)->groupBy('order_id')->distinct()->get()->toArray();
//        $orders_id = [];
//        foreach ($order_ids as $order_id_){
//            foreach ($order_id_ as $order_id){
//                $orders_id[] = $order_id;
//            }
//        }
        $orders = Order::where('status', $id)->get();
        $products = [];
        $products_with_anime = [];
        $product_types = 0;
        $product_quantity = 0;
        $company_product_price = 0;
        foreach($orders as $order){
            $order_has = false;
            foreach($order->orderDetail as $order_detail){
                if($order_detail->warehouse_id && $order_detail->product_id == NULL &&
                    !empty($order_detail->warehouse) && $order_detail->warehouse->company_id == $user->company_id){
                    $product_types = $product_types + 1;
                    $product_quantity = $product_quantity + $order_detail->quantity;
                    $company_product_price = $company_product_price + $order_detail->price - $order_detail->discount_price;
                    $order_has = true;
                    $products[] = $order_detail;
                }elseif(!$order_detail->warehouse_id && $order_detail->product_id){
                    $product_types = $product_types + 1;
                    $product_quantity = $product_quantity + $order_detail->quantity;
                    $company_product_price = $company_product_price + $order_detail->price - $order_detail->discount_price;
                    $order_has = true;
                    $products_with_anime[] = $order_detail;
                }
            }
            if($order_has == true){
                $order_data[] = [
                    'order'=>$order,
                    'product_types'=>$product_types,
                    'product_quantity'=>$product_quantity,
                    'products'=>$products,
                    'products_with_anime'=>$products_with_anime,
                    'company_product_price'=>$company_product_price,
                ];
            }
        }

        return view('company.order.index', ['order_data'=>$order_data, 'id'=>$id]);
    }

    public function category(){

        return view('company.order.category');
    }

    public function show($id){
        $order = Order::find($id);
        return view('company.order.show', ['order'=>$order]);
    }

    public function destroy($id){
        return view('company.order.show');
    }
}
