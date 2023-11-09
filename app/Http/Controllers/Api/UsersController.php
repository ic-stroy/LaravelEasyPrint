<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function setPersonalInformation(Request $request){
        $user = Auth::user();
        if(isset($user->personalInfo)){
            $personal_info = $user->personalInfo;
        }else{
            $personal_info = new PersonalInfo();
        }
        $personal_info->first_name = $request->first_name;
        $personal_info->last_name = $request->last_name;
        $personal_info->phone_number = $request->phone_number;
        $personal_info->gender = $request->gender;

        $file = $request->file('image');
        $this->imageSave($file, $personal_info, 'update');
        $personal_info->birth_date = $request->birth_date;
        $personal_info->save();
        $user->personal_info_id = $personal_info->id;
        $user->save();
        $response = [
            'status'=>true,
            'message'=>'Success'
        ];
        return response()->json($response);
    }

    public function getPersonalInformation(){
        $user = Auth::user();
        if(isset($user->personalInfo)){
            $data = [
                "id"=>$user->id,
                "first_name" => $user->personalInfo->first_name,
                "last_name" => $user->personalInfo->last_name,
                "phone_number" => $user->personalInfo->last_name,
                "gender" => $user->personalInfo->last_name,
                "image"=>asset('storage/user/'.$user->personalInfo->avatar),
                "birth_date"=>$user->personalInfo->birth_date
            ];
        }else{
            $data = [];
        }
        $response = [
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ];
        return response()->json($response);
    }

    public function setAddress(Request $request){
        $user = Auth::user();
        if(isset($user->address->id)){
            $address = $user->address;
        }else{
            $address = new Address();
        }
        $address->city_id = $request->city_id;
        $address->name = $request->name;
        $address->postcode = $request->postcode;
        $address->save();
        $user->address_id = $address->id;
        $user->save();
        $response = [
            'status'=>true,
            'message'=>'Success'
        ];
        return response()->json($response);
    }
}
