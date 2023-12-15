<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $datas = [];
        foreach ($companies as $company){
            foreach (Language::all() as $language) {
                if(!CompanyTranslations::where(['lang' => $language->code, 'company_id' => $company->id])->exists()){
                    $datas[] = [
                        'name'=>$company->name,
                        'company_id'=>$company->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        foreach ($datas as $data){
            CompanyTranslations::create($data);
        }
    }
}
