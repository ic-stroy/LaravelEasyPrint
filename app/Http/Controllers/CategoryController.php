<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslations;
use App\Models\ColorTranslations;
use App\Models\Language;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::where('step', 0)->orderBy('created_at', 'desc')->get();
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
        $model->step = 0;
        $model->save();
        foreach (Language::all() as $language) {
            $category_translations = CategoryTranslations::firstOrNew(['lang' => $language->code, 'category_id' => $model->id]);
            $category_translations->lang = $language->code;
            $category_translations->name = $model->name;
            $category_translations->category_id = $model->id;
            $category_translations->save();
        }
        return redirect()->route('category.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::where('step', 0)->find($id);
        return view('admin.category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::where('step', 0)->find($id);
        return view('admin.category.edit', ['category'=> $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 0)->find($id);
        if($request->name != $model->name){
            foreach (Language::all() as $language) {
                $category_translations = CategoryTranslations::firstOrNew(['lang' => $language->code, 'category_id' => $model->id]);
                $category_translations->lang = $language->code;
                $category_translations->name = $request->name;
                $category_translations->category_id = $model->id;
                $category_translations->save();
            }
        }
        $model->name = $request->name;
        $model->step = 0;
        $model->save();
        return redirect()->route('category.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('step', 0)->find($id);
        foreach (Language::all() as $language) {
            $categories_translations = CategoryTranslations::where(['lang' => $language->code, 'category_id' => $model->id])->get();
            foreach ($categories_translations as $category_translation){
                $category_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('category.index')->with('status', translate('Successfully deleted'));
    }
}
