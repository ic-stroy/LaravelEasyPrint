<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EskizToken;
use App\Models\PersonalInfo;
use App\Models\User;
use App\Models\UserVerify;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use function auth;
use function bcrypt;

class AuthController extends Controller
{
    public function PhoneRegister(Request $request){
        date_default_timezone_set("Asia/Tashkent");
        $language = $request->header('language');
        $fields = $request->validate([
            'phone'=>'required|string'
        ]);
        $client = new Client();
        $eskiz_token = EskizToken::firstOrNew();
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
        if($user_verify){
            if(strtotime($user_verify->updated_at) + 60 > strtotime('now')){
                return $this->error("Fail message not sent. You must wait 1 minute to resend sms", 400);
            }
        }
        if(!$eskiz_token->expire_date && strtotime('now') > (int)$eskiz_token->expire_date){
            $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/auth/login');
            $res = $client->sendAsync($guzzle_request, $token_options)->wait();
            $res_array = json_decode($res->getBody());
            $eskizToken = EskizToken::firstOrNew();
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

    public function PhoneVerify(Request $request){
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
                $is_registred = false;
                $user = User::withTrashed()->find($model->user_id);
                if(!$user){
                    $new_user = new User();
                    $personal_info = new PersonalInfo();
                    $personal_info->phone_number = (int)$fields['phone_number'];
                    $personal_info->save();
                    $new_user->personal_info_id = $personal_info->id;
                    $new_user->language = $request->header('language');
                    $new_user->save();
                    $model->user_id = $new_user->id;
                    $model->save();
                    $new_user->email = $model->phone_number;
                    $new_user->password = Hash::make($model->verify_code);
                    $token = $new_user->createToken('myapptoken')->plainTextToken;
                    $new_user->role_id = 4;
                    $new_user->token = $token;
                    $new_user->save();
                    $message = 'Success';
                    return $this->success($message, 201, ['token'=>$token, 'is_registred'=>$is_registred]);
                }else{
                    $is_registred = true;
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
                    return $this->success($message, 201, ['token'=>$token, 'is_registred'=>$is_registred]);
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

    public function register(Request $request)
    {
        $language = $request->header('language');
        $user = Auth::user();
        $fields = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);
        $user->password = bcrypt($fields['password']);
        if($user->personalInfo){
            $personal_info = $user->personalInfo;
            $personal_info->first_name = $fields['name'];
            $personal_info->last_name = $fields['surname'];
            $personal_info->save();
        }else{
            $personal_info = new PersonalInfo();
            $personal_info->first_name = $fields['name'];
            $personal_info->last_name = $fields['surname'];
            $personal_info->save();
            $user->personal_info_id = $personal_info->id;
        }
        $user->role_id = 4;
        $user->save();
        if($user->personalInfo){
            $first_name = $user->personalInfo ?$user->personalInfo->first_name:null;
        }else{
            $first_name = null;
        }
        if($user->personalInfo){
            $last_name = $user->personalInfo ?$user->personalInfo->last_name:null;
        }else{
            $last_name = null;
        }
        $data = [
            'user' => [
                "first_name"=>$first_name,
                "last_name"=>$last_name,
                "email"=> $user->email,
                "role_id"=> $user->role_id,
                "updated_at"=> $user->updated_at,
                "created_at"=>$user->created_at,
                "id"=>$user->id,
                ],
            'token' => $user->token??null
        ];
        $message = translate_api('success', $language);
        return $this->success($message, 200,$data);
    }

    public function login(Request $request) {
        $language = $request->header('language');
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            $message = translate_api('Password or phone number is incorrect', $language);
            return $this->error($message, 401, []);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->token = $token;
        $user->save();
        $is_admin = false;
        if(in_array($user->role_id, [2, 3])){
            $is_admin = true;
        }
        $data = [
            'user' => $user,
            'token' => $token,
            'is_admin'=>$is_admin
        ];
        $message = translate_api('success', $language);
        return $this->success($message, 200, $data);
    }

    public function logout(Request $request) {
        $language = $request->header('language');
        auth()->user()->tokens()->delete();
        $message = translate_api('success', $language);
        return $this->success($message, 200, []);
    }

    public function redirectGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(Request $request){
        $user = Socialite::driver('google')->user();
        $model = $this->regOrLogin($user);
        $token = $model->createToken('myapptoken')->plainTextToken;
        $model->token = $token;
        $model->save();
        $this->responseUser($model);
//        return redirect()->route('responseUser', [$model]);
    }

    public function regOrLogin($user){
        $model = User::where('email', $user->email)->first();
        if(!$model){
            $model = new User();
            $model->email = $user->email;
            $model->password = bcrypt(rand(10000, 100000));
            $model->role_id = 4;
            $model->save();
            $personal_info = new PersonalInfo();
            $personal_info->first_name = $user->name;
            $personal_info->save();
            $model->personal_info_id = $personal_info->id;
            $model->save();
        }
        return $model;
//        Auth::login($user);
    }

    public function responseUser(Request $request, $model){
        $language = $request->header('language');
        $message = translate_api('success', $language);
        return $this->success($message, 200, [$model]);
    }
}
