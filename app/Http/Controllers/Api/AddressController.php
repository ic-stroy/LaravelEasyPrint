<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cities;
use Illuminate\Http\Request;
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
        if(count($address)>0){

        }
        $address->delete();
        $message = translate_api('Success', $language);
        return $this->success($message, 200);
    }
}
