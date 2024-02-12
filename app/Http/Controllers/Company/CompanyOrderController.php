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
        $order_data = [];
//        $not_read_order_quantity = OrderDetail::where('order_id', $id)->where('is_read', 0)->count();
        $order_product_quantity_array = OrderDetail::where('order_id', $id)->pluck('quantity')->all();
        foreach($orders as $order){
            $company_product_price = 0;
            $order_has = false;
            $products_with_anime_uploads = [];
            foreach($order->orderDetail as $order_detail){
                $order_product_quantity = array_sum($order_product_quantity_array);
                if((int)$order->coupon_price>0){
                    $coupon_price = (int)$order_detail->quantity * (int)$order->coupon_price/$order_product_quantity;
                }else{
                    $coupon_price = 0;
                }

                $product_types = $product_types + 1;
                $product_quantity = $product_quantity + $order_detail->quantity;
                $company_product_price = $company_product_price + $order_detail->price*$order_detail->quantity - $order_detail->discount_price - $coupon_price;
                $order_has = true;
                $order_detail_all_price = (int)$order_detail->price*(int)$order_detail->quantity - (int)$order_detail->discount_price - (int)$coupon_price;
                if($order_detail->warehouse_id && $order_detail->product_id == NULL &&
                    !empty($order_detail->warehouse) && $order_detail->warehouse->company_id == $user->company_id){
                    $products[] = [$order_detail, $order_detail_all_price];
                }elseif(!$order_detail->warehouse_id && $order_detail->product_id){
                    if ($uploads=Uploads::where('relation_type', Constants::PRODUCT)->where('relation_id', $order_detail->product_id)->get()) {
                        foreach ($uploads as $upload){
                            if (!$upload->image) {
                                $upload->image = 'no';
                            }
                            $order_detail_upload = storage_path('app/public/print/'.$upload->image);
                            if(file_exists($order_detail_upload)){
                                $products_with_anime_uploads[] = asset('storage/print/'.$upload->image);
                            }
                        }
                    }
                    $products_with_anime[] = [$order_detail, $order_detail_all_price, $products_with_anime_uploads];
                }
            }
            $order_coupon_price = $order->coupon_price??0;
            if($order_has == true){
                $order_data[] = [
                    'order'=>$order,
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
        $user = User::where('role_id', 1)->first();
        $list_images = !empty($this->getImages($orderDetail->warehouse, 'warehouses')) ? $this->getImages($orderDetail->warehouse, 'warehouses')[0] : $this->getImages($orderDetail->warehouse->product, 'product')[0];
        $data = [
            'order_id'=>$order->id,
            'order_detail_id'=>$orderDetail->id,
            'order_all_price'=>$orderDetail->price*$orderDetail->quantity-(int)$orderDetail->discount_price - $coupon_price,
            'product'=>[
                'name'=>$orderDetail->warehouse->name,
                'images'=>$list_images
            ],
            'receiver_name'=>$order->receiver_name,
        ];
        if($user){
            Notification::send($user, new OrderNotification($data));
        }
        $orderDetail->save();
        return redirect()->route('company_order.index', 2);
    }

    public function performOrderDetail($id){
        $orderDetail = OrderDetail::find($id);
        $order = $orderDetail->order;
        $order_product_quantity_array = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('quantity')->all();
        $order_product_quantity = array_sum($order_product_quantity_array);
        if((int)$order->coupon_price>0){
            $coupon_price = (int)$orderDetail->quantity * (int)$order->coupon_price/$order_product_quantity;
        }else{
            $coupon_price = 0;
        }
        $user = User::where('role_id', 1)->first();
        $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;
        $list_images = !empty($this->getImages($orderDetail->warehouse, 'warehouses')) ? $this->getImages($orderDetail->warehouse, 'warehouses')[0] : $this->getImages($orderDetail->warehouse->product, 'product')[0];
        $data = [
            'order_id'=>$order->id,
            'order_detail_id'=>$orderDetail->id,
            'order_all_price'=>$orderDetail->price*$orderDetail->quantity-(int)$orderDetail->discount_price - $coupon_price,
            'product'=>[
                'name'=>$orderDetail->warehouse->name,
                'images'=>$list_images
            ],
            'receiver_name'=>$order->receiver_name,
        ];
        Notification::send($user, new OrderNotification($data));
        $orderDetail->save();
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
}
