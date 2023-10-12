<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.user.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $personal_info = new PersonalInfo();
        $personal_info->first_name = $request->first_name;
        $personal_info->last_name = $request->last_name;
        $personal_info->middle_name = $request->middle_name;
        $personal_info->phone_number = $request->phone_number;
        $letters = range('a', 'z');
        $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
        $random = implode("", $random_array);
        $file = $request->file('avatar');

        if (isset($file)) {
            $image_name = $random . '' . date('Y-m-dh-i-s') . '.' . $file->extension();
            $file->storeAs('public/user/', $image_name);
            $personal_info->avatar = $image_name;
        }

        $personal_info->gender = $request->gender;
        $personal_info->birth_date = $request->birth_date;
        $personal_info->save();

        $model = new User();
        $model->email =  $request->email;
        $model->password = Hash::make($request->password);
        $model->role_id = (int)$request->role_id;
        $model->personal_info_id = $personal_info->id;
        $model->phone_number = $request->phone_number;
        $model->save();

        return redirect()->route('user.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = User::find($id);
        return view('admin.user.show', [
            'model' => $model
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = User::find($id);
        if(isset($model->personalInfo)){
            $personal_info = $model->personalInfo;
            $personal_info->first_name = $request->first_name;
            $personal_info->last_name = $request->last_name;
            $personal_info->middle_name = $request->middle_name;
            $personal_info->phone_number = $request->phone_number;
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);
            $file = $request->file('avatar');
            if (isset($file)) {
                $sms_avatar = storage_path('app/public/user/' . $personal_info->avatar);
                if (file_exists($sms_avatar)) {
                    unlink($sms_avatar);
                }
                $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
                $file->storeAs('public/user/', $image_name);
                $personal_info->avatar = $image_name;
            }
            $personal_info->gender = $request->gender;
            $personal_info->birth_date = $request->birth_date;
            $personal_info->save();
        }else{
            $personal_info = new PersonalInfo();
            $personal_info->first_name = $request->first_name;
            $personal_info->last_name = $request->last_name;
            $personal_info->middle_name = $request->middle_name;
            $personal_info->phone_number = $request->phone_number;
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);
            $file = $request->file('avatar');

            if (isset($file)) {
                $image_name = $random . '' . date('Y-m-dh-i-s') . '.' . $file->extension();
                $file->storeAs('public/user/', $image_name);
                $personal_info->avatar = $image_name;
            }

            $personal_info->gender = $request->gender;
            $personal_info->birth_date = $request->birth_date;
            $personal_info->save();
        }

        $model->email =  $request->email;
        $model->password = Hash::make($request->password);
        $model->role_id = (int)$request->role_id;
        $model->phone_number = $request->phone_number;
        $model->personal_info_id = $personal_info->id;
        $model->save();
        return redirect()->route('user.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = User::find($id);
        if (isset($model->avatar)) {
            $sms_avatar = storage_path('app/public/user/'.$model->avatar);
        } else {
            $sms_avatar = 'no';
        }

        if (file_exists($sms_avatar)) {
            unlink($sms_avatar);
        }

        $model->delete();
        return redirect()->route('user.index')->with('status', __('Successfully deleted'));
    }

}
