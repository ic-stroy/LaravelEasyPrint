<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function getCities(){
        $cities = Cities::where('parent_id', 0)->orderBy('id', 'ASC')->get();
        foreach ($cities as $city){
            $cities_ = [];
            foreach ($city->getDistricts as $district){
                $cities_[] = [
                    'id'=>$district->id,
                    'name'=>$district->name,
                    'lat'=>$district->lat,
                    'long'=>$district->lng
                ];
            }
            $data[] = [
                'id'=>$city->id,
                'region'=>$city->name,
                'cities'=>$cities_,
            ];
            $response = [
                'status'=>true,
                'message'=>'Success',
                'data'=>$data
            ];
        }
        return response()->json($response);
    }
}
