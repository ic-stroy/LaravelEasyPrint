<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Color;
use App\Models\Company;
use Illuminate\Http\Request;

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
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Company();
        $address = new Address();
        $address->region = $request->region;
        $address->district = $request->district;
        $address->latitude = $request->address_lat;
        $address->longitude = $request->address_long;
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
        $address->region = $request->region;
        $address->district = $request->district;
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
        $address->delete();
        return redirect()->route('company.index')->with('status', __('Successfully deleted'));
    }
}
