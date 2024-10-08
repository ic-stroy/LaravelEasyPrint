<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Discount;
use App\Models\Language;
use App\Models\Products;
use App\Models\ProductTranslations;
use App\Models\Sizes;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductStoreRequest;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('step', 0)->get();
        $all_products = [];
        foreach($categories as $category){
            $categories_id = Category::where('parent_id', $category->id)->pluck('id')->all();
            array_push($categories_id, $category->id);
            $products = Products::orderBy('created_at', 'desc')->whereIn('category_id', $categories_id)->get();
            $all_products[$category->id] = $products;
        }
        return view('admin.products.index', ['all_products'=> $all_products, 'categories'=> $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('admin.products.create', ['categories'=> $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Products();
        if(in_array($request->name, ['Футболка', 'Свитшот', 'Худи'])){
            return redirect()->back()->with('error', translate('You cannot use this name for product. Because they are already used'));
        }
        $model->name = $request->name;
        if($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->status = $request->status;
        $model->price = $request->price;
        $model->description = $request->description;
        $model->manufacturer_country = $request->manufacturer_country;
        $model->material_composition = $request->material_composition;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'store');
        $model->save();
        if(!empty($model->categoryDiscount)){
            if(empty($model->discount)){
                $discount = new Discount();
                $discount->percent = $model->categoryDiscount->percent;
                $discount->start_date = $model->categoryDiscount->start_date;
                $discount->end_date = $model->categoryDiscount->end_date;
                $discount->category_id = $model->category_id;
                $discount->type = (int)Constants::DISCOUNT_PRODUCT_TYPE;
                $discount->product_id = $model->id;
                $discount->discount_number = $model->categoryDiscount->discount_number;
                $discount->save();
            }
        }
        foreach (Language::all() as $language) {
            $product_translations = ProductTranslations::firstOrNew(['lang' => $language->code, 'product_id' => $model->id]);
            $product_translations->lang = $language->code;
            $product_translations->name = $model->name;
            $product_translations->product_id = $model->id;
            $product_translations->save();
        }
        return redirect()->route('product.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Products::find($id);
        $category_ = '';
        $category_array = [];
        $sub_category_ = '';
        if($model){
            if(!empty($model->category)){
                $category_ = $model->category->name;
                $category_array = [$category_];
            }elseif(!empty($model->subCategory)){
                $category_ = !empty($model->subCategory->category)?$model->subCategory->category->name:'';
                $sub_category_ = $model->subCategory->name;
                if($category_ != ''){
                    $category_array = [$category_, $sub_category_];
                }else{
                    $category_array = [$sub_category_];
                }
            }
        }
        return view('admin.products.show', [
            'model'=>$model,
            'category_array'=> $category_array
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::find($id);
       if($product->subCategory){
            $category_product = $product->subCategory;
            $is_category = 2;
           $current_category = $category_product->category?$category_product->category:'no';
           $current_sub_category_id = $category_product->id?$category_product->id:'no';
           $current_sub_sub_category_id = 'no';
        }elseif($product->category){
            $category_product = $product->category;
            $is_category = 1;
           $current_category = $category_product;
           $current_sub_category_id = 'no';
           $current_sub_sub_category_id = 'no';
        }elseif($product->subSubCategory) {
           $category_product = $product->subSubCategory;
           $is_category = 3;
           if($category_product->subSubCategory->subCategory){
               if($category_product->subSubCategory->subCategory->category){
                   $current_category = $category_product->subSubCategory->subCategory->category;
               }else{
                   $current_category = 'no';
               }
               $current_sub_category_id = $category_product->subSubCategory->subCategory ? $category_product->subSubCategory->subCategory : 'no';
           }else{
               $current_category = 'no';
               $current_sub_category_id = 'no';
           }
           $current_sub_sub_category_id = $category_product ? $category_product->id : 'no';
       }else{
            $category_product = 'no';
            $is_category = 0;
           $current_category = 'no';
           $current_sub_category_id = 'no';
           $current_sub_sub_category_id = 'no';
       }
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('admin.products.edit', [
            'product'=> $product, 'categories'=> $categories,
            'category_product'=> $category_product, 'is_category'=>$is_category,
            'current_category' => $current_category,
            'current_sub_category_id' => $current_sub_category_id,
            'current_sub_sub_category_id' => $current_sub_sub_category_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Products::find($id);
        if($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->status = $request->status;
        $model->price = $request->price;
        $model->description = $request->description;
        $model->manufacturer_country = $request->manufacturer_country;
        $model->material_composition = $request->material_composition;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'update');
        if($request->name != $model->name){
            foreach (Language::all() as $language) {
                $product_translations = ProductTranslations::firstOrNew(['lang' => $language->code, 'product_id' => $model->id]);
                $product_translations->lang = $language->code;
                $product_translations->name = $request->name;
                $product_translations->product_id = $model->id;
                $product_translations->save();
            }
        }
        $model->name = $request->name;
        $model->save();
        if(!empty($model->categoryDiscount)){
            if(empty($model->discount)){
                $discount = new Discount();
                $discount->percent = $model->categoryDiscount->percent;
                $discount->start_date = $model->categoryDiscount->start_date;
                $discount->end_date = $model->categoryDiscount->end_date;
                $discount->category_id = $model->category_id;
                $discount->type = (int)Constants::DISCOUNT_PRODUCT_TYPE;
                $discount->product_id = $model->id;
                $discount->discount_number = $model->categoryDiscount->discount_number;
                $discount->save();
            }
        }

        return redirect()->route('product.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Products::find($id);
        if(!empty($model->warehouse)){
            if(!$model->warehouse->isEmpty()){
                return redirect()->back()->with('error', translate('You cannot delete this product because here is some products in warehouse'));
            }
        }
        if($model->images){
            $images = json_decode($model->images);
            foreach ($images as $image){
                $avatar_main = storage_path('app/public/products/'.$image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
        }
        foreach (Language::all() as $language) {
            $product_translations = ProductTranslations::where(['lang' => $language->code, 'product_id' => $model->id])->get();
            foreach ($product_translations as $product_translation){
                $product_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('product.index')->with('status', translate('Successfully deleted'));
    }

    public function getSizes($id){
        $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $id)->get();
        $respone = [
            'status'=>true,
            'data'=>$sizes
        ];
        return response()->json($respone, 200);
    }

//    public function category()
//    {
//        $category = Category::where('step', 0)->get();
//        return view('admin.products.category', ['categories'=>$category]);
//    }

    public function product($id)
    {
        $category = Category::find($id);
        $subcategories = $category->subcategory;
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->id;
        }
        $category_ids[] = $category->id;
        $products = Products::whereIn('category_id', $category_ids)->get();
        return view('admin.products.product', ['products'=>$products]);
    }

    public function allproduct_destroy()
    {
        $models = Products::all();
        foreach($models as $model){
            $images = json_decode($model->images);
            foreach ($images as $image){
                $avatar_main = storage_path('app/public/products/'.$image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
            foreach($model->order_detail as $order_detail){
                if($order_detail->order){
                    $order_detail->order->delete();
                }
                $order_detail->delete();
            }
            foreach($model->warehouse as $warehouse){
                $warehouse->delete();
            }
            $model->delete();
        }

        return redirect()->route('product.index')->with('status', translate('Successfully deleted'));
    }

    public function payment(){
        $payment = PaymentStatus::first();
        return view('admin.payment.index', ['payment'=>$payment]);
    }

    public function paymentGetStatus(){
        $payment = PaymentStatus::first();
        $status = '';
        if($payment){
            if($payment->status == 0){
                $status = 'Not active';
            }elseif($payment->status == 1){
                $status = 'Active';
            }
        }
        return $this->success('Success', 200, [$status]);
    }

    public function paymentStatus(Request $request){
        $payment = PaymentStatus::find($request->id);
        if($payment){
            if($request->checked == 'true'){
                $payment->status = Constants::ACTIVE;
                $message = translate('Payment activated');
            }elseif($request->checked == 'false'){
                $payment->status = Constants::NOT_ACTIVE;
                $message = translate('Payment disactivated');
            }
            $payment->save();
        }
        return $this->success($message, 200);
    }

    public function imageSave($product, $images, $text){
        if($text == 'update'){
            if($product->images && !is_array($product->images)){
                $product_images = json_decode($product->images);
            }else{
                $product_images = [];
            }
        }else{
            $product_images = [];
        }
        if(isset($images)){
            $ProductImage = [];
            foreach($images as $image){
                $random = $this->setRandom();
                $product_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
                $image->storeAs('public/products/', $product_image_name);
                $ProductImage[] = $product_image_name;
            }
            $all_product_images = array_values(array_merge($product_images, $ProductImage));
        }
        $productImages = json_encode($all_product_images??$product_images);
        return $productImages;
    }

    // Backend api json

    public function getWarehousesByProduct(Request $request){
        $warehouses_ = Warehouse::where('product_id', $request->product_id)->get();
        $warehouses = [];
        foreach ($warehouses_ as $warehouse_){
            $warehouses[] = [
                'id'=>$warehouse_->id,
                'name'=>$warehouse_->name?$warehouse_->name:$warehouse_->product->name,
                'color'=>$warehouse_->color?$warehouse_->color->name:'',
                'size'=>$warehouse_->size?$warehouse_->size->name:''
            ];
        }
        return response()->json([
            'data'=>$warehouses,
            'status'=>true,
            'message'=>'Success'
        ]);
    }
}
