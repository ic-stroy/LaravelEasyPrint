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
                    'id'=>1,
                    'name'=>'Superadmin',
                ],
                [
                    'id'=>2,
                    'name'=>'Admin',
                ],
                [
                    'id'=>3,
                    'name'=>'Manager',
                ],
                [
                    'id'=>4,
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
