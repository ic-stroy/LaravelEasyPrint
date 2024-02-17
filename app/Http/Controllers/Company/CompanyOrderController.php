<?php

namespace App\Http\Controllers\Company;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Uploads;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CompanyOrderController extends Controller
{
    public function index($id){
        $user = Auth::user();
        $orders = Order::where('status', $id)->get();
        $order_data = [];
        foreach($orders as $order){
//        $not_read_order_quantity = OrderDetail::where('order_id', $id)->where('is_read', 0)->count();
            $products_with_anime = [];
            $products = [];
            $product_types = 0;
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
                $order_has = true;

                if($order_detail->warehouse_id && $order_detail->product_id == NULL &&
                    !empty($order_detail->warehouse) && $order_detail->warehouse->company_id == $user->company_id){
                    $product_types = $product_types + 1;

                    if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED) {
                        $performed_product_types = $performed_product_types + 1;
                        $performed_company_product_price = $performed_company_product_price + $order_detail->price * $order_detail->quantity - $order_detail->discount_price;
                        $performed_company_discount_price = $performed_company_discount_price + (int)$order_detail->discount_price;
                    }

                    $company_product_price = $company_product_price + $order_detail->price * $order_detail->quantity - $order_detail->discount_price;
                    $order_detail_all_price = (int)$order_detail->price * (int)$order_detail->quantity - (int)$order_detail->discount_price;
                    $company_discount_price = $company_discount_price + (int)$order_detail->discount_price;

                    $products[] = [$order_detail, $order_detail_all_price];
                }elseif(!$order_detail->warehouse_id && $order_detail->product_id){
                    $product_types = $product_types + 1;
                    $uploads=Uploads::where('relation_type', Constants::PRODUCT)->where('relation_id', $order_detail->product_id)->get();

                    if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED) {
                        $performed_product_types = $performed_product_types + 1;
                        $performed_company_product_price = $performed_company_product_price + $order_detail->price * $order_detail->quantity - $order_detail->discount_price;
                        $performed_company_discount_price = $performed_company_discount_price + (int)$order_detail->discount_price;
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
                    $products_with_anime[] = [$order_detail, $order_detail_all_price, $products_with_anime_uploads];
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
                    'order'=>$order,
                    'order_detail_is_ordered'=>$order_detail_is_ordered,
                    'product_types'=>$product_types,
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

        return view('company.order.index', ['order_data'=>$order_data, 'id'=>$id]);
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

        $ordered_orders = Order::where('status', Constants::ORDERED)->count();
        $performed_orders = Order::where('status', Constants::PERFORMED)->count();
        $cancelled_orders = Order::where('status', Constants::CANCELLED)->count();
        $accepted_by_recipient_orders = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->count();
        return view('company.order.category', [
            'ordered_orders'=>$ordered_orders,
            'performed_orders'=>$performed_orders,
            'cancelled_orders'=>$cancelled_orders,
            'accepted_by_recipient_orders'=>$accepted_by_recipient_orders
        ]);
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
            foreach($user->unreadnotifications as $notification){
                if($notification->type == "App\Notifications\OrderNotification"){
                    if(!empty($notification->data)){
                        if($notification->data['order_detail_id'] == $orderDetail->id){
                            $notification->read_at = date('Y-m-d H:i:s');
                            $notification->save();
                        }
                    }
                }
            }
            $orderDetail->save();
            $order = Order::whereIn('status', [Constants::ORDERED, Constants::PERFORMED, Constants::CANCELLED])->find($orderDetail->order_id);
            if($order){
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
                    if($order_detail_price == 0 && $cancelled_has == true){
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
                    }

                    $order->save();

                    return redirect()->route('company_order.category')->with('cancelled', 'Product is cancelled');
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
        return redirect()->route('company_order.category')->with('cancelled', 'Product is cancelled');
    }

    public function performOrderDetail($id){
        $user = Auth::user();
        $orderDetail = OrderDetail::find($id);
        $order_details_discount_price = 0;
        $order_detail_price = 0;
        if($orderDetail){
            $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;
            foreach($user->unreadnotifications as $notification){
                if($notification->type == "App\Notifications\OrderNotification"){
                    if(!empty($notification->data)){
                        if($notification->data['order_detail_id'] == $orderDetail->id){
                            $notification->read_at = date('Y-m-d H:i:s');
                            $notification->save();
                        };
                    }
                }
            }
            $orderDetail->save();
            $order = Order::where('status', Constants::ORDERED)->find($orderDetail->order_id);
            if($order){
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

                    $order->save();
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

        return redirect()->route('company_order.category')->with('performed', 'Product is performed');
    }

    public function getImages($model, $text){
        if($model->images){
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

    public function setOrderCoupon($coupon, $price){
        if ($coupon->percent) {
            $order_coupon_price = ($price/100)*($coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = $coupon->price;
        }
        return $order_coupon_price;
    }
}
