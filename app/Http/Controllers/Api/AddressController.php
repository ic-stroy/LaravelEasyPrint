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
use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Constants;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function getCities(Request $request){
        $language = $request->header('language');
        $cities = Cities::where('parent_id', 0)->whereIn('name', ['Toshkent viloyati', 'Toshkent shahri'])->orderBy('id', 'ASC')->get();
        $data = [];
        foreach ($cities as $city){
            $cities_ = [];
            foreach ($city->getDistricts as $district){
                $cities_[] = [
                    'id'=>$district->id,
                    'name'=>table_translate($district,'city',$language),
                    'lat'=>$district->lat,
                    'long'=>$district->lng
                ];
            }
            $data[] = [
                'id'=>$city->id,
                'region'=>table_translate($city,'city',$language),
                'lat'=>$city->lat,
                'long'=>$city->lng,
                'cities'=>$cities_,
            ];
        }
        if(!empty($data)){
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
        if(!$cities){
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
        if(!$address){
            $message = translate_api('Address not found', $language);
            return $this->error($message, 400);
        }
        $cities = Cities::find($request->city_id);
        if(!$cities){
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
            if($address_->cities){
                if($address_->cities->type == 'district'){
                    $city = [
                        'id' => $address_->cities->id,
                        'name' => $address_->cities->name??'',
                        'lat' => $address_->cities->lat??'',
                        'lng' => $address_->cities->lng??'',
                    ];
                    if($address_->cities->region){
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
        if(!empty($address)){
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
        if($address){
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

        $company = Company::where('id', $id)->first();
        $user = User::where(['role_id' => 2, 'company_id' => $id])->first();

        $response = [];

        if(($company) && $user){
//            $full_name = ($user->personalInfo) ? $user->personalInfo->last_name . ' ' . $user->personalInfo->first_name : '';
            $company_name = $company->name??'';
            $company_image = null;
            if($company->image) {
                 $sms_avatar = storage_path('app/public/company/' . $company->image);
            }else{
                 $sms_avatar = storage_path('app/public/company/' . 'no');
            }
            if (file_exists($sms_avatar)) {
                $company_image = asset('storage/company/'.$company->image);
            }

            $total_prints = Warehouse::distinct('product_id')->where('company_id', $id)->count();
            $total_solds = OrderDetail::sum('quantity');
            $data = $this->getProducts($id, $language);

            $response[] = [
                'id' =>$company->id,
                'user_id' => $user->id,
                'full_name' => $company_name,
                'country' => translate_api('Uzbekistan',$language),
                'avatar' => $company_image,
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
        if($model->images){
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

    public function getProducts($company_id, $language)
    {
        $warehouse_products_ = Warehouse::distinct('product_id')->where('company_id', $company_id)->get();
        $warehouse_products = [];

        $product_ides = [];
        foreach ($warehouse_products_ as $warehouse_product_) {
            $product_ides[] = $warehouse_product_->product_id;

            if (!empty($this->getImages($warehouse_product_, 'warehouse'))) {
                $warehouseProducts = $this->getImages($warehouse_product_, 'warehouse');
            } else {
                $warehouseProducts = $this->getImages($warehouse_product_->product, 'product');
            }
            $translate_name=table_translate($warehouse_product_,'warehouse_category',$language);
            if($warehouse_product_->discount){
                $warehouse_discount = $warehouse_product_->discount->percent;
            }elseif($warehouse_product_->product_discount){
                $warehouse_discount = $warehouse_product_->product_discount->percent;
            }else{
                $warehouse_discount = '';
            }
            $warehouse_products[] = [
                'id' => $warehouse_product_->id,
                'name' => $translate_name ?? $warehouse_product_->product->name,
                'price' => $warehouse_product_->price,
                'discount' => $warehouse_discount != '' ? $warehouse_discount : NULL,
                'price_discount' => $warehouse_discount != '' ? $warehouse_product_->price - ($warehouse_product_->price / 100 * (int)$warehouse_discount) : NULL,
                'images' => $warehouseProducts
            ];
        }

        $products_ = Products::where('slide_show', Constants::ACTIVE)
            ->whereIn('id', $product_ides)
            ->get();

        $products = [];
        foreach ($products_ as $product_) {
            $products[] = [
                'id' => $product_->id,
                'name' => $product_->name,
                'price' => $product_->price,
                'discount' => $product_->discount ? $product_->discount->percent : NULL,
                'price_discount' => $product_->discount ? $product_->price - ($product_->price / 100 * $product_->discount->percent) : NULL,
                'images' => $this->getImages($product_, 'product')
            ];
        }

        return  [
            'product_list' => $products,
            'warehouse_product_list' => $warehouse_products
        ];
    }

    public function pickUpFunction(Request $request){
        $language = $request->header('language');
        $super_admins_id = User::where('role_id', 1)->pluck('id')->all();
        $addresses = Address::whereIn('user_id', $super_admins_id)->get();
        $data = [];
        foreach ($addresses as $address){
            if($address->cities){
                if($address->cities->type == 'district'){
                    $city = $address->cities->name??null;
                    if($address->cities->region){
                        $region = $address->cities->region->name??null;
                    }
                }else{
                    $region = $address->cities->name??null;
                }
            }
            $data[] = [
              'id'=>$address->id,
              'name'=>$address->name??null,
              'region'=>$region,
              'city'=>$city,
              'postcode'=>$address->postcode??null,
            ];
        }

        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }


}
