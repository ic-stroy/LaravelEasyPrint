<?php

namespace App\Http\Controllers\Company;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\EskizToken;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Products;
use App\Models\Uploads;
use App\Models\User;
use App\Models\UserVerify;
use App\Models\Warehouse;
use App\Notifications\OrderNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CompanyOrderController extends Controller
{
    public function index(){
        $user = Auth::user();
        $orderedOrders_ = Order::where('status', Constants::ORDERED)->orderBy('updated_at', 'asc')->get();
        $performedOrders_ = Order::where('status', Constants::PERFORMED)->where('company_id', $user->company_id)->orderBy('updated_at', 'asc')->get();
//        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->where('company_id', $user->company_id)->orderBy('updated_at', 'asc')->limit(101)->get();
        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->orderBy('updated_at', 'asc')->limit(26)->get();
        $deliveredOrders_ = Order::where('status', Constants::ORDER_DELIVERED)->where('company_id', $user->company_id)->orderBy('updated_at', 'asc')->get();
        $readyForPickup_ = Order::where('status', Constants::READY_FOR_PICKUP)->where('company_id', $user->company_id)->orderBy('updated_at', 'asc')->get();
        $acceptedByRecipientOrders_ = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->where('company_id', $user->company_id)->orderBy('created_at', 'asc')->limit(26)->get();
        $orderedOrders = $this->getOrders($orderedOrders_, $user);
        $performedOrders = $this->getOrders($performedOrders_, $user);
        $cancelledOrders = $this->getOrders($cancelledOrders_, $user);
        $deliveredOrders = $this->getOrders($deliveredOrders_, $user);
        $readyForPickup = $this->getOrders($readyForPickup_, $user);
        $acceptedByRecipientOrders = $this->getOrders($acceptedByRecipientOrders_, $user);
        $all_orders = [
            'orderedOrders'=>$orderedOrders,
            'performedOrders'=>$performedOrders,
            'cancelledOrders'=>$cancelledOrders,
            'deliveredOrders'=>$deliveredOrders,
            'readyForPickup'=>$readyForPickup,
            'acceptedByRecipientOrders'=>$acceptedByRecipientOrders,
        ];
        return view('company.order.index', [
            'all_orders'=>$all_orders,
            'user'=>$user
        ]);
    }

    public function index_old(){
        $user = Auth::user();
        $orderedOrders_ = Order::where('status', Constants::ORDERED)->orderBy('updated_at', 'desc')->get();
        $performedOrders_ = Order::where('status', Constants::PERFORMED)->where('company_id', $user->company_id)->orderBy('updated_at', 'desc')->get();
//        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->where('company_id', $user->company_id)->orderBy('updated_at', 'desc')->limit(101)->get();
        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->where('company_id', $user->company_id)->orderBy('updated_at', 'desc')->limit(26)->get();
        $deliveredOrders_ = Order::where('status', Constants::ORDER_DELIVERED)->where('company_id', $user->company_id)->orderBy('updated_at', 'asc')->get();
        $readyForPickup_ = Order::where('status', Constants::READY_FOR_PICKUP)->where('company_id', $user->company_id)->orderBy('updated_at', 'asc')->get();
        $acceptedByRecipientOrders_ = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->where('company_id', $user->company_id)->orderBy('created_at', 'desc')->limit(26)->get();
        $orderedOrders = $this->getOrders($orderedOrders_, $user);
        $performedOrders = $this->getOrders($performedOrders_, $user);
        $cancelledOrders = $this->getOrders($cancelledOrders_, $user);
        $deliveredOrders = $this->getOrders($deliveredOrders_, $user);
        $readyForPickup = $this->getOrders($readyForPickup_, $user);
        $acceptedByRecipientOrders = $this->getOrders($acceptedByRecipientOrders_, $user);
        $all_orders = [
            'orderedOrders'=>$orderedOrders,
            'performedOrders'=>$performedOrders,
            'cancelledOrders'=>$cancelledOrders,
            'deliveredOrders'=>$deliveredOrders,
            'readyForPickup'=>$readyForPickup,
            'acceptedByRecipientOrders'=>$acceptedByRecipientOrders,
        ];
        return view('company.order.indexold', [
            'all_orders'=>$all_orders,
            'user'=>$user
        ]);
    }

    public function finishedAllOrders(){
        $user = Auth::user();
        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->where('company_id', $user->company_id)->orderBy('updated_at', 'desc')->get();
        $acceptedByRecipientOrders_ = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->where('company_id', $user->company_id)->orderBy('created_at', 'desc')->get();
        $acceptedByRecipientOrders = $this->getOrders($acceptedByRecipientOrders_, $user);
        $cancelledOrders = $this->getOrders($cancelledOrders_, $user);
        $all_orders = [
            'cancelledOrders'=>$cancelledOrders,
            'acceptedByRecipientOrders'=>$acceptedByRecipientOrders,
        ];
        return view('company.order.finished_all_orders', [
            'all_orders'=>$all_orders,
            'user'=>$user
        ]);
    }

    public function getOrders($orders, $user){
        $order_data = [];
        foreach($orders as $order) {
            $user_name = '';
            $user_full_name = '';
            $user_gender = '';
            $user_birth_date = '';
            $user_info = [
                'user_name'=>'',
                'role'=>'',
                'birth_date'=>'',
                'gender'=>'',
                'phone_number'=>'',
                'email'=>'',
            ];
            $user_email = '';
            $address = ['name'=>'', 'status'=>''];
            if ($order){
                if ($order->user){
                    $role = '';
                    if ($order->user->personalInfo) {
                        $first_name = $order->user->personalInfo->first_name ? $order->user->personalInfo->first_name . ' ' : '';
                        $last_name = $order->user->personalInfo->last_name ? $order->user->personalInfo->last_name . ' ' : '';
                        $middle_name = $order->user->personalInfo->middle_name ? $order->user->personalInfo->middle_name : '';
                        $user_name = $first_name . '' . $last_name;
                        $user_full_name = $first_name . '' . $last_name.''.$middle_name;
                        if($order->user->personalInfo->gender == Constants::MALE){
                            $user_gender = translate('Male');
                        }elseif($order->user->personalInfo->gender == Constants::FEMALE){
                            $user_gender = translate('Female');
                        }
                        $user_email = $order->user->personalInfo->email??'';
                    }
                    if($order->user->role){
                        $role = $order->user->role->name??'';
                    }
                    $user_info = [
                        'user_name'=>$user_full_name,
                        'role'=>$role,
                        'birth_date'=>$user_birth_date,
                        'gender'=>$user_gender,
                        'phone_number'=>$order->user->email,
                        'email'=>$user_email,
                    ];
                }
                if($order->address) {
                    $address_type = '';
                    if($order->address->user){
                        if($order->address->user->role_id && $order->address->user->role_id != 4){
                            $address_type = 'pick_up';
                        }else{
                            $address_type = 'deliver';
                        }
                    }
                    if ($order->address->cities) {
                        if ($order->address->cities->region) {
                            $region_name = $order->address->cities->region->name ?? "";
                        }
                        $city_name = $order->address->cities->name ?? "";
                    }
                    $address_name = $order->address->name ?? '';
                    $address_postcode = $order->address->postcode ?? '';
                    $address = [
                        'name'=>$region_name.' '.$city_name.' '.$address_name. ' '.$address_postcode,
                        'status'=>$address_type
                    ];
                }
            }

            $products_with_anime = [];
            $products = [];
            $product_types = 0;
            $products_quantity = 0;
            $performed_product_types = 0;
            $product_quantity = 0;
            $company_product_price = 0;
            $performed_company_product_price = 0;
            $company_discount_price = 0;
            $performed_company_discount_price = 0;
            $order_has = false;
            $order_detail_is_ordered = false;
            $products_with_anime_uploads = [];
            foreach($order->orderDetail as $order_detail){
                if($order_detail->status == Constants::ORDER_DETAIL_ORDERED){
                    $order_detail_is_ordered = true;
                }
                $product_quantity = $product_quantity + $order_detail->quantity;

                $discount_withouth_expire = 0;
                $product_discount_withouth_expire = 0;
                $images = [];

                if($order_detail->warehouse_id && $order_detail->product_id == NULL &&
                    $order_detail->warehouse && $order_detail->warehouse->company_id == $user->company_id){
                    $order_has = true;
                    $product_types = $product_types + 1;
                    $products_quantity = $products_quantity + $order_detail->quantity;
                    if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED) {
                        $performed_product_types = $performed_product_types + 1;
                        $performed_company_product_price = $performed_company_product_price + $order_detail->price * $order_detail->quantity - (int)$order_detail->discount_price;
                        $performed_company_discount_price = $performed_company_discount_price + (int)$order_detail->discount_price;
                    }

                    $company_product_price = $company_product_price + $order_detail->price * $order_detail->quantity - (int)$order_detail->discount_price;
                    $order_detail_all_price = (int)$order_detail->price * (int)$order_detail->quantity - (int)$order_detail->discount_price;
                    $company_discount_price = $company_discount_price + (int)$order_detail->discount_price;

                    if($order_detail->warehouse){
                        $discount_withouth_expire = $order_detail->warehouse->discount_withouth_expire?$order_detail->warehouse->discount_withouth_expire->percent:0;
                        $product_discount_withouth_expire = $order_detail->warehouse->product_discount_withouth_expire?$order_detail->warehouse->product_discount_withouth_expire->percent:0;
                    }else{
                        $discount_withouth_expire = 0;
                        $product_discount_withouth_expire = 0;
                    }
                    if($order_detail->warehouse) {
                        $images = [];
                        $warehouse__ = $order_detail->warehouse;
                        if($warehouse__->type == Constants::WAREHOUSE_TYPE){
                            if (count($this->getImages($warehouse__, 'warehouses'))>0) {
                                $images = $this->getImages($warehouse__, 'warehouses');
                            } else {
                                $parentProduct = Products::find($warehouse__->product_id);
                                if($parentProduct){
                                    $images = $this->getImages($parentProduct, 'product');
                                }
                            }
                        }else{
                            if (!$warehouse__->image_front) {
                                $warehouse__->image_front = 'no';
                            }
                            $model_image_front = storage_path('app/public/warehouse/'.$warehouse__->image_front);
                            if (!$warehouse__->image_back) {
                                $warehouse__->image_back = 'no';
                            }
                            $model_image_back = storage_path('app/public/warehouse/'.$warehouse__->image_back);
                            if(file_exists($model_image_front)){
                                $images[] = asset("/storage/warehouse/$warehouse__->image_front");
                            }
                            if(file_exists($model_image_back)){
                                $images[] = asset("/storage/warehouse/$warehouse__->image_back");
                            }
                        }

                    }else{
                        $images = [];
                    }
                    $products[] = [$order_detail, $order_detail_all_price, 'images'=>$images,
                        'discount_withouth_expire'=>$discount_withouth_expire, 'product_discount_withouth_expire'=>$product_discount_withouth_expire
                    ];
                }elseif(!$order_detail->warehouse_id && $order_detail->product_id){
                    $product_translate_name = '';
                    $product_types = $product_types + 1;
                    $products_quantity = $products_quantity + $order_detail->quantity;
                    $order_has = true;
                    $uploads=Uploads::where('relation_type', Constants::PRODUCT)->where('relation_id', $order_detail->id)->get();
                    if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED) {
                        $performed_product_types = $performed_product_types + 1;
                        $performed_company_product_price = $performed_company_product_price + $order_detail->price * $order_detail->quantity - $order_detail->discount_price;
                        $performed_company_discount_price = $performed_company_discount_price + (int)$order_detail->discount_price;
                    }
                    if(isset($order_detail->product_type)){
                        switch($order_detail->product_type){
                            case 0:
                                $product_translate_name = translate('Футболка стандарт');
                                break;
                            case 1:
                                $product_translate_name = translate('Футболка с воротником');
                                break;
                            case 2:
                                $product_translate_name = translate('Оверсайз');
                                break;
                            default:
                                if($order_detail->product){
                                    $product_translate_name = $order_detail->product->name??'';
                                }else{
                                    $product_translate_name = '';
                                }
                        }
                    }else{
                        if($order_detail->product){
                            $product_translate_name = $order_detail->product->name??'';
                        }else{
                            $product_translate_name = '';
                        }
                    }

                    $company_product_price = $company_product_price + $order_detail->price * $order_detail->quantity - $order_detail->discount_price;
                    $order_detail_all_price = (int)$order_detail->price * (int)$order_detail->quantity - (int)$order_detail->discount_price;
                    $company_discount_price = $company_discount_price + (int)$order_detail->discount_price;

                    foreach ($uploads as $upload){
                        if (!$upload->image) {
                            $upload->image = 'no';
                        }
                        $order_detail_upload = storage_path('app/public/print/'.$upload->image);
                        if(file_exists($order_detail_upload)){
                            $products_with_anime_uploads[] = asset('storage/print/'.$upload->image);
                        }
                    }

                    if($order_detail->image_front){
                        $order_detail_image_front_name = $order_detail->image_front;
                    }else{
                        $order_detail_image_front_name = 'no';
                    }
                    $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$order_detail_image_front_name);
                    if(file_exists($order_detail_image_front_exists)){
                        $order_detail_image_front = asset('storage/warehouse/'.$order_detail->image_front);
                    }else{
                        $order_detail_image_front = null;
                    }
                    if($order_detail->image_back){
                        $order_detail_image_back_name = $order_detail->image_back;
                    }else{
                        $order_detail_image_back_name = 'no';
                    }
                    $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$order_detail_image_back_name);
                    if(file_exists($order_detail_image_back_exists)){
                        $order_detail_image_back = asset('storage/warehouse/'.$order_detail->image_back);
                    }else{
                        $order_detail_image_back = null;
                    }
                    if(!$order_detail_image_front && !$order_detail_image_back){
                        if($order_detail->product){
                            if($order_detail->product->images){
                                $images_ = json_decode($order_detail->product->images);
                                $images = [];
                                foreach ($images_ as $key => $image_){
                                    if($key < 2){
                                        $images[] = asset('storage/products/'.$image_);
                                    }
                                }
                            }else{
                                $images = [];
                            }
                        }else{
                            $images = [];
                        }
                    }else{
                        $images = [$order_detail_image_front??'no', $order_detail_image_back??'no'];
                    }
                    if($order_detail->product){
                        $product_discount_withouth_expire = $order_detail->product->discount_whithout_expire?$order_detail->product->discount_whithout_expire->percent:0;
                    }
                    $products_with_anime[] = [$order_detail, $order_detail_all_price, $products_with_anime_uploads,
                        'images'=>$images, 'product_discount_withouth_expire'=>$product_discount_withouth_expire, 'name'=>$product_translate_name];
                }
            }
            if((int)$order->coupon_price>0){
                if($order->coupon){
                    $order_coupon_price = $this->setOrderCoupon($order->coupon, $company_product_price);
                    $performed_order_coupon_price = $this->setOrderCoupon($order->coupon, $performed_company_product_price);
                }else{
                    $order_coupon_price = $order->coupon_price??0;
                    $performed_order_coupon_price = $order->coupon_price??0;
                }
            }else{
                $order_coupon_price = $order->coupon_price??0;
                $performed_order_coupon_price = $order->coupon_price??0;
            }
            if($order_has == true){
                $order_data[] = [
                    'user_info'=>$user_info,
                    'address'=>$address,
                    'order'=>$order,
                    'user_name'=>$user_name,
                    'order_detail_is_ordered'=>$order_detail_is_ordered,
                    'product_types'=>$product_types,
                    'products_quantity'=>$products_quantity,
                    'performed_product_types'=>$performed_product_types,
                    'product_quantity'=>$product_quantity,
                    'products'=>$products,
                    'products_with_anime'=>$products_with_anime,
                    'company_product_price'=>$company_product_price - $order_coupon_price,
                    'order_coupon_price'=>$order_coupon_price,
                    'company_discount_price'=>$company_discount_price,
                    'performed_company_product_price'=>$performed_company_product_price - $performed_order_coupon_price,
                    'performed_order_coupon_price'=>$performed_order_coupon_price,
                    'performed_company_discount_price'=>$performed_company_discount_price
                ];
            }
        }
        return $order_data;
    }

    public function category(){
//
//        $user = Auth::user();
//        foreach($user->unreadnotifications as $notification) {
//            if ($notification->type == "App\Notifications\OrderNotification") {
//                if (!empty($notification->data)) {
//                    $notification->data['product_images'] ? $notification->data['product_images'] : '';
//
//                    $not_read_ordered_quantity = OrderDetail::where('status', 0)->where()->count();
//                }
//            }
//        }

//        $ordered_orders = Order::where('status', Constants::ORDERED)->count();
//        $performed_orders = Order::where('status', Constants::PERFORMED)->count();
//        $cancelled_orders = Order::where('status', Constants::CANCELLED)->count();
//        $accepted_by_recipient_orders = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->count();
//        return view('company.order.category', [
//            'ordered_orders'=>$ordered_orders,
//            'performed_orders'=>$performed_orders,
//            'cancelled_orders'=>$cancelled_orders,
//            'accepted_by_recipient_orders'=>$accepted_by_recipient_orders
//        ]);
    }

    public function show($id){
        $order = Order::find($id);
        return view('company.order.show', ['order'=>$order]);
    }

    public function destroy($id){
        return view('company.order.show');
    }

    public function cancellOrderDetail($id){
        $user = Auth::user();
        $orderDetail = OrderDetail::find($id);
        $order_details_discount_price = 0;
        $order_detail_price = 0;
        $cancelled_has = false;
        if($orderDetail){
            $orderDetail->status = Constants::ORDER_DETAIL_CANCELLED;

            $warehouse_product___ = Warehouse::find($orderDetail->warehouse_id);
            if($warehouse_product___) {
                $warehouse_product___->quantity = (int)$warehouse_product___->quantity + (int)$orderDetail->quantity;
                $warehouse_product___->save();
            }

            $orderDetail->save();
            $order = Order::whereIn('status', [Constants::ORDERED, Constants::PERFORMED, Constants::CANCELLED])->find($orderDetail->order_id);
            if($order){
                sleep(1);
                $order_details_status = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('status')->all();
                if(!in_array(Constants::ORDER_DETAIL_BASKET, $order_details_status) && !in_array(Constants::ORDER_DETAIL_ORDERED, $order_details_status)){
                    foreach($order->orderDetail as $order_detail){
                        if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED){
                            $discount_price = $order_detail->discount_price??0;
                            $order_detail_price = $order_detail_price + $order_detail->price*$order_detail->quantity;
                            $order_details_discount_price = $order_details_discount_price + $discount_price;
                        }elseif($order_detail->status == Constants::ORDER_DETAIL_CANCELLED){
                            $cancelled_has = true;
                        }
                    }
                    if($order_detail_price <= 0 && $cancelled_has == true){
                        if($order->status = Constants::ORDERED){
                            $users = User::where('company_id', $user->company_id)->get();
                            foreach($users as $user) {
                                foreach ($user->unreadnotifications as $notification) {
                                    if ($notification->type == "App\Notifications\OrderNotification") {
                                        if (!empty($notification->data)) {
                                            if ($notification->data['order_id'] == $order->id) {
                                                $notification->read_at = date('Y-m-d H:i:s');
                                                $notification->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $order->status = Constants::CANCELLED;

                    }else{
                        $order->price = $order_detail_price;
                        $order->discount_price = $order_details_discount_price;
                        $order->all_price = $order_detail_price - $order_details_discount_price;
                        if((int)$order->coupon_price>0){
                            if($order->coupon){
                                $coupon_price = $this->setOrderCoupon($order->coupon, $order->all_price);
                            }else{
                                $coupon_price = $order->coupon_price;
                            }
                            $order->coupon_price = $coupon_price;
                        }else{
                            $coupon_price = 0;
                        }
                        $order->all_price = $order->all_price - $coupon_price;
                        $order->status = Constants::PERFORMED;
                        $order->company_id = $user->company_id;
                    }
                    $order->save();

                    return redirect()->route('company_order.index')->with('cancelled', 'Product is cancelled');
                }
            }
        }
        return redirect()->route('company_order.index')->with('cancelled', 'Order is cancelled');
    }

    public function performOrderDetail($id){
        $user = Auth::user();
        $orderDetail = OrderDetail::find($id);
        $order_details_discount_price = 0;
        $order_detail_price = 0;
        if($orderDetail){
            if($orderDetail->status ==  Constants::ORDER_DETAIL_CANCELLED){
                $warehouse_product___ = Warehouse::find($orderDetail->warehouse_id);
                if($warehouse_product___) {
                    $warehouse_product___->quantity = (int)$warehouse_product___->quantity - (int)$orderDetail->quantity;
                    $warehouse_product___->save();
                }
            }
            $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;

            $orderDetail->save();
            $order = Order::whereIn('status', [Constants::ORDERED, Constants::PERFORMED, Constants::CANCELLED])->find($orderDetail->order_id);
            if($order){
                sleep(1);
                $order_details_status = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('status')->all();
                if(!in_array(Constants::ORDER_DETAIL_BASKET, $order_details_status) && !in_array(Constants::ORDER_DETAIL_ORDERED, $order_details_status)){
                    foreach($order->orderDetail as $order_detail){
                        if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED){
                            $discount_price = $order_detail->discount_price??0;
                            $order_detail_price = $order_detail_price + $order_detail->price*$order_detail->quantity;
                            $order_details_discount_price = $order_details_discount_price + $discount_price;
                        }
//                        elseif($order_detail->status == Constants::ORDER_DETAIL_CANCELLED){
//                            $order_detail->delete();
//                        }
                    }
                    if($order_detail_price>0){
                        $order->price = $order_detail_price;
                        $order->discount_price = $order_details_discount_price;
                        $order->all_price = $order_detail_price - $order_details_discount_price;
                        if((int)$order->coupon_price>0){
                            if($order->coupon){
                                $coupon_price = $this->setOrderCoupon($order->coupon, $order->all_price);
                            }else{
                                $coupon_price = $order->coupon_price;
                            }
                            $order->coupon_price = $coupon_price;
                        }else{
                            $coupon_price = 0;
                        }
                        $order->all_price = $order->all_price - $coupon_price;
                        if($order->status = Constants::ORDERED){
                            $users = User::where('company_id', $user->company_id)->get();
                            foreach($users as $user){
                                foreach($user->unreadnotifications as $notification){
                                    if($notification->type == "App\Notifications\OrderNotification"){
                                        if(!empty($notification->data)){
                                            if($notification->data['order_id'] == $order->id){
                                                $notification->read_at = date('Y-m-d H:i:s');
                                                $notification->save();
                                            };
                                        }
                                    }
                                }
                            }
                        }
                        $order->status = Constants::PERFORMED;
                        $order->company_id = $user->company_id;
                        $order->save();
                    }
                }
            }
//        $user = User::where('role_id', 1)->first();
//        $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;
//        $list_images = !empty($this->getImages($orderDetail->warehouse, 'warehouses')) ? $this->getImages($orderDetail->warehouse, 'warehouses')[0] : $this->getImages($orderDetail->warehouse->product, 'product')[0];
//        $data = [
//            'order_id'=>$order->id,
//            'order_detail_id'=>$orderDetail->id,
//            'order_all_price'=>$orderDetail->price*$orderDetail->quantity-(int)$orderDetail->discount_price - $coupon_price,
//            'product'=>[
//                'name'=>$orderDetail->warehouse->name,
//                'images'=>$list_images
//            ],
//            'receiver_name'=>$order->receiver_name,
//        ];
//        Notification::send($user, new OrderNotification($data));
        }

        return redirect()->route('company_order.index')->with('performed', 'Order is performed');
    }

    public function acceptedByRecipient($id){
        $order = Order::whereIn('status', [Constants::ORDER_DELIVERED, Constants::READY_FOR_PICKUP])->find($id);
        if(!$order){
            return redirect()->route('company_order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::ACCEPTED_BY_RECIPIENT;
        $order->save();
        return redirect()->route('company_order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function orderDelivered($id){
        $order = Order::where('status', Constants::PERFORMED)->find($id);
        if(!$order){
            return redirect()->route('company_order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::ORDER_DELIVERED;
        $order->save();
        return redirect()->route('company_order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function readyForPickup($id){
        $phone_number = '';
        $address = '';
        $order = Order::where('status', Constants::PERFORMED)->find($id);
        if(!$order){
            return redirect()->route('company_order.index')->with('error', 'Order not found');
        }else{
            $order->status = Constants::READY_FOR_PICKUP;
            if($order->user){
                if((int)$order->user->email > 1000000){
                    $phone_number = $order->user->email;
                }
            }
            if($order->address) {
                if($order->address->user){
                    if($order->address->user->role_id && $order->address->user->role_id != 4){
                        if ($order->address->cities) {
                            if ($order->address->cities->region) {
                                $region_name = $order->address->cities->region->name ?? "";
                            }
                            $city_name = $order->address->cities->name ?? "";
                        }
                        $address_name = $order->address->name ?? '';
                        $address = $region_name.' '.$city_name.' '.$address_name;
                    }
                }
            }
            $order_code = $order->code??'';
//            $this->sendMessage($phone_number, $order_code, $address);
            $order->save();
            return redirect()->route('company_order.index')->with('performed', 'Order is accepted by recipient');
        }

    }

    public function cancellAcceptedByRecipient($id){
        $order = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->find($id);
        if($order){
            if($order->address) {
                if ($order->address->user) {
                    if ($order->address->user->role_id && $order->address->user->role_id != 4) {
                        $order->status = Constants::READY_FOR_PICKUP;
                        $order->save();
                        return redirect()->route('company_order.index')->with('performed', 'Order is ready for pickup');
                    } else {
                        $order->status = Constants::ORDER_DELIVERED;
                        $order->save();
                        return redirect()->route('company_order.index')->with('performed', 'Order is delivered');
                    }
                }
            }
            return redirect()->route('company_order.index')->with('performed', 'There is no address in order');
        }else{
            return redirect()->route('company_order.index')->with('error', 'Order not found');
        }
    }

    public function cancellOrderDelivered($id){
        $order = Order::where('status', Constants::ORDER_DELIVERED)->find($id);
        if(!$order){
            return redirect()->route('company_order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::PERFORMED;
        $order->save();
        return redirect()->route('company_order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function cancellReadyForPickup($id){
        $order = Order::where('status', Constants::READY_FOR_PICKUP)->find($id);
        if(!$order){
            return redirect()->route('company_order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::PERFORMED;
        $order->save();
        return redirect()->route('company_order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function deleteOrderDetail($id){
        $order_detail = OrderDetail::find($id);
        if($order_detail){
            $order_detail->delete();
            return redirect()->back()->with('performed', 'Order detail deleted from order');
        }else{
            return redirect()->back()->with('cancelled', 'Order detail not found');
        }
    }

    public function orderHistory(){
        $orders = Order::all();
        $order_data = [];
        foreach($orders as $order){
            $user_full_name = '';
            switch ($order->status){
                case Constants::BASKED:
                    $status = translate('Basked');
                    break;
                case Constants::ORDERED:
                    $status = translate('Ordered');
                    break;
                case Constants::PERFORMED:
                    $status = translate('Performed');
                    break;
                case Constants::CANCELLED:
                    $status = translate('Cancelled');
                    break;
                case Constants::ORDER_DELIVERED:
                    $status = translate('Delivered');
                    break;
                case Constants::READY_FOR_PICKUP:
                    $status = translate('Ready for pickup');
                    break;
                case Constants::ACCEPTED_BY_RECIPIENT:
                    $status = translate('Accepted by recipient');
                    break;
                default:
                    $status = null;
            }
            if ($order->user) {
                if ($order->user->personalInfo) {
                    $first_name = $order->user->personalInfo->first_name ? $order->user->personalInfo->first_name . ' ' : '';
                    $last_name = $order->user->personalInfo->last_name ? $order->user->personalInfo->last_name . ' ' : '';
                    $middle_name = $order->user->personalInfo->middle_name ? $order->user->personalInfo->middle_name : '';
                    $user_full_name = $first_name . '' . $last_name . '' . $middle_name;
                }
            }
            $order_data[] = [
                'code'=>$order->code,
                'status'=>$status,
                'updated_at'=>$order->updated_at,
                'user_name'=>$user_full_name
            ];
        }
        return view('company.order.order_history', ['order_data'=>$order_data]);
    }

    public function sendMessage($phone_number, $order_code, $address){
        date_default_timezone_set("Asia/Tashkent");
        $client = new Client();
        $eskiz_token = EskizToken::firstOrNew();
        $random = rand(100000, 999999);
        $token_options = [
            'multipart' => [
                [
                    'name' => 'email',
                    'contents' => 'easysolutiongroupuz@gmail.com'
                ],
                [
                    'name' => 'password',
                    'contents' => '4TYvyjOof4CmOUk5CisHHUzzQ5Mcn1mirx0VBuQV'
                ]
            ]
        ];
        if(!$eskiz_token->expire_date || strtotime('now') > (int)$eskiz_token->expire_date){
            $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/auth/login');
            $res = $client->sendAsync($guzzle_request, $token_options)->wait();
            $res_array = json_decode($res->getBody());
            $eskiz_token->token = $res_array->data->token;
            $eskiz_token->status = 'order';
            $eskiz_token->expire_date = strtotime('+28 days');
            $eskiz_token->save();
        }
        $options = [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => "Bearer $eskiz_token->token",
            ],
            'multipart' => [
                [
                    'name' => 'mobile_phone',
                    'contents' => (int)$phone_number
                ],
                [
                    'name' => 'message',
                    'contents' => "Ваш заказ № ($order_code) ожидает в пункте выдачи. При получении заказа назовите номер заказа: ($order_code). Пункт Выдачи ($address)".': '.$random
                ],
                [
                    'name' => 'from',
                    'contents' => '4546'
                ],
            ]
        ];
        $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/message/sms/send');
        $res = $client->sendAsync($guzzle_request, $options)->wait();
        $result = $res->getBody();
        $result = json_decode($result);
        if(!$result){
            return redirect()->back()->with('status', translate("Fail message not sent. Try again"));
        }
    }

    public function getImages($model, $text){
        if($model->images){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                switch($text){
                    case 'warehouse':
                        $exists_image = storage_path('app/public/warehouse/'.$image_);
                        if(file_exists($exists_image)){
                            $images[] = asset('storage/warehouse/'.$image_);
                        }
                        $images[] = asset('storage/warehouse/'.$image_);
                        break;
                    case 'product':
                        $exists_image = storage_path('app/public/products/'.$image_);
                        if(file_exists($exists_image)){
                            $images[] = asset('storage/products/'.$image_);
                        }
                        break;
                    case 'warehouses':
                        $exists_image = storage_path('app/public/warehouses/'.$image_);
                        if(file_exists($exists_image)){
                            $images[] = asset('storage/warehouses/'.$image_);
                        }
                        break;
                    default:
                }
            }
        }else{
            $images = [];
        }
        return $images;
    }

    public function setOrderCoupon($coupon, $price){
        if ($coupon->percent) {
            $order_coupon_price = ($price/100)*($coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = $coupon->price;
        }
        return $order_coupon_price;
    }

    public function makeAllNotificationsAsRead(){
        $user = Auth::user();
        foreach($user->unreadnotifications as $notification){
            if($notification->type == "App\Notifications\OrderNotification"){
                if(!empty($notification->data)){
                    $notification->read_at = date('Y-m-d H:i:s');
                    $notification->save();
                }
            }
        }
        return redirect()->back();
    }
}
