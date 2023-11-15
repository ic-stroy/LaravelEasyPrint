<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function auth;
use function bcrypt;
use function response;

class AuthController extends Controller
{
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
}
