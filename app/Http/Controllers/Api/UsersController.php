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
        $language = $request->header('language');
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

         $message = translate_api('Success', $language);
        return $this->success($message, 200, []);
    }

    public function getPersonalInformation(Request $request){
        $language = $request->header('language');
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
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }

    public function setAddress(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        if(isset($user->address->id)){
            $address = $user->address;
        }else{
            $address = new Address();
        }
        $address->city_id = $request->city_id;
        $address->name = $request->name;
        $address->user_id = $user->id;
        $address->postcode = $request->postcode;
        $address->save();
        $message = translate_api('Success', $language);
        return $this->success($message, 200, []);
    }

    public function imageSave($file, $personal_info, $text){
        if (isset($file)) {
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);

            if($text == 'update'){
                if(isset($personal_info->avatar)){
                    $sms_avatar = storage_path('app/public/user/' . $personal_info->avatar);
                }else{
                    $sms_avatar = storage_path('app/public/user/' . 'no');
                }
                if (file_exists($sms_avatar)) {
                    unlink($sms_avatar);
                }
            }
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/user/', $image_name);
            $personal_info->avatar = $image_name;
            return $personal_info;
        }
    }
}
