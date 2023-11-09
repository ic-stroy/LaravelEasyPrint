<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\PersonalInfo;
use App\Models\User;
use App\Models\Role;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class CompanyUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('company.user.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $addresses = Address::select('id', 'name')->get();
        $roles = Role::select('id', 'name')->get();
        return view('company.user.create', ['roles'=>$roles, 'companies'=>$companies, 'addresses'=>$addresses]);
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
        $file = $request->file('avatar');
        $this->imageSave($file, $personal_info, 'store');
        $personal_info->gender = $request->gender;
        $personal_info->birth_date = $request->birth_date;
        $personal_info->save();

        $model = new User();
        $model->email =  $request->email;
        $model->password = Hash::make($request->password);
        $model->role_id = (int)$request->role_id;
        $model->personal_info_id = $personal_info->id;
        $model->phone_number = $request->phone_number;
        $model->language = 'ru';
        $model->company_id = $request->company_id;
        $model->address_id = $request->address_id;
        $model->save();

        return redirect()->route('user.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = User::find($id);
        if(isset($model->personalInfo->birth_date)){
            $birth_date_array = explode(' ', $model->personalInfo->birth_date);
            $now_time = strtotime('now');
            $birth_time = strtotime($birth_date_array[0]);
            $month = date('m', ($now_time));
            $day = date('d', ($now_time));
            $birth_month = date('m', ($birth_time));
            $birth_date = date('d', ($birth_time));
            $year = date('Y', ($now_time));
            $birth_year = date('Y', ($birth_time));
            $year_old = 0;
            if($year > $birth_year){
                $year_old = $year - $birth_year - 1;
                if($month > $birth_month){
                    $year_old = $year_old +1;
                }elseif($month == $birth_month){
                    if($day >= $birth_date){
                        $year_old = $year_old +1;
                    }
                }
            }
        }
        return view('company.user.show', [
            'model' => $model,
            'year_old' => $year_old
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companies = Company::all();
        $addresses = Address::select('id', 'name')->get();
        $user = User::find($id);
        return view('company.user.edit', [
            'user' => $user,
            'companies'=>$companies,
            'addresses'=>$addresses
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
            $file = $request->file('avatar');
            $this->imageSave($file, $personal_info, 'update');
            $personal_info->gender = $request->gender;
            $personal_info->birth_date = $request->birth_date;
            $personal_info->save();
        }else{
            $personal_info = new PersonalInfo();
            $personal_info->first_name = $request->first_name;
            $personal_info->last_name = $request->last_name;
            $personal_info->middle_name = $request->middle_name;
            $personal_info->phone_number = $request->phone_number;
            $file = $request->file('avatar');
            $this->imageSave($file, $personal_info, 'store');
            $personal_info->gender = $request->gender;
            $personal_info->birth_date = $request->birth_date;
            $personal_info->save();
        }

        $model->email =  $request->email;
        $model->password = Hash::make($request->password);
        $model->role_id = (int)$request->role_id;
        $model->personal_info_id = $personal_info->id;
        $model->phone_number = $request->phone_number;
        $model->language = 'ru';
        $model->company_id = $request->company_id;
        $model->address_id = $request->address_id;
        $model->save();
        return redirect()->route('user.index')->with('status', __('Successfully updated'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = User::find($id);
        if (isset($model->personalInfo->id)) {
            $model->personalInfo->delete();
        }

        if(isset($model->personalInfo->avatar)) {
            $sms_avatar = storage_path('app/public/user/'.$model->personalInfo->avatar);
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