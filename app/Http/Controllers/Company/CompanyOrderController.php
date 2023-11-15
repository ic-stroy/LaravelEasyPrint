<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class CompanyOrderController extends Controller
{
    public function index(){
        $user = Auth::user();
//        $order_details = OrderDetail::where('warehouse_id', '!=', null)->get()->groupBy('order_id');
        $order_details = OrderDetail::get()->groupBy('order_id');
        $orders = [];
        foreach ($order_details as $order_detail_){
            foreach ($order_detail_ as $order_detail){
                if(isset($order_detail->warehouse_product->company_id) && $order_detail->warehouse_product->company_id == $user->company_id){
                    if(isset($order_details->order)){
                        $orders[] = $order_details->order;
                    }
                }
            }
        }
        return view('company.order.index', ['orders'=>$orders]);
    }

    public function show($id){
        return view('company.order.show');
    }

    public function destroy($id){
        return view('company.order.show');
    }
}
