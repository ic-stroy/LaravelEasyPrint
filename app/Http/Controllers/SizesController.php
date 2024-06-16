<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use Illuminate\Http\Request;

class SizesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('step', 0)->get();
        $all_sizes = [];
        foreach($categories as $category){
            $categories_id = Category::where('parent_id', $category->id)->pluck('id')->all();
            array_push($categories_id, $category->id);
            $products = Sizes::orderBy('created_at', 'desc')->whereIn('category_id', $categories_id)->get();
            $all_sizes[$category->id] = $products;
        }
        return view('admin.sizes.index', ['all_sizes'=> $all_sizes, 'categories'=> $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->where('parent_id', 0)->get();
        return view('admin.sizes.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Sizes();
        $model->name = $request->name;
        $model->category_id = $request->category_id;
        $model->status = $request->status;
        $model->save();
        return redirect()->route('size.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Sizes::find($id);
        return view('admin.sizes.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $size = Sizes::find($id);
        $categories = Category::select('id', 'name')->where('parent_id', 0)->get();
        return view('admin.sizes.edit', ['size'=> $size, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Sizes::find($id);
        $model->name = $request->name;
        $model->category_id = $request->category_id;
        $model->status = $request->status;
        $model->save();
        return redirect()->route('size.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Sizes::find($id);
        if($model->warehouse){
            return redirect()->back()->with('error', translate('You cannot delete this size because here is product associated with this size.'));
        }
        $model->delete();
        return redirect()->route('size.index')->with('status', translate('Successfully deleted'));
    }
}
