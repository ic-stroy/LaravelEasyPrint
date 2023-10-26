<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $SubSubCategory = Category::where('step', 2)->orderBy('created_at', 'desc')->get();
        return view('admin.sub-sub-category.index', ['subsubcategories'=> $SubSubCategory]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subcategories = Category::select('parent_id')->where('step', 1)->groupBy('parent_id')->distinct()->get();
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->parent_id;
        }
        $categories = Category::whereIn('id', $category_ids)->get();
        return view('admin.sub-sub-category.create', ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $model->name = $request->name;
        $model->parent_id = $request->subcategory_id;
        $model->step = 2;
        $model->save();
        return redirect()->route('subsubcategory.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::where('step', 2)->find($id);
        return view('admin.sub-sub-category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $SubSubCategory = Category::where('step', 2)->find($id);
        $subcategories = Category::where('step', 1)->get();
        return view('admin.sub-sub-category.edit', ['subsubcategory'=>$SubSubCategory, 'subcategories'=>$subcategories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 2)->find($id);
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->step = 2;
        $model->save();
        return redirect()->route('subsubcategory.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('step', 2)->find($id);
        $model->delete();
        return redirect()->route('subsubcategory.index')->with('status', __('Successfully deleted'));
    }
}
