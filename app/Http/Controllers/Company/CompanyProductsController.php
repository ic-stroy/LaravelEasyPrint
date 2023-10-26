<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Category;

class CompanyProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return 'vsdas';
        $products = Products::orderBy('created_at', 'desc')->get();
        return view('company.products.index', ['products'=> $products]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subcategories = Category::where('parent_id', '!=', 0)->get();
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        $firstcategory = Category::where('parent_id', 0)->orderBy('id', 'asc')->first();
        $colors = Color::all();
        return view('company.products.create', ['subcategories'=> $subcategories, 'colors'=> $colors, 'categories'=> $categories, 'firstcategory'=> $firstcategory]);
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
        $model = Products::find($id);
        $colors_array = json_decode($model->colors_id);
        $colors = Color::select('name', 'code')->whereIn('id', $colors_array??[])->get();
        return view('company.products.show', ['model'=>$model, 'colors'=>$colors]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::find($id);
        $current_category = isset($product->subCategory->category)?$product->subCategory->category:'no';
        $categories = Category::orderBy('id', 'asc')->get();
        $colors = Color::all();
        return view('company.products.edit', ['product'=> $product, 'categories'=> $categories, 'colors'=> $colors, 'current_category'=> $current_category]);
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
