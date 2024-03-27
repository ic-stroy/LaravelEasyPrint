<?php

namespace App\Http\Controllers\Company;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Discount;
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
        $categories = Category::where('step', 0)->get();
        $all_products = [];
        foreach($categories as $category) {
            $categories_id = Category::where('parent_id', $category->id)->pluck('id')->all();
            array_push($categories_id, $category->id);
            $products = Products::orderBy('created_at', 'desc')->whereIn('category_id', $categories_id)->get();
//            $products = Warehouse::orderBy('created_at', 'desc')->where('type', Constants::WAREHOUSE_TYPE)->get();
            $all_products[$category->id] = $products;
        }
        return view('company.warehouse.index', ['products'=> $products, 'categories'=> $categories, 'all_products'=> $all_products]);
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
        $IsExistWarehouse = Warehouse::where(['size_id'=>$request->size_id, 'color_id'=>$request->color_id, 'product_id'=>$request->product_id, 'type'=>Constants::WAREHOUSE_TYPE])->first();
        if($IsExistWarehouse){
            $model = $IsExistWarehouse;
            if($request->quantity){
                $model->quantity = (int)$IsExistWarehouse->quantity + (int)$request->quantity;
            }
        }else{
            $model = new Warehouse();
            $model->quantity = $request->quantity;
        }
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->product_id = $request->product_id;
        if($request->name){
            $model->name = $request->name;
        }
        $model->company_id = $user->company_id;
        if($request->price){
            $model->price = $request->price;
        }
        if($request->description){
            $model->description = $request->description;
        }
        if($request->manufacturer_country){
            $model->manufacturer_country = $request->manufacturer_country;
        }
        if($request->material_composition){
            $model->material_composition = $request->material_composition;
        }
        $model->material_id = $request->material_id;
        $model->type = Constants::WAREHOUSE_TYPE;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'store');
        $model->save();
        $categoryDiscount = Discount::where(['product_id' => $model->product_id, 'type'=>2])->first();
        if($categoryDiscount){
            $discount = new Discount();
            $discount->percent = $model->categoryDiscount->percent;
            $discount->start_date = $model->categoryDiscount->start_date;
            $discount->end_date = $model->categoryDiscount->end_date;
            $discount = $model->category_id;
            $discount->type = Constants::DISCOUNT_WAREHOUSE_TYPE;
            $discount->product_id = $model->product_id;
            $discount->warehouse_id = $model->id;
            $discount->discount_number = $model->categoryDiscount->discount_number;
            $discount->save();
        }

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
        $model = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->find($id);
        return view('company.warehouse.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id)
    {
        $warehouse = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->find($id);
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
        $model = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->find($id);
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->product_id = $request->product_id;
        $model->name = $request->name;
        $model->company_id = $user->company_id;
        $model->quantity = $request->quantity;
        $model->price = $request->price;
        $model->description = $request->description;
        $model->manufacturer_country = $request->manufacturer_country;
        $model->material_composition = $request->material_composition;
        $model->material_id = $request->material_id;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'update');
        $model->save();
        return redirect()->route('warehouse.category.warehouse', $request->product_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->find($id);
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
        return view('warehouse.index', ['products'=>$products]);
    }

    public function warehouse($id){
        $warehouse = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->where('product_id', $id)->get();
        $product = Products::select('id', 'name')->find($id);
        return view('company.warehouse.warehouse', ['warehouse'=>$warehouse, 'product'=>$product]);
    }

    public function createWarehouse($id){
        $colors = Color::all();
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        $warehouse = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->where('product_id', $id)->get();
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

    public function imageSave($warehouse, $images, $text){
        if($text == 'update'){
            if(isset($warehouse->images) && !is_array($warehouse->images)){
                $warehouse_images = json_decode($warehouse->images);
            }else{
                $warehouse_images = [];
            }
        }else{
            $warehouse_images = [];
        }
        if(isset($images)){
            $WarehouseImage = [];
            foreach($images as $image){
                $random = $this->setRandom();
                $warehouse_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
                $image->storeAs('public/warehouses/', $warehouse_image_name);
                $WarehouseImage[] = $warehouse_image_name;
            }
            $all_warehouse_images = array_values(array_merge($warehouse_images, $WarehouseImage));
        }
        $warehouseImages = json_encode($all_warehouse_images??$warehouse_images);
        return $warehouseImages;
    }

    // backend json api

    public function getWarehousesByProduct(Request $request){
        $user = Auth::user();
        $warehouses_ = Warehouse::where(['product_id'=>$request->product_id, 'company_id'=>$user->company_id, 'type'=>Constants::WAREHOUSE_TYPE])->get();
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

    public function deleteWarehouseImage(Request $request){
        $warehouse = Warehouse::where('type', Constants::WAREHOUSE_TYPE)->find($request->id);
        if(isset($warehouse->images) && !is_array($warehouse->images)){
            $warehouse_images_base = json_decode($warehouse->images);
        }else{
            $warehouse_images_base = [];
        }
        if(is_array($warehouse_images_base)){
            if(isset($request->warehouse_name)){
                $selected_warehouse_key = array_search($request->warehouse_name, $warehouse_images_base);
                $warehouse_main = storage_path('app/public/warehouses/'.$request->warehouse_name);
                if(file_exists($warehouse_main)){
                    unlink($warehouse_main);
                }
                unset($warehouse_images_base[$selected_warehouse_key]);
            }
            $warehouse->images = json_encode(array_values($warehouse_images_base));
            $warehouse->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>'Success'
        ], 200);
    }
}
