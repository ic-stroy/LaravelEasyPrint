<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        $user = Auth::user();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->token = $token;
        $user->save();
        return view('index');
    }

    public function setCities(){
        if(!Cities::withTrashed()->exists()){
            $response = Http::get(asset("assets/json/cities.json"));
            $cities = json_decode($response);
            foreach ($cities as $city){
                if(!Cities::where('name', $city->region)->exists()){
                    $model_region = new Cities();
                    $model_region->name = $city->region;
                    $model_region->type = 'region';
                    $model_region->parent_id = 0;
                    $model_region->lng = $city->long;
                    $model_region->lat = $city->lat;
                    $model_region->save();
                    foreach ($city->cities as $city_district){
                        $model = new Cities();
                        $model->name = $city_district->name;
                        $model->type = 'district';
                        $model->parent_id = $model_region->id;
                        $model->lng = $city_district->long;
                        $model->lat = $city_district->lat;
                        $model->save();
                    }
                }else{
                    $model_region = Cities::where('name', $city->region)->first();
                    $model_region->lng = $city->long;
                    $model_region->lat = $city->lat;
                    $model_region->save();
                }
            }
        }

    }
}
