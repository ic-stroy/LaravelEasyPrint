<?php

namespace App\Http\Controllers\Api;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\EskizToken;
use App\Models\PersonalInfo;
use App\Models\User;
use App\Models\UserVerify;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function setPersonalInformation(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        if($user->personalInfo){
            $personal_info = $user->personalInfo;
        }else{
            $personal_info = new PersonalInfo();
        }
        if($request->first_name){
            $personal_info->first_name = $request->first_name;
        }
        if($request->last_name){
            $personal_info->last_name = $request->last_name;
        }
        if($request->phone_number){
            $personal_info->phone_number = $request->phone_number;
        }
        if($request->gender){
            $personal_info->gender = $request->gender;
        }
        if($request->email){
            $personal_info->email = $request->email;
        }

        $file = $request->file('image');
        $this->imageSave($file, $personal_info, 'update');

        if($request->birth_date){
            $personal_info->birth_date = date('Y-m-d', strtotime($request->birth_date));
        }
        $personal_info->save();
        $user->personal_info_id = $personal_info->id;
        $user->save();

        if($user->personalInfo){
            $user_image = null;
            if($user->personalInfo->avatar){
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

    public function getPersonalInformation(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        if($user->personalInfo){
            $user_image = null;
            if($user->personalInfo->avatar){
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
                if($personal_info){
                    if($personal_info->avatar){
                        $sms_avatar = storage_path('app/public/user/' . $personal_info->avatar);
                    }else{
                        $sms_avatar = storage_path('app/public/user/' . 'no');
                    }
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

    public function sendCode(Request $request){
        date_default_timezone_set("Asia/Tashkent");
        $language = $request->header('language');
        $fields = $request->validate([
            'phone'=>'required|string'
        ]);
        $client = new Client();
        $eskiz_token = EskizToken::first();
        $user_verify = UserVerify::withTrashed()->where('phone_number', (int)$fields['phone'])->first();
        $random = rand(100000, 999999);
        if(!$user_verify){
            $user_verify = new UserVerify();
            $user_verify->phone_number = (int)$request->phone;
            $user_verify->status_id = 1;
        }elseif($user_verify->deleted_at){
            $user_verify->status_id = 1;
            $user_verify->deleted_at = NULL;
        }
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
        if(!$eskiz_token->expire_date){
            $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/auth/login');
            $res = $client->sendAsync($guzzle_request, $token_options)->wait();
            $res_array = json_decode($res->getBody());
            $eskizToken = new EskizToken();
            $eskizToken->token = $res_array->data->token;
            $eskizToken->expire_date = strtotime('+28 days');
            $eskizToken->save();
        }elseif(strtotime('now') > (int)$eskiz_token->expire_date){
            $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/auth/login');
            $res = $client->sendAsync($guzzle_request, $token_options)->wait();
            $res_array = json_decode($res->getBody());
            $eskizToken = EskizToken::first();
            $eskizToken->token = $res_array->data->token;
            $eskizToken->expire_date = strtotime('+28 days');
            $eskizToken->save();
        }
        $eskiz_token = '';
        $eskiz_token = EskizToken::first();
        $options = [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => "Bearer $eskiz_token->token",
            ],
            'multipart' => [
                [
                    'name' => 'mobile_phone',
                    'contents' => $request->phone
                ],
                [
                    'name' => 'message',
                    'contents' => translate_api('Easy Print - Sizni bir martalik tasdiqlash kodingiz', $language).': '.$random
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
        if($result){
            $user_verify->verify_code = $random;
            $user_verify->save();
            return $this->success("Success", 200);
        }else{
            return $this->error(translate_api("Fail message not sent. Try again", $language), 400);
        }
    }

    public function verifyToken(Request $request){
        date_default_timezone_set("Asia/Tashkent");
        $language = $request->header('language');
        $fields = $request->validate([
            'phone_number'=>'required',
            'verify_code'=>'required',
        ]);
        $model = UserVerify::withTrashed()->where('phone_number', (int)$fields['phone_number'])->first();
        if($model){
            if(strtotime('-7 minutes') > strtotime($model->updated_at)){
                $model->verify_code = rand(100000, 999999);
                $model->save();
                return $this->error(translate_api('Your sms code expired. Resend sms code', $language), 400);
            }
            if($model->deleted_at){
                $model->deleted_at = NULL;
            }
            if($model->verify_code == $fields['verify_code']){
                $user = User::withTrashed()->find($model->user_id);
                if(!$user){
                    $message = translate_api('this phone is not registered', $language);
                    return $this->error($message, 201);
                }else{
                    if($user->deleted_at){
                        $user->deleted_at = NULL;
                    }
                    $user->email = $model->phone_number;
                    if(!$user->personal_info_id){
                        $personal_info = new PersonalInfo();
                        $personal_info->phone_number = (int)$fields['phone_number'];
                        $personal_info->save();
                        $user->personal_info_id = $personal_info->id;
                    }else{
                        $personal_info = PersonalInfo::withTrashed()->find($user->personal_info_id);
                        if(!$personal_info){
                            $personal_info = new PersonalInfo();
                            $personal_info->phone_number = (int)$fields['phone_number'];
                            $personal_info->save();
                            $user->personal_info_id = $personal_info->id;
                        }elseif($personal_info->deleted_at){
                            $personal_info->deleted_at = NULL;
                        }
                    }
                    $personal_info->save();
                    $user->password = Hash::make($model->verify_code);
                    $token = $user->createToken('myapptoken')->plainTextToken;
                    $user->token = $token;
                    $user->role_id = 4;
                    $user->language = $request->header('language');
                    $user->save();
                    $model->save();
                    $message = 'Success';
                    return $this->success($message, 201, ['token'=>$token]);
                }
            }else{
                $message = "Failed your token didn't match";
                return $this->error(translate_api($message, $language), 400);
            }
        }else{
            $message = "Failed your token didn't match";
            return $this->error(translate_api($message, $language), 400);
        }
    }

    public function passwordReset(Request $request){
        $language = $request->header('language');
        $user = Auth::user();
        if (isset($request->password) && isset($request->password_confirmation)) {
            if ($request->password != '' && $request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->save();
            }else{
                $message = translate_api('Your new password confirmation is incorrect', $language);
                return $this->error($message, 200);
            }
        }
        $message = translate_api('Successfully reseted', $language);
        return $this->success($message, 200);
    }

}
