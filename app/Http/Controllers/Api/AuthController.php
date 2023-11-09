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
        $response = [
            'status'=>true,
            'message'=>"Success",
            'data'=>[
                'user' => $user,
                'token' => $token
            ]
        ];
        return response()->json($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'bad creds'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->token = $token;
        $user->save();
        $response = [
            'status'=>true,
            'message'=>"Success",
            'data'=>[
                'user' => $user,
                'token' => $token
            ]
        ];
        return response()->json($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        $response = [
            'status'=>true,
            'message'=>"Success",
        ];
        return response()->json($response, 201);
    }
}
