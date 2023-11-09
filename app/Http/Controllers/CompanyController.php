<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cities;
use App\Models\Color;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->get();
        return view('admin.company.index', ['companies'=> $companies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
//        $response = Http::get(asset('assets/json/cities.json'));
//        $cities = json_decode($response);
//        foreach ($cities as $city){
//            $model_region = new Cities();
//            $model_region->name = $city->region;
//            $model_region->type = 'region';
//            $model_region->parent_id = 0;
//            $model_region->save();
//            foreach ($city->cities as $city_district){
//                $model = new Cities();
//                $model->name = $city_district->name;
//                $model->type = 'district';
//                $model->parent_id = $model_region->id;
//                $model->lng = $city_district->long;
//                $model->lat = $city_district->lat;
//                $model->save();
//            }
//        }
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Company();
        $address = new Address();
        $address->city_id = $request->district;
        $address->latitude = $request->address_lat;
        $address->longitude = $request->address_long;
        $address->name = $request->address_name;
        $address->postcode = $request->postcode;
        $address->save();
        $model->name = $request->name;
        $model->delivery_price = $request->delivery_price;
        $model->address_id = $address->id;
        $model->save();
        return redirect()->route('company.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Company::find($id);
        return view('admin.company.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::find($id);
        return view('admin.company.edit', ['company'=> $company]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Company::find($id);
        if(isset($model->address->id)){
            $address = $model->address;
        }else{
            $address = new Address();
        }
        $address->city_id = $request->district;
        $address->name = $request->address_name;
        $address->postcode = $request->postcode;
        $address->latitude = $request->address_lat;
        $address->longitude = $request->address_long;
        $address->save();
        $model->name = $request->name;
        $model->delivery_price = $request->delivery_price;
        $model->address_id = $address->id;
        $model->save();
        return redirect()->route('company.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Company::find($id);
        $address = $model->address;
        $model->delete();
        if(isset($address->id)){
            $address->delete();
        }
        return redirect()->route('company.index')->with('status', __('Successfully deleted'));
    }

}
