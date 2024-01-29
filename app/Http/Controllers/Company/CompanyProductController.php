<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;



class CompanyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id=auth()->user()->company_id;
        $warehouses = DB::table('warehouses as dt1')
            ->leftJoin('colors as dt2', 'dt2.id', '=', 'dt1.color_id')
            ->leftJoin('products as dt3', 'dt3.id', '=', 'dt1.product_id')
            ->leftJoin('categories as dt4', 'dt4.id', '=', 'dt3.category_id')
            ->leftJoin('sizes as dt5', 'dt5.id', '=', 'dt1.size_id')
            ->where('dt1.company_id', $company_id)
            ->select('dt1.id','dt1.name','dt1.quantity','dt1.price','dt2.name as color_name','dt3.images', 'dt4.name as category_name', 'dt5.name as size_name')
            ->get();

        return view('company.products.index', ['warehouses'=> $warehouses]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        $firstcategory = Category::where('parent_id', 0)->orderBy('id', 'asc')->first();
        $products = Products::orderBy('created_at', 'desc')->get();
        $colors = Color::all();
        $sizes = Sizes::all();

        return view('company.products.create', ['products'=> $products, 'colors'=> $colors, 'categories'=> $categories, 'firstcategory'=> $firstcategory, 'sizes'=> $sizes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id=auth()->user()->company_id;
        $warehouse = Warehouse::create([
            'name'=>$request->name,
            'color_id'=>$request->color_id,
            'product_id'=>$request->product_id,
            'company_id'=>$company_id,
            'size_id'=>$request->size_id,
            'price'=>$request->sum,
            'quantity'=>$request->quantity,
        ]);
        return redirect()->route('company_product.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $warehouse = DB::table('warehouses as dt1')
            ->leftJoin('colors as dt2', 'dt2.id', '=', 'dt1.color_id')
            ->leftJoin('products as dt3', 'dt3.id', '=', 'dt1.product_id')
            ->leftJoin('categories as dt4', 'dt4.id', '=', 'dt3.category_id')
            ->leftJoin('sizes as dt5', 'dt5.id', '=', 'dt1.size_id')
            ->where('dt1.id', $id)
            ->select('dt1.id','dt1.name','dt1.quantity','dt1.price','dt2.name as color_name','dt3.images', 'dt4.name as category_name', 'dt5.name as size_name')
            ->first();

        return view('company.products.show', ['warehouse'=>$warehouse]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warehouse = DB::table('warehouses as dt1')
        ->where('dt1.id', $id)
        ->first();

        $products = Products::orderBy('created_at', 'desc')->get();
        $colors = Color::all();
        $sizes = Sizes::all();


        return view('company.products.edit', ['products'=> $products, 'warehouse'=> $warehouse, 'colors'=> $colors, 'sizes'=> $sizes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , string $id)
    {
        $warehouse=Warehouse::where('id',$id)
        ->update([
            'name'=>$request->name,
            'product_id'=>$request->product_id,
            'color_id'=>$request->color_id,
            'size_id'=>$request->size_id,
            'price'=>$request->sum,
            'quantity'=>$request->quantity,
        ]);
        return redirect()->route('company_product.index')->with('status', translate('Successfully updated'));



    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
