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
    public function create()
    {
        $addresses = Address::all();
        return view('admin.company.create', ['addresses'=>$addresses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Company();
        $model->address_id = $request->address_id;
        $model->delivery_price = $request->delivery_price;
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
        $addresses = Address::all();
        return view('admin.company.edit', ['company'=> $company, 'addresses'=>$addresses]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Company::find($id);
        $model->address_id = $request->address_id;
        $model->delivery_price = $request->delivery_price;
        $model->save();
        return redirect()->route('company.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Company::find($id);
        $model->delete();
        return redirect()->route('company.index')->with('status', __('Successfully deleted'));
    }
}
