<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::where('parent_id', 0)->orderBy('created_at', 'desc')->get();
        return view('admin.category.index', ['categories'=> $category]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $model->name = $request->name;
        $model->parent_id = 0;
        $model->save();
        return redirect()->route('category.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::find($id);
        return view('admin.category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('admin.category.edit', ['category'=> $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::find($id);
        $model->name = $request->name;
        $model->parent_id = 0;
        $model->save();
        return redirect()->route('category.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::find($id);
        $model->delete();
        return redirect()->route('category.index')->with('status', __('Successfully deleted'));
    }
}
