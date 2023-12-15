<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Role;
use App\Models\RoleTranslations;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        $datas = [];
        foreach ($roles as $role){
            foreach (Language::all() as $language) {
                if(!RoleTranslations::where(['lang' => $language->code, 'role_id' => $role->id])->exists()){
                    $datas[] = [
                        'name'=>$role->name,
                        'role_id'=>$role->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        foreach ($datas as $data){
            RoleTranslations::create($data);
        }
    }
}
