<?php

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Models\Coupon;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $coupons = Coupon::where('company_id', $user->company_id)->get();
        return view('company.coupons.index', ['coupons'=> $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $coupon = new Coupon();
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        }elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        $coupon->min_price = $request->min_price;
        $coupon->company_id = $user->company_id;
        $coupon->type = $request->type;
        $coupon->status = $request->status;
        $coupon->order_count = $request->order_count;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();
        return redirect()->route('company_coupons.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Coupon::find($id);
        return view('company.coupons.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::find($id);
        $companies = Company::all();
        return view('company.coupons.edit', ['coupon'=> $coupon, 'companies'=>$companies]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $coupon = Coupon::find($id);
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        } elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        if(isset($request->min_price)){
            $coupon->min_price = $request->min_price;
        }
        $coupon->company_id = $user->company_id;
        $coupon->order_count = $request->order_count;
        $coupon->type = $request->type;
        $coupon->status = $request->status;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();
        return redirect()->route('company_coupons.index')->with('status', translate('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Coupon::find($id);
        $model->delete();
        return redirect()->route('company_coupons.index')->with('status', translate('Successfully created'));
    }
}
