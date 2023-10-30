<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::orderBy('created_at', 'desc')->get();
        return view('admin.products.index', ['products'=> $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subcategories = Category::where('parent_id', 1)->get();
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        $firstcategory = Category::where('parent_id', 0)->orderBy('id', 'asc')->first();
        $colors = Color::all();
        return view('admin.products.create', ['subcategories'=> $subcategories, 'colors'=> $colors, 'categories'=> $categories, 'firstcategory'=> $firstcategory]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Products();
        $model->name = $request->name;
        if(isset($request->subsubcategory_id)){
            $model->category_id = $request->subsubcategory_id;
        }elseif($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->status = $request->status;
        $images = $request->file('images');
        if(isset($request->images)){
            foreach ($images as $image){
                $letters_new = range('a', 'z');
                $random_array_new = [$letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)]];
                $random_new = implode("", $random_array_new);
                $image_name = $random_new . '' . date('Y-m-dh-i-s') . '.' . $image->extension();
                $image->storeAs('public/products/'.$image_name);
                $array_images[] = $image_name;
            }
            $model->images = json_encode($array_images);
        }
        $model->save();
        return redirect()->route('product.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Products::find($id);
        $colors_array = json_decode($model->colors_id);
        $colors = Color::select('name', 'code')->whereIn('id', $colors_array??[])->get();
        return view('admin.products.show', ['model'=>$model, 'colors'=>$colors]);
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
        return view('admin.products.edit', ['product'=> $product, 'categories'=> $categories, 'colors'=> $colors, 'current_category'=> $current_category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Products::find($id);
        $model->name = $request->name;
        $model->subcategory_id = $request->subcategory_id;
        $model->sum = $request->sum;
        $model->company = $request->company;
        $images = $request->file('images');
        if(isset($request->images)){
            if(isset($model->images)){
                $images_ = json_decode($model->images);
                foreach ($images_ as $image_){
                    $avatar_main = storage_path('app/public/products/'.$image_);
                    if(file_exists($avatar_main)){
                        unlink($avatar_main);
                    }
                }
            }
            foreach ($images as $image){
                $letters_new = range('a', 'z');
                $random_array_new = [$letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)]];
                $random_new = implode("", $random_array_new);
                $image_name = $random_new . '' . date('Y-m-dh-i-s') . '.' . $image->extension();
                $image->storeAs('public/products/'.$image_name);
                $array_images[] = $image_name;
            }
            $model->images = json_encode($array_images);
        }
        $model->save();
        return redirect()->route('product.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Products::find($id);
        if(isset($model->images)){
            $images = json_decode($model->images);
            foreach ($images as $image){
                $avatar_main = storage_path('app/public/products/'.$image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
        }
        $model->delete();
        return redirect()->route('product.index')->with('status', __('Successfully deleted'));
    }

    public function getSubcategory($id){
        $subcategories = Category::where('parent_id', $id)->get();
        $respone = [
            'status'=>true,
            'data'=>$subcategories
        ];
        return response()->json($respone, 200);
    }

    public function getSizes($id){
        $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $id)->get();
        $respone = [
            'status'=>true,
            'data'=>$sizes
        ];
        return response()->json($respone, 200);
    }
}
