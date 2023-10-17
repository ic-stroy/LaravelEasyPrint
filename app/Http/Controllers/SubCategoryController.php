<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $SubCategory = Category::where('parent_id', '!=', 0)->orderBy('created_at', 'desc')->get();
        return view('admin.sub-category.index', ['subcategories'=> $SubCategory]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id',  0)->get();
        return view('admin.sub-category.create', ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->save();
        return redirect()->route('subcategory.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::where('parent_id', '!=', 0)->find($id);
        return view('admin.sub-category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $SubCategory = Category::where('parent_id', '!=', 0)->find($id);
        $categories = Category::where('parent_id', 0)->get();
        return view('admin.sub-category.edit', ['subcategory'=> $SubCategory, 'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('parent_id', '!=', 0)->find($id);
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->save();
        return redirect()->route('subcategory.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('parent_id', '!=', 0)->find($id);
        $model->delete();
        return redirect()->route('subcategory.index')->with('status', __('Successfully deleted'));
    }
}
