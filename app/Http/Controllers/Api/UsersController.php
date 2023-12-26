<?php

namespace App\Http\Controllers\api;

use App\Constants;
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
        $personal_info->email = $request->email;

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
            $user_image = null;
            if(isset($user->personalInfo->avatar)){
                $sms_avatar = storage_path('app/public/user/' . $user->personalInfo->avatar);
            }else{
                $sms_avatar = storage_path('app/public/user/' . 'no');
            }
            if (file_exists($sms_avatar)) {
                $user_image = asset('storage/user/'.$user->personalInfo->avatar);
            }
            switch ($user->personalInfo->gender){
                case 1:
                    $gender = Constants::MALE;
                    break;
                case 2:
                    $gender = Constants::FEMALE;
                    break;
                default:
                    $gender = null;
            }
            if($user->personalInfo->birth_date){
                $birth_date = date("d.m.Y", strtotime($user->personalInfo->birth_date));
            }else{
                $birth_date = null;
            }

            $data = [
                "id"=>$user->id,
                "first_name" => $user->personalInfo->first_name??null,
                "last_name" => $user->personalInfo->last_name??null,
                "phone_number" => $user->personalInfo->phone_number??null,
                "gender" => $gender,
                "email" => $user->personalInfo->email??null,
                "image"=>$user_image,
                "birth_date"=>$birth_date
            ];
        }else{
            $data = [];
        }
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
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
