<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Language;
use App\Models\Products;
use App\Models\ProductTranslations;
use App\Models\Sizes;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('admin.products.create', ['categories'=> $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Products();
        $model->name = $request->name;
        if($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->status = $request->status;
        $model->price = $request->price;
        $model->description = $request->description;
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
        foreach (Language::all() as $language) {
            $product_translations = ProductTranslations::firstOrNew(['lang' => $language->code, 'product_id' => $model->id]);
            $product_translations->lang = $language->code;
            $product_translations->name = $model->name;
            $product_translations->product_id = $model->id;
            $product_translations->save();
        }
        return redirect()->route('product.category.product', $request->category_id)->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Products::find($id);
        $subcategories = Category::where('parent_id', 1)->get();
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        $firstcategory = Category::where('parent_id', 0)->orderBy('id', 'asc')->first();
        return view('admin.products.show', ['model'=>$model, 'subcategories'=> $subcategories, 'categories'=> $categories, 'firstcategory'=> $firstcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::find($id);
       if(isset($product->subCategory->id)){
            $category_product = $product->subCategory;
            $is_category = 2;
        }elseif(isset($product->category->id)){
            $category_product = $product->category;
            $is_category = 1;
        }else{
            $category_product = 'no';
            $is_category = 0;
        }
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('admin.products.edit', ['product'=> $product, 'categories'=> $categories, 'category_product'=> $category_product, 'is_category'=>$is_category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Products::find($id);
        $model->name = $request->name;
        if($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->status = $request->status;
        $model->price = $request->price;
        $model->description = $request->description;
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
        return redirect()->route('product.category.product', $request->category_id)->with('status', translate('Successfully updated'));
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
        foreach (Language::all() as $language) {
            $product_translations = ProductTranslations::where(['lang' => $language->code, 'product_id' => $model->id])->get();
            foreach ($product_translations as $product_translation){
                $product_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('product.category')->with('status', translate('Successfully deleted'));
    }

    public function getSizes($id){
        $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $id)->get();
        $respone = [
            'status'=>true,
            'data'=>$sizes
        ];
        return response()->json($respone, 200);
    }

    public function category()
    {
        $category = Category::where('step', 0)->get();
        return view('admin.products.category', ['categories'=>$category]);
    }

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
                if(isset($order_detail->order)){
                    $order_detail->order->delete();
                }
                $order_detail->delete();
            }
            foreach($model->warehouse as $warehouse){
                $warehouse->delete();
            }
            $model->delete();
        }

        return redirect()->route('product.category')->with('status', translate('Successfully deleted'));
    }

    public function SlideShow(){
        $products = Products::all();
        return view('admin.slide-show.index', ['products'=>$products]);
    }

    public function SlideShowShow($id){
        $products = Products::where('slide_show')->get();
        return view('admin.slide-show.show');
    }

    public function SlideShowStatus(Request $request){
        $product = Products::find($request->id);
        if(isset($product)){
            if($request->checked == 'true'){
                $product->slide_show = Constants::ACTIVE;
                $message = translate('Added to slide show');
            }elseif($request->checked == 'false'){
                $product->slide_show = Constants::NOT_ACTIVE;
                $message = translate('Deleted from slide show');
            }
            $product->save();
        }
        return $this->success($message, 200);
    }

    // Backend api json

    public function getWarehousesByProduct(Request $request){
        $warehouses_ = Warehouse::where('product_id', $request->product_id)->get();
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
