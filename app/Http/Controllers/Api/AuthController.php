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
        $eskiz_token = EskizToken::first();
        $user_verify = UserVerify::withTrashed()->where('phone_number', (int)$fields['phone'])->first();
        $random = rand(100000, 999999);
        if(!isset($user_verify->id)){
            $user_verify = new UserVerify();
            $user_verify->phone_number = (int)$request->phone;
            $user_verify->status_id = 1;
        }elseif(isset($user_verify->deleted_at)){
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
        if(!isset($eskiz_token->expire_date)){
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
        if(isset($result)){
            $user_verify->verify_code = $random;
            $user_verify->save();
            return $this->success("Success", 200, ['Verify_code'=>$random]);
        }else{
            return $this->error(translate_api("Fail message not sent. Try again", $language), 400);
        }
    }

    public function loginToken(Request $request){
        date_default_timezone_set("Asia/Tashkent");
        $language = $request->header('language');
        $fields = $request->validate([
            'phone_number'=>'required',
            'verify_code'=>'required',
            'device_type'=>'nullable',
            'device_id'=>'nullable',
        ]);
        $model = UserVerify::withTrashed()->where('phone_number', (int)$fields['phone_number'])->first();
        if(isset($model->id)){
            if(strtotime('-7 minutes') > strtotime($model->updated_at)){
                $model->verify_code = rand(100000, 999999);
                $model->save();
                return $this->error(translate_api('Your sms code expired. Resend sms code', $language), 400);
            }
            if(isset($model->deleted_at)){
                $model->deleted_at = NULL;
            }
            if($model->verify_code == $fields['verify_code']){
                $is_registred = false;
                $user = User::withTrashed()->find($model->user_id);
                if(!isset($user->id)){
                    $new_user = new User();
                    $personal_info = new PersonalInfo();
                    $personal_info->phone_number = (int)$fields['phone_number'];
                    $personal_info->save();
                    $new_user->personal_info_id = $personal_info->id;
                    $personal_info->phone_number = (int)$fields['phone_number'];
                    $personal_info->save();
                    $new_user->personal_info_id = $personal_info->id;
                    $new_user->rating = 4.5;
                    $new_user->language = $request->header('language');
                    $new_user->save();
                    $model->user_id = $new_user->id;
                    $model->save();
                    $new_user->email = $model->phone_number;
                    $new_user->password = Hash::make($model->verify_code);
                    $token = $new_user->createToken('myapptoken')->plainTextToken;
                    $new_user->token = $token;
                    $new_user->save();
                    $message = 'Success';
                    return $this->success($message, 201, ['token'=>$token, 'is_registred'=>$is_registred]);
                }else{
                    $is_registred = true;
                    if(isset($user->deleted_at)){
                        $user->deleted_at = NULL;
                    }
                    $user->email = $model->phone_number;
                    if(!isset($user->personal_info_id)){
                        $personal_info = new PersonalInfo();
                        $personal_info->phone_number = (int)$fields['phone_number'];
                        $personal_info->save();
                        $user->personal_info_id = $personal_info->id;
                    }else{
                        $personal_info = PersonalInfo::withTrashed()->find($user->personal_info_id);
                        if(!isset($personal_info->id)){
                            $personal_info = new PersonalInfo();
                            $personal_info->phone_number = (int)$fields['phone_number'];
                            $personal_info->save();
                            $user->personal_info_id = $personal_info->id;
                        }elseif(isset($personal_info->deleted_at)){
                            $personal_info->deleted_at = NULL;
                        }
                    }
                    $personal_info->save();
                    $user->password = Hash::make($model->verify_code);
                    $token = $user->createToken('myapptoken')->plainTextToken;
                    $user->token = $token;
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
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role_id' => 4
        ]);
        $personal_info = new PersonalInfo();
        $personal_info->first_name = $fields['name'];
        $personal_info->save();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->token = $token;
        $user->personal_info_id = $personal_info->id;
        $user->save();
        $data = [
            'user' => $user,
            'token' => $token
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
        $data = [
            'user' => $user,
            'token' => $token
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
