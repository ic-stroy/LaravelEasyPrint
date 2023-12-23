<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Category;
use App\Models\RoleTranslations;
use App\Models\Warehouse;
use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\WarehouseTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Warehouse::orderBy('created_at', 'desc')->get();
        return view('company.warehouse.index', ['products'=> $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Products::all();
        $colors = Color::all();
        return view('company.warehouse.create', ['colors'=> $colors, 'products'=> $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $IsExistWarehouse = Warehouse::where(['size_id', 'color_id', 'product_id'])->first();
        if($IsExistWarehouse){
            $model = $IsExistWarehouse;
            if($request->quantity){
                $model->quantity = $model->quantity??0 + $request->quantity;
            }
        }else{
            $model = new Warehouse();
            $model->quantity = $request->quantity;
        }
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->product_id = $request->product_id;

        $model->name = $request->name;
        $model->company_id = $user->company_id;
        $model->price = $request->price;
        $model->quantity = $request->quantity;
        $model->description = $request->description;
        $model->manufacturer_country = $request->manufacturer_country;
        $model->material_composition = $request->material_composition;
        $model->material_id = $request->material_id;
        $images = $request->file('images');
        if(isset($request->images)){
            foreach ($images as $image){
                $letters_new = range('a', 'z');
                $random_array_new = [$letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)], $letters_new[rand(0,25)]];
                $random_new = implode("", $random_array_new);
                $image_name = $random_new . '' . date('Y-m-dh-i-s') . '.' . $image->extension();
                $image->storeAs('public/warehouses/'.$image_name);
                $array_images[] = $image_name;
            }
            $model->images = json_encode($array_images);
        }
        $model->save();
        foreach (Language::all() as $language) {
            $warehouse_translations = WarehouseTranslations::where(['lang' => $language->code, 'warehouse_id' => $model->id])->firstOrNew();
            $warehouse_translations->lang = $language->code;
            $warehouse_translations->name = $model->name;
            $warehouse_translations->warehouse_id = $model->id;
            $warehouse_translations->save();
        }
        return redirect()->route('warehouse.category.warehouse', $request->product_id)->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Warehouse::find($id);
        $colors_array = json_decode($model->colors_id);
        $colors = Color::select('name', 'code')->whereIn('id', $colors_array??[])->get();
        return view('company.warehouse.show', ['model'=>$model, 'colors'=>$colors]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warehouse = Warehouse::find($id);
        if(isset($warehouse->product)){
            $product = $warehouse->product;
            $current_category = $this->getProductCategory($warehouse->product);
            $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $current_category->id)->get();
        }else{
            $current_category = 'no';
            $sizes = 'no';
            $product = 'no';
        }
        $colors = Color::all();
        return view('company.warehouse.edit', ['warehouse'=> $warehouse, 'sizes'=> $sizes, 'current_category'=> $current_category, 'colors'=> $colors, 'product'=> $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $model = Warehouse::find($id);
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->product_id = $request->product_id;
        $model->name = $request->name;
        $model->company_id = $user->company_id;
        $model->price = $request->price;
        $model->quantity = $request->quantity;
        $model->description = $request->description;
        $model->manufacturer_country = $request->manufacturer_country;
        $model->material_composition = $request->material_composition;
        $model->material_id = $request->material_id;
        $images = $request->file('images');
        if(isset($request->images)){
            if(isset($model->images)){
                $images_ = json_decode($model->images);
                foreach ($images_ as $image_){
                    $avatar_main = storage_path('app/warehouses/products/'.$image_);
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
                $image->storeAs('public/warehouses/'.$image_name);
                $array_images[] = $image_name;
            }
            $model->images = json_encode($array_images);
        }
        $model->save();
        return redirect()->route('warehouse.category.warehouse', $request->product_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Warehouse::find($id);
        foreach (Language::all() as $language) {
            $warehouse_translations = WarehouseTranslations::where(['lang' => $language->code, 'warehouse_id' => $model->id])->get();
            foreach ($warehouse_translations as $warehouse_translation){
                $warehouse_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('warehouse.category.warehouse', $model->product_id)->with('status', translate('Successfully deleted'));
    }

    public function category()
    {
        $category = Category::where('step', 0)->get();
        return view('company.warehouse.category', ['categories'=>$category]);
    }

    public function product($id)
    {
        $category = Category::find($id);
        $subcategories = $category->subcategory;
        $category_ids = [];
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->id;
        }
        $subsubcategories = Category::WhereIn('parent_id', $category_ids)->get();
        foreach ($subsubcategories as $subsubcategory){
            $category_ids[] = $subsubcategory->id;
        }
        $category_ids[] = $category->id;
        $products = Products::whereIn('category_id', $category_ids)->get();
        return view('company.warehouse.products', ['products'=>$products]);
    }
    public function warehouse($id){
        $warehouse = Warehouse::where('product_id', $id)->get();
        $product = Products::select('id', 'name')->find($id);
        return view('company.warehouse.warehouse', ['warehouse'=>$warehouse, 'product'=>$product]);
    }
    public function createWarehouse($id){
        $colors = Color::all();
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        $warehouse = Warehouse::where('product_id', $id)->get();
        return view('company.warehouse.create_warehouse', ['warehouse'=>$warehouse, 'product'=>$product, 'colors'=>$colors, 'current_category'=>$current_category]);
    }

    public function getProductCategory($product){
        if(isset($product->subSubCategory->id)){
            $category_product = $product->subSubCategory;
            $is_category = 3;
        }elseif(isset($product->subCategory->id)){
            $category_product = $product->subCategory;
            $is_category = 2;
        }elseif(isset($product->category->id)){
            $category_product = $product->category;
            $is_category = 1;
        }else{
            $category_product = 'no';
            $is_category = 0;
        }
        switch ($is_category){
            case 1:
                $current_category = $category_product;
                break;
            case 2:
                $current_category = isset($category_product->category)?$category_product->category:'no';
                break;
            case 3:
                $current_category = isset($category_product->sub_category->category)?$category_product->sub_category->category:'no';
                break;
            default:
                $current_category = 'no';
        }
        return $current_category;
    }

    // backend json api

    public function getWarehousesByProduct(Request $request){
        $user = Auth::user();
        $warehouses_ = Warehouse::where('product_id', $request->product_id)->where('company_id', $user->company_id)->get();
        $warehouses = [];
        foreach ($warehouses_ as $warehouse_){
            $warehouses[] = [
                'id'=>$warehouse_->id,
                'name'=>isset($warehouse_->name)?$warehouse_->name:$warehouse_->product->name,
                'color'=>isset($warehouse_->color->name)?$warehouse_->color->name:'',
                'size'=>isset($warehouse_->size->name)?$warehouse_->size->name:''
            ];
        }
        return response()->json([
            'data'=>$warehouses,
            'status'=>true,
            'message'=>'Success'
        ]);
    }
}
