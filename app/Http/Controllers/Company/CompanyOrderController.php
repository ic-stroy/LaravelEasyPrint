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
            $order_product_quantity_array = OrderDetail::where('order_id', $order->id)->pluck('quantity')->all();
            $products_with_anime = [];
            $products = [];
            $product_types = 0;
            $product_quantity = 0;
            $company_product_price = 0;
            $order_has = false;
            $order_detail_is_ordered = false;
            $products_with_anime_uploads = [];
            foreach($order->orderDetail as $order_detail){
                if($order_detail->status == Constants::ORDER_DETAIL_ORDERED){
                    $order_detail_is_ordered = true;
                }
                $order_product_quantity = array_sum($order_product_quantity_array);
                if((int)$order->coupon_price>0){
                    $coupon_price = (int)$order_detail->quantity * (int)$order->coupon_price/$order_product_quantity;
                }else{
                    $coupon_price = 0;
                }
                $product_quantity = $product_quantity + $order_detail->quantity;
                $company_product_price = $company_product_price + $order_detail->price*$order_detail->quantity - $order_detail->discount_price - $coupon_price;
                $order_has = true;
                $order_detail_all_price = (int)$order_detail->price*(int)$order_detail->quantity - (int)$order_detail->discount_price - (int)$coupon_price;
                if($order_detail->warehouse_id && $order_detail->product_id == NULL &&
                    !empty($order_detail->warehouse) && $order_detail->warehouse->company_id == $user->company_id){
                    $product_types = $product_types + 1;
                    $products[] = [$order_detail, $order_detail_all_price];
                }elseif(!$order_detail->warehouse_id && $order_detail->product_id){
                    $product_types = $product_types + 1;
                    $uploads=Uploads::where('relation_type', Constants::PRODUCT)->where('relation_id', $order_detail->product_id)->get();
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
            $order_coupon_price = $order->coupon_price??0;
            if($order_has == true){
                $order_data[] = [
                    'order'=>$order,
                    'order_detail_is_ordered'=>$order_detail_is_ordered,
                    'product_types'=>$product_types,
                    'product_quantity'=>$product_quantity,
                    'products'=>$products,
                    'products_with_anime'=>$products_with_anime,
                    'company_product_price'=>$company_product_price - $order_coupon_price,
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
        return view('company.order.category');
    }

    public function show($id){
        $order = Order::find($id);
        return view('company.order.show', ['order'=>$order]);
    }

    public function destroy($id){
        return view('company.order.show');
    }

    public function cancellOrderDetail($id){
        $orderDetail = OrderDetail::find($id);
        $order = $orderDetail->order;
        $orderDetail->status = Constants::ORDER_DETAIL_CANCELLED;
        $order_product_quantity_array = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('quantity')->all();
        $order_product_quantity = array_sum($order_product_quantity_array);
        if((int)$order->coupon_price>0){
            $coupon_price = (int)$orderDetail->quantity * (int)$order->coupon_price/$order_product_quantity;
        }else{
            $coupon_price = 0;
        }
//        $user = User::where('role_id', 1)->first();
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
//        if($user){
//            Notification::send($user, new OrderNotification($data));
//        }
        $orderDetail->save();
        return redirect()->route('company_order.index', 2);
    }

    public function performOrderDetail($id){
        $orderDetail = OrderDetail::find($id);
        $company_product_price = 0;
        $order_details_discount_price = 0;
        $order_coupon_price = 0;
        if($orderDetail){
            $order = Order::where('status', Constants::BASKED)->find($orderDetail->order_id);
            if($order){
                $order_product_quantity_array = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('quantity')->all();
                $order_product_quantity = array_sum($order_product_quantity_array);
                if((int)$order->coupon_price>0){
                    if($order->coupon){
                        $coupon_price = $this->setOrderCoupon($order->coupon, (int)$orderDetail->price*(int)$orderDetail->quantity-(int)$orderDetail->discount_price);
                    }else{
                        $coupon_price = (int)$orderDetail->quantity * (int)$order->coupon_price/$order_product_quantity;
                    }
                }else{
                    $coupon_price = 0;
                }
                $order_detail_ = OrderDetail::where('order_id', $orderDetail->order_id)->whereNotIn('status', [Constants::ORDER_DETAIL_ORDERED, Constants::ORDER_DETAIL_BASKET])->first();
                if(!$order_detail_){
                    foreach($order->orderDetail as $order_detail){
                        $discount_price = $order_detail->discount_price??0;
                        $order_details_discount_price = $order_details_discount_price + $discount_price;
                        $company_product_price = $company_product_price + $order_detail->price*$order_detail->quantity - $order_detail->discount_price - $coupon_price*$order_detail->quantity;
                        $order_coupon_price = $order_coupon_price + $coupon_price * $order_detail->quantity;
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
            $orderDetail->save();
        }

        return redirect()->route('company_order.index', 2);
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
