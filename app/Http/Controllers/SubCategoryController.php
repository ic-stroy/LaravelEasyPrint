<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslations;
use App\Models\Language;
use App\Models\Products;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public $types_sex = [
        'No sex type', 'Unisex', 'Men', 'Women', 'Children'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = Category::where('step', 0)->get();
        $all_categories = [];
        foreach($categories as $category){
            $sub_categories = $category->subcategory;
            if(!empty($sub_categories)){
                $all_categories[$category->id] = $sub_categories;
            }else{
                $all_categories[$category->id] = [];

            }
        }
        return view('admin.sub-category.index', ['all_categories'=> $all_categories, 'categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('step', 0)->get();
        return view('admin.sub-category.create', ['categories'=>$categories, 'types_sex'=>$this->types_sex]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->step = 1;
        $model->save();
        foreach (Language::all() as $language) {
            $category_translations = CategoryTranslations::firstOrNew(['lang' => $language->code, 'category_id' => $model->id]);
            $category_translations->lang = $language->code;
            $category_translations->name = $model->name;
            $category_translations->category_id = $model->id;
            $category_translations->save();
        }
        return redirect()->route('subcategory.index', $request->category_id)->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::where('step', 1)->find($id);
        return view('admin.sub-category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $SubCategory = Category::where('step', 1)->find($id);
        $categories = Category::where('step', 0)->get();
        return view('admin.sub-category.edit', ['subcategory'=>$SubCategory, 'types_sex'=>$this->types_sex, 'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 1)->find($id);
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
        $model->parent_id = $request->category_id;
        $model->step = 1;
        $model->save();
        return redirect()->route('subcategory.index', $request->category_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('step', 1)->find($id);
        $model->delete();
        return redirect()->route('subcategory.index', $model->category->id)->with('status', translate('Successfully deleted'));
    }

    public function category()
    {
        $category = Category::where('step', 0)->get();
        return view('admin.sub-category.category', ['categories'=>$category]);
    }

    public function subcategory($id)
    {
        $SubCategory = Category::where('parent_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin.sub-category.subcategory', ['subcategories'=>$SubCategory]);
    }


    /*
     * Json api
     */
    public function getSubcategory($id)
    {
        $model = Category::where('parent_id', $id)->get();
        if($model && count($model)>0){
            return response()->json([
                'status'=>true,
                'data'=>$model
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'data'=>[]
            ]);
        }

    }
}
