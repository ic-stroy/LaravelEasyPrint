<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Coupon;


use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::where('company_id', NULL)->get();
        return view('admin.coupons.index', ['coupons'=> $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('admin.coupons.create', ['companies'=> $companies]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coupon = new Coupon();
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        } elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }

        $coupon->save();
        return redirect()->route('coupons.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Coupon::find($id);
        return view('admin.coupons.show', ['model'=>$model, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::find($id);
        $companies = Company::all();
        return view('admin.coupons.edit', ['coupon'=> $coupon, 'companies'=>$companies]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::find($id);
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        } elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        $coupon->save();
        return redirect()->route('coupons.index')->with('status', translate('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Coupon::find($id);
        $model->delete();
        return redirect()->route('coupons.index')->with('status', translate('Successfully created'));
    }
}
