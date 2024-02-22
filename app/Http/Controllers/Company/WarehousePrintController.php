<?php

namespace App\Http\Controllers\Company;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Category;
use App\Models\RoleTranslations;
use App\Models\Uploads;
use App\Models\Warehouse;
use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\WarehouseTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehousePrintController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Warehouse::where('type', Constants::PRINT_TYPE)->orderBy('created_at', 'desc')->get();
        return view('company.print.index', ['products'=> $products]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Warehouse::where('type', Constants::PRINT_TYPE)->find($id);
        return view('company.print.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id)
    {
        $warehouse = Warehouse::where('type', Constants::PRINT_TYPE)->find($id);
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
        return view('company.print.edit', ['warehouse'=> $warehouse, 'sizes'=> $sizes, 'current_category'=> $current_category, 'colors'=> $colors, 'product'=> $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $model = Warehouse::where('type', Constants::PRINT_TYPE)->find($id);
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
        $model->save();
        return redirect()->route('warehouse.category.warehouse', $request->product_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Warehouse::where('type', Constants::PRINT_TYPE)->find($id);

        if (!$model->image_front) {
            $model->image_front = 'no';
        }
        $model_image_front = storage_path('app/public/warehouse/'.$model->image_front);
        if (!$model->image_back) {
            $model->image_back = 'no';
        }
        $model_image_back = storage_path('app/public/warehouse/'.$model->image_back);
        if(file_exists($model_image_front)){
            unlink($model_image_front);
        }
        if(file_exists($model_image_back)){
            unlink($model_image_back);
        }
        $uploads=Uploads::where('relation_type', Constants::WAREHOUSE)->where('relation_id', $model->id)->get();
        foreach ($uploads as $upload){
            if (!$upload->image) {
                $upload->image = 'no';
            }
            $order_detail_upload = storage_path('app/public/print/'.$upload->image);
            if(file_exists($order_detail_upload)){
                unlink($order_detail_upload);
            }
            $upload->delete();
        }
        foreach (Language::all() as $language) {
            $warehouse_translations = WarehouseTranslations::where(['lang' => $language->code, 'warehouse_id' => $model->id])->get();
            foreach ($warehouse_translations as $warehouse_translation){
                $warehouse_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('print.category.warehouse', $model->product_id)->with('status', translate('Successfully deleted'));
    }

    public function category()
    {
        $category = Category::where('step', 0)->get();
        return view('company.print.category', ['categories'=>$category]);
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
        return view('company.print.products', ['products'=>$products]);
    }

    public function warehouse($id){
        $warehouse = Warehouse::where(['product_id'=>$id, 'type'=>Constants::PRINT_TYPE])->get();
        $product = Products::select( 'id', 'name')->find($id);
        return view('company.print.warehouse', ['warehouse'=>$warehouse, 'product'=>$product]);
    }

    public function createWarehouse($id){
        $colors = Color::all();
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        $warehouse = Warehouse::where(['product_id'=>$id, 'type'=>Constants::PRINT_TYPE])->get();
        return view('company.print.create_warehouse', ['warehouse'=>$warehouse, 'product'=>$product, 'colors'=>$colors, 'current_category'=>$current_category]);
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
        $warehouses_ = Warehouse::where(['product_id'=>$request->product_id, 'type'=>Constants::PRINT_TYPE, 'company_id'=>$user->company_id])->get();
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
        $warehouse = Warehouse::where('type', Constants::PRINT_TYPE)->find($request->id);
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
