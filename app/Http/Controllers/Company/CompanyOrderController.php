<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;

class CompanyOrderController extends Controller
{
    public function index(){
        $orders = Order::all();
        $order_details = OrderDetail::where('warehouse_id' )->get();


        return view('company.order.index');
    }

    public function show($id){
        return view('company.order.show');
    }

    public function destroy($id){
        return view('company.order.show');
    }
}
