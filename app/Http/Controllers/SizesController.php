<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Sizes;
use Illuminate\Http\Request;

class SizesController extends Controller
{
    public $all_sizes = ['S', 'M', 'L', 'X', 'XL', 'XXL', 'XXXL', 'XXXXL', '6-7 years', '8-10 years', '11-13 years'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Sizes::orderBy('created_at', 'desc')->get();
        return view('admin.sizes.index', ['sizes'=> $sizes]);
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
        foreach ($this->all_sizes as $all_size){
            $model = new Sizes();
            $model->name = $all_size;
            $model->category_id = $request->category_id;
            $model->status = $request->status;
            $model->save();
        }
//        $model = new Sizes();
//        $model->name = $request->name;
//        $model->category_id = $request->category_id;
//        $model->status = $request->status;
//        $model->save();
        return redirect()->route('size.index')->with('status', __('Successfully created'));
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
        return redirect()->route('size.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Sizes::find($id);
        $model->delete();
        return redirect()->route('size.index')->with('status', __('Successfully deleted'));
    }
}
