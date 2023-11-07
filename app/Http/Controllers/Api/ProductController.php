<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Products;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return "came";
        // $products=Products::get();
        // dd($products);
        $products = DB::table('products')
        ->select('id','name','price','images')
        ->get();
        // dd($products);

        // $warehouse_products=DB::table('warehouses')
        // // ->select('product_id','id','name','price','images')
        // ->select('product_id', DB::raw('MAX(id)'), DB::raw('MAX(name)'), DB::raw('MAX(price)'), DB::raw('MAX(images)'))
        // // ->select('product_id')
        // ->groupBy('product_id')
        // ->get();
        // dd($warehouse_products);
        // $warehouse_products=Warehouse::groupBy('product_id')
        // ->get();
        // dd($warehouse_products);




    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
