<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Address::orderBy('created_at', 'desc')->get();
        return view('admin.address.index', ['addresses'=> $addresses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.address.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Address();
        $model->name = $request->name;
        $model->latitude = $request->latitude;
        $model->longitude = $request->longitude;
        $model->save();
        return redirect()->route('address.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Address::find($id);
        return view('admin.address.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $address = Address::find($id);
        return view('admin.address.edit', ['address'=> $address]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Address::find($id);
        $model->name = $request->name;
        $model->latitude = $request->latitude;
        $model->longitude = $request->longitude;
        $model->save();
        return redirect()->route('address.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Address::find($id);
        $model->delete();
        return redirect()->route('address.index')->with('status', __('Successfully deleted'));
    }
}
