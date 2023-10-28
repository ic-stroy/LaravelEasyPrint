<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;


use Illuminate\Http\Request;

class CompanyCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id=auth()->user()->company_id;


        $coupons = DB::table('coupons as dt1')
            ->leftJoin('warehouses as dt2', 'dt2.company_id', '=', 'dt1.company_id')
            ->leftJoin('categories as dt3', 'dt3.id', '=', 'dt1.category_id')
            ->where('dt1.company_id', $id)
            ->select('dt1.*', 'dt2.*', 'dt3.*')
            ->get();
            // dd($coupons);

            return view('company.coupons.index', ['coupons'=> $coupons]);



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id=auth()->user()->company_id;
        $warehouse_products= DB::table('warehouses')
        ->where('company_id', $id)
        ->latest()
        ->get();
        // dd($warehouse_products);

        $categories = Category::where('step', 2)->orderBy('created_at', 'desc')->get();

        return view('company.coupons.create', ['warehouse_products'=> $warehouse_products, 'categories'=> $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
