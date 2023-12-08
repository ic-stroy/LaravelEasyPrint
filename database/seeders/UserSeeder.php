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
        $personal_info_last_id = PersonalInfo::withTrashed()->select('id')->orderBy('id', 'desc')->first();
        $last_id = isset($personal_info_last_id->id)?$personal_info_last_id->id:0;
        $personal_info = [
            'id'=>(int)$last_id+1,
            'first_name'=>'Superadmin',
            'last_name'=>'Super',
            'middle_name'=>'Admin',
            'gender'=>1,
        ];
        $user = [
            'email'=>'superadmin@example1.com',
            'role_id'=>1,
            'password'=>Hash::make('12345678'),
            'personal_info_id'=>$last_id
        ];
        PersonalInfo::create($personal_info);
        User::create($user);
    }
}
