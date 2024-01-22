<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cities;
use App\Models\Company;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\OrderDetail;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Constants;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function getCities(Request $request){
        $language = $request->header('language');
        $cities = Cities::where('parent_id', 0)->orderBy('id', 'ASC')->get();
        $data = [];
        foreach ($cities as $city){
            $cities_ = [];
            foreach ($city->getDistricts as $district){
                $cities_[] = [
                    'id'=>$district->id,
                    'name'=>$district->name,
                    'lat'=>$district->lat,
                    'long'=>$district->lng
                ];
            }
            $data[] = [
                'id'=>$city->id,
                'region'=>$city->name,
                'lat'=>$city->lat,
                'long'=>$city->lng,
                'cities'=>$cities_,
            ];
        }
        if(count($data)>0){
            $message = translate_api('Success', $language);
            return $this->success($message, 200, $data);
        }else{
            $message = translate_api('No cities', $language);
            return $this->error($message, 400);
        }
    }

    public function setAddress(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        $address = new Address();
        $cities = Cities::find($request->city_id);
        if(!isset($cities->id)){
            $message = translate_api('City or Region not found', $language);
            return $this->error($message, 400);
        }
        $address->city_id = $request->city_id;
        $address->name = $request->name;
        $address->user_id = $user->id;
        $address->postcode = $request->postcode;
        $address->save();
        $message = translate_api('Success', $language);
        return $this->success($message, 200, []);
    }

    public function editAddress(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->find($request->id);
        if(!isset($address->id)){
            $message = translate_api('Address not found', $language);
            return $this->error($message, 400);
        }
        $cities = Cities::find($request->city_id);
        if(!isset($cities->id)){
            $message = translate_api('City or Region not found', $language);
            return $this->error($message, 400);
        }
        $address->city_id = $request->city_id;
        $address->name = $request->name;
        $address->user_id = $user->id;
        $address->postcode = $request->postcode;
        $address->save();
        $message = translate_api('Success', $language);
        return $this->success($message, 200);
    }

    public function getAddress(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        $address = [];
        $city = [];
        $region = [];
        foreach ($user->addresses as $address_) {
            if(isset($address_->cities->id)){
                if($address_->cities->type == 'district'){
                    $city = [
                        'id' => $address_->cities->id,
                        'name' => $address_->cities->name??'',
                        'lat' => $address_->cities->lat??'',
                        'lng' => $address_->cities->lng??'',
                    ];
                    if(isset($address_->cities->region->id)){
                        $region = [
                            'id' => $address_->cities->region->id,
                            'name' => $address_->cities->region->name??'',
                            'lat' => $address_->cities->region->lat??'',
                            'lng' => $address_->cities->region->lng??'',
                        ];
                    }
                }else{
                    $region = [
                        'id' => $address_->cities->id,
                        'name' => $address_->cities->name??'',
                        'lat' => $address_->cities->lat??'',
                        'lng' => $address_->cities->lng??'',
                    ];
                }
            }

            $address[] = [
                'id'=>$address_->id,
                'name'=>$address_->name??null,
                'region'=>$region,
                'city'=>$city,
                'latitude'=>$address_->latitude??null,
                'longitude'=>$address_->longitude??null,
                'postcode'=>$address_->postcode??null,
            ];
        }
        if(count($address)>0){
            $message = translate_api('Success', $language);
            return $this->success($message, 200, $address);
        }else{
            $message = translate_api('No address', $language);
            return $this->error($message, 400);
        }
    }

    public function destroy(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->find($request->id);
        if(isset($address->id)){
            $address->delete();
            $message = translate_api('Success', $language);
            return $this->success($message, 200);
        }else{
            $message = translate_api('No address', $language);
            return $this->error($message, 400);
        }
    }



    // get company products
    public function getCompanyProducts(Request $request){
        $language = $request->header('language');
     
        $id = (int)$request->id;

        $company = Company::where('id',$id)->first();
        $user = User::where(['role_id' => 2, 'company_id' => $id])->first();

        $response = [];
        
        if((isset($company) && $company != NULL) && (isset($user) && $user != NULL)){
            $full_name = ($user->personalInfo) ? $user->personalInfo->last_name . ' ' . $user->personalInfo->first_name : '';
            
            $user_image = null;
            if(isset($user->personalInfo->avatar)){
                $sms_avatar = storage_path('app/public/user/' . $user->personalInfo->avatar);
            }else{
                $sms_avatar = storage_path('app/public/user/' . 'no');
            }
            if (file_exists($sms_avatar)) {
                $user_image = asset('storage/user/'.$user->personalInfo->avatar);
            }

            // total_prints
            $total_prints = Warehouse::where('company_id',$id)->count();
            // total_solds
            $total_solds = OrderDetail::sum('quantity');

            $warehouse_products_ = Warehouse::distinct('product_id')->where('company_id', $id)->get();
            $warehouse_products = [];
            
            $product_ides = [];
            foreach ($warehouse_products_ as $warehouse_product_) {
                $product_ides[] = $warehouse_product_->product_id;

                if (count($this->getImages($warehouse_product_, 'warehouse'))>0) {
                    $warehouseProducts = $this->getImages($warehouse_product_, 'warehouse');
                } else {
                    $warehouseProducts = $this->getImages($warehouse_product_->product, 'product');
                }
                $translate_name=table_translate($warehouse_product_,'warehouse_category',$language);
                $warehouse_products[] = [
                    'id' => $warehouse_product_->id,
                    'name' => $translate_name ?? $warehouse_product_->product->name,
                    'price' => $warehouse_product_->price,
                    'discount' => (isset($warehouse_product_->discount)) > 0 ? $warehouse_product_->discount->percent : NULL,
                    'price_discount' => (isset($warehouse_product_->discount)) > 0 ? $warehouse_product_->price - ($warehouse_product_->price / 100 * $warehouse_product_->discount->percent) : NULL,
                    'images' => $warehouseProducts
                ];
            }

            $products_ = DB::table('products')
                ->select('id','name', 'price', 'images')
                ->where('slide_show', Constants::ACTIVE)
                ->whereIn('id', $product_ides)
                ->get();

            $products = [];
            foreach ($products_ as $product_) {
                $products[] = [
                    'id' => $product_->id,
                    'name' => $product_->name,
                    'price' => $product_->price,
                    'discount' => (isset($product_->discount)) > 0 ? $product_->discount->percent : NULL,
                    'price_discount' => (isset($product_->discount)) > 0 ? $product_->price - ($product_->price / 100 * $product_->discount->percent) : NULL,
                    'images' => $this->getImages($product_, 'product')
                ];
            }

            $data = [
                'product_list' => $products,
                'warehouse_product_list' => $warehouse_products
            ];

            $response[] = [
                'id' =>$company->id,
                'user_id' => $user->id,
                'full_name' => $full_name,
                'country' => translate_api('Uzbekistan',$language),
                'avatar' => $user_image,
                'total_prints' => $total_prints,
                'total_solds' => $total_solds,
                'registration_date' => date('d.m.Y', strtotime(date($company->created_at))),
                'products' => $data
            ];    

            $message=translate_api('Success',$language);
            return $this->success($message, 200, $response);
        }
        else{
            $message=translate_api('Company not found or User not found',$language);
            return $this->error($message, 500);
        }

    }

    public function getImages($model, $text){
        if(isset($model->images)){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                if($text == 'warehouse'){
                    $images[] = asset('storage/warehouses/'.$image_);
                }elseif($text == 'product'){
                    $images[] = asset('storage/products/'.$image_);
                }
            }
        }else{
            $images = [];
        }
        return $images;
    }


}
