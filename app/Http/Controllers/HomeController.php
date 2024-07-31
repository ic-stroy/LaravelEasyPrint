<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Cities;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $ordered_orders = Order::where('status', Constants::ORDERED)->count();
        $performed_orders = Order::where('status', Constants::PERFORMED)->count();
        $cancelled_orders = Order::where('status', Constants::CANCELLED)->count();
        $accepted_orders = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->count();
        $delivered_orders = Order::where('status', Constants::ORDER_DELIVERED)->count();
        $ready_for_pickup_orders = Order::where('status', Constants::READY_FOR_PICKUP)->count();
        return view('index', [
            'ordered_orders'=>$ordered_orders,
            'performed_orders'=>$performed_orders,
            'cancelled_orders'=>$cancelled_orders,
            'accepted_orders'=>$accepted_orders,
            'delivered_orders'=>$delivered_orders,
            'ready_for_pickup_orders'=>$ready_for_pickup_orders
        ]);
    }

    public function setCities(){
        if(!Cities::withTrashed()->exists()){
            $response = Http::get(asset("assets/json/cities.json"));
            $cities = json_decode($response);
            foreach ($cities as $city){
                if(!Cities::where('name', $city->region)->exists()){
                    $model_region = new Cities();
                    $model_region->name = $city->region;
                    $model_region->type = 'region';
                    $model_region->parent_id = 0;
                    $model_region->lng = $city->long;
                    $model_region->lat = $city->lat;
                    $model_region->save();
                    foreach ($city->cities as $city_district){
                        $model = new Cities();
                        $model->name = $city_district->name;
                        $model->type = 'district';
                        $model->parent_id = $model_region->id;
                        $model->lng = $city_district->long;
                        $model->lat = $city_district->lat;
                        $model->save();
                    }
                }else{
                    $model_region = Cities::where('name', $city->region)->first();
                    $model_region->lng = $city->long;
                    $model_region->lat = $city->lat;
                    $model_region->save();
                }
            }
        }

    }

    public function getPayme($order_id)
    {
        $order = Order::where(['id' => (int)$order_id, 'status' => Constants::BASKED])->first();
        if (empty($order))
            return redirect('https://easyprint.uz');

        $price = $order->all_price * 100;
        return view('get-payme', [
            'order_id' => $order_id,
            'price' => $price,
            'merchant_id' => Constants::PAYME_MERCHANT_ID
        ]);
        // return redirect('https://checkout.paycom.uz/base64(m='.Constants::PAYME_MERCHANT_ID.';ac.order_id='.$order_id.';a='.$price.')');
    }
}
