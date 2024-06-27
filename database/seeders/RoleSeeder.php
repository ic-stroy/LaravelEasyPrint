<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::first();
        if(!isset($role->id)){
            $datas = [
                [
                    'name'=>'Superadmin',
                ],
                [
                    'name'=>'Admin',
                ],
                [
                    'name'=>'Manager',
                ],
                [
                    'name'=>'User',
                ],
            ];
            foreach ($datas as $data){
                Role::create($data);
            }
        }else{
            echo "Role is exist status active";
        }
    }
}
