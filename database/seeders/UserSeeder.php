<?php

namespace Database\Seeders;

use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $is_exist_user = User::withTrashed()->where('email', 'admin@example.com')->first();
        if(!isset($is_exist_user->id)){
            $personal_info = [
                'first_name'=>'Superadmin',
                'last_name'=>'Super',
                'middle_name'=>'Admin',
                'gender'=>1,
            ];
            $personalInfo = PersonalInfo::create($personal_info);
            $user = [
                'email'=>'admin@example.com',
                'role_id'=>1,
                'language'=>'ru',
                'password'=>Hash::make('12345678'),
                'personal_info_id'=>(int)$personalInfo->id
            ];
            User::create($user);
        }else{
            if(!isset($is_exist_user->deleted_at)){
                echo "Current user is exist status deleted";
            }else{
                echo "Current user is exist status active";
            }
        }
    }
}
