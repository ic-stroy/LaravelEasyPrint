<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Warehouse;
use App\Models\WarehouseTranslations;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $datas = [];
        foreach ($warehouses as $warehouse){
            foreach (Language::all() as $language) {
                if(!WarehouseTranslations::where(['lang' => $language->code, 'warehouse_id' => $warehouse->id])->exists()){
                    $datas[] = [
                        'name'=>$warehouse->name,
                        'warehouse_id'=>$warehouse->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        foreach ($datas as $data){
            WarehouseTranslations::create($data);
        }
    }
}
