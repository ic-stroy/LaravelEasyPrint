<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants;
use App\Models\Products;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $language=$request->header('language');
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        // $products_ = DB::table('products')
        //     ->select('id','name','price','images')
        //     ->get();

        $products_ = Products::select('id','name','price','images')->with('discount')->get();

        foreach ($products_ as $product_) {
            $products[] = [
                'id' => $product_->id,
                'name' => $product_->name,
                'price' => $product_->price,
                'discount' => (isset($product_->discount)) > 0 ? $product_->discount->percent : NULL,
                'price_discount' => (isset($product_->discount)) > 0 ? $product_->price - ($product_->price / 100 * $product_->discount->percent) : NULL,
                'images' => $this->getImages($product_, 'product')
            ];
        }
        // $warehouse_products_=DB::table('warehouses')
        //     ->select('product_id', 'id', 'name', 'price', 'images')
        //     ->distinct('product_id')
        //     ->get();
        // $warehouse_products = [];
        // foreach ($warehouse_products_ as $warehouse_product_){
        //     if(count($this->getImages($warehouse_product_, 'warehouse'))>0){
        //         $warehouseProducts = $this->getImages($warehouse_product_, 'warehouse');
        //     }else{
        //         $warehouseProducts = $this->getImages($product_, 'product');
        //     }
        //     $warehouse_products[] = [
        //         'product_id' => $warehouse_product_->product_id,
        //         'id' => $warehouse_product_->id,
        //         'name' => $warehouse_product_->name,
        //         'price' => $warehouse_product_->price,
        //         'images' => $warehouseProducts
        //     ];
        // }
        $data=[
            'product_list'=>$products
        ];
        $message=translate_api('success',$language);
        return $this->success($message, 200,$data);

    }

    public function getWarehouses(Request $request)
    {
        $language = $request->header('language');
        $warehouse_products_ = Warehouse::distinct('product_id')->get();
        $warehouse_products = [];
        // dd($warehouse_products_);

        foreach ($warehouse_products_ as $warehouse_product_) {
            if (count($this->getImages($warehouse_product_, 'warehouse'))>0) {
                $warehouseProducts = $this->getImages($warehouse_product_, 'warehouse');
            } else {
                $warehouseProducts = $this->getImages($warehouse_product_->product, 'product');
            }

            //  join qilish kere
            $warehouse_products[] = [
                // 'product_id' => $warehouse_product_->product_id,
                'id' => $warehouse_product_->id,
                'name' => $warehouse_product_->name ?? $warehouse_product_->product->name,
                'price' => $warehouse_product_->price,
                'discount' => (isset($warehouse_product_->discount)) > 0 ? $warehouse_product_->discount->percent : NULL,
                'price_discount' => (isset($warehouse_product_->discount)) > 0 ? $warehouse_product_->price - ($warehouse_product_->price / 100 * $warehouse_product_->discount->percent) : NULL,
                'images' => $warehouseProducts
            ];
        }

        $products_ = DB::table('products')
            ->select('id','name', 'price', 'images')
            ->where('slide_show', Constants::ACTIVE)
            ->get();

        $products = [];
        foreach ($products_ as $product_) {
            $products[] = [
                'id' => $product_->id,
                'name' => $product_->name,
                'price' => $product_->price,
                'discount' => (isset($product_->discount)) > 0 ? $product_->discount->percent : NULL,
                'price_discount' => (isset($product_->discount)) > 0 ? $product_->price - ($product_->price / 100 * $product_->discount->percent) : NULL,
                'images' => $this->getImages($product_, 'product')
            ];
        }

        $data = [
            'product_list' => $products,
            'warehouse_product_list' => $warehouse_products
        ];

        $message = translate_api('success',$language);
        return $this->success($message, 200, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $language = $request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        $warehouse_product_id = $request->warehouse_product_id;
        if ($warehouse_product_id != null) {
            $warehouse_product = DB::table('warehouses as dt2')
                // ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                ->join('products as dt5', 'dt5.id', '=', 'dt2.product_id')
                ->leftJoin('discounts as dt6', function($join) {
                    $join->on('warehouse_id', '=', 'dt2.id')
                    ->where('type', 2)
                    ->where('start_date', '<=', date('Y-m-d H:i:s'))
                    ->where('end_date', '>=', date('Y-m-d H:i:s'));
                })
                // ->leftJoin('coupons as dt5', 'dt5.warehouse_product_id', '=', 'dt2.id')
                ->where('dt2.id' , $warehouse_product_id)
                ->select('dt2.id as warehouse_product_id','dt2.name as warehouse_product_name','dt2.quantity as quantity', 'dt2.images as images', 'dt2.description as description',
                    'dt2.product_id as product_id', 'dt2.company_id as company_id', 'dt2.price as price', 'dt3.id as size_id',
                    'dt3.name as size_name','dt4.id as color_id','dt4.name as color_name','dt4.code as color_code',
                    'dt5.name as product_name', 'dt5.images as product_images', 'dt5.description as product_description', 'dt6.percent AS discount')
                ->first();
                // dd($warehouse_product);

            if (isset($warehouse_product->images)) {
                $images_ = json_decode($warehouse_product->images);
                $images = [];
                foreach ($images_ as $image_) {
                    $images[] = asset('storage/warehouses/' . $image_);
                }
            } elseif (isset($warehouse_product->product_images)) {
                $images_ = json_decode($warehouse_product->product_images);
                $images = [];
                foreach ($images_ as $image_){
                    $images[] = asset('storage/products/' . $image_);
                }
            } else {
                $images = [];
            }

            if (isset($warehouse_product->product_id)) {
                $sizes = DB::table('warehouses as dt1')
                    ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                    // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                    ->where('dt1.product_id', $warehouse_product->product_id)
                    ->where('dt1.company_id', $warehouse_product->company_id)
                    ->select('dt1.id as id','dt3.id as size_id', 'dt3.name as size_name')
                    ->distinct('size_id')
                    ->get();

                $size_list=[];
                foreach ($sizes as $size) {
                    $colors = DB::table('warehouses as dt1')
                        ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->where('dt1.size_id', $size->size_id)
                        ->select('dt4.id as color_id','dt4.code as color_code', 'dt4.name as color_name','dt1.images as images')
                        // ->distinct('color_id')
                        ->get();

                    $color_list=[];
                    foreach ($colors as $color) {
                        $aa_color = [
                            'id' => $color->color_id,
                            'code' => $color->color_code,
                            'name' => $color->color_name,
                        ];
                        array_push($color_list,$aa_color);
                    }

                    $aa_size = [
                        'id' => $size->size_id,
                        'name' => $size->size_name,
                        'color' => $color_list
                    ];

                    array_push($size_list, $aa_size);
                }

                $colors = DB::table('warehouses as dt1')
                    ->join('colors as dt3', 'dt3.id', '=', 'dt1.color_id')
                    // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                    ->where('dt1.product_id', $warehouse_product->product_id)
                    ->where('dt1.company_id', $warehouse_product->company_id)
                    ->select('dt1.id as id','dt3.id as color_id','dt3.code as color_code', 'dt3.name as color_name')
                    ->distinct('color_id')
                    ->get();

                $aaa_color_list = [];
                foreach ($colors as $color) {
                    $sizes = DB::table('warehouses as dt1')
                        ->join('sizes as dt4', 'dt4.id', '=', 'dt1.size_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->where('dt1.color_id', $color->color_id)
                        ->select('dt4.id as size_id','dt4.name as size_name')
                        // ->distinct('color_id')
                        ->get();

                    $aaa_size_list = [];
                    foreach ($sizes as $size) {
                        $aas_size = [
                            'id' => $size->size_id,
                            'name' => $size->size_name,
                        ];
                        array_push($aaa_size_list,$aas_size);
                    }

                    $aaa_color = [
                        'id' => $color->color_id,
                        'code' => $color->color_code,
                        'name' => $color->color_name,
                        'sizes' => $aaa_size_list
                    ];

                    array_push($aaa_color_list,$aaa_color);
                }
            } else {
                $aaa_color_list = [];
                $size_list = [];
            }

            // $relation_type='warehouse_product';
            // $relation_id=$order_detail->warehouse_id;
            if (isset($warehouse_product->warehouse_product_id)) {
                $list = [
                    "id" => $warehouse_product->warehouse_product_id,
                    "name" => $warehouse_product->warehouse_product_name ?? $warehouse_product->product_name,
                    // "relation_id" => $relation_id,
                    "price" => $warehouse_product->price,
                    'discount' => (isset($warehouse_product->discount)) > 0 ? $warehouse_product->discount : NULL,
                    'price_discount' => (isset($warehouse_product->discount)) > 0 ? $warehouse_product->price - ($warehouse_product->price / 100 * $warehouse_product->discount) : NULL,
                    // "discounts" => $warehouse_product->price,
                    "quantity" => $warehouse_product->quantity,
                    // "max_quantity" => $warehouse_product->max_quantity,
                    "description" => $warehouse_product->description ?? $warehouse_product->product_description,
                    "images" => $images,
                    "color" => [
                        "id" => $warehouse_product->color_id,
                        "code" => $warehouse_product->color_code,
                        "name" => $warehouse_product->color_name,
                    ],
                    "size" => [
                        "id" => $warehouse_product->size_id,
                        "name" => $warehouse_product->size_name,
                    ],
                    "color_by_size" => $size_list,
                    "size_by_color" => $aaa_color_list
                ];
            } else {
                $list = [];
            }

            $message = translate_api('success', $language);
            return $this->success($message, 200, $list);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProduct(Request $request)
    {
        $language = $request->header('language');
        $product = Products::find($request->id);
        if (isset($product->warehouse)) {
            $colors_array = [];
            $sizes_array = [];
            foreach ($product->warehouse as $warehouse_) {
                $colors_array[] = $warehouse_->color->id;
                $sizes_array[] = $warehouse_->size->id;
                if($colors_array[0] == $warehouse_->color->id){
                    $firstColorProducts[] = [
                        'id'=>$warehouse_->id,
                        'size'=>isset($warehouse_->size) ? $warehouse_->size->name:'',
                        'quantity' => $warehouse_->quantity,
                        'images' => $this->getImages($warehouse_, 'warehouse'),
                    ];
                }
            }
            foreach (array_unique($colors_array) as $color) {
                $productsByColor = [];
                foreach ($product->warehouse as $warehouse){
                    if($color == $warehouse->color->id){
                        $colorModel = $warehouse->color;
                        $productsByColor[] = [
                            'id' => $warehouse->id,
                            'size' => isset($warehouse->size) ? $warehouse->size->name:'',
                            'price' => $warehouse->price,
                            'quantity' => $warehouse->quantity,
                            'images' => $this->getImages($warehouse, 'warehouse'),
                        ];
                    }
                }
                $categorizedByColor[] = [
                    'color'=>$colorModel,
                    'products'=>$productsByColor
                ];
            }
            foreach (array_unique($sizes_array) as $size) {
                $productsBySize = [];
                foreach ($product->warehouse as $warehouse){
                    if($size == $warehouse->size->id){
                        $sizeModel = $warehouse->size;
                        $productsBySize[] = [
                            'id' => $warehouse->id,
                            'color' => isset($warehouse->color) ? $warehouse->color:'',
                            'price' => $warehouse->price,
                            'quantity' => $warehouse->quantity,
                            'images' => $this->getImages($warehouse, 'warehouse'),
                        ];
                    }
                }
                $categorizedBySize[] = [
                    'size'=>$sizeModel,
                    'products'=>$productsBySize
                ];
            }
        }
        $good = [];
        if(isset($product->id)){
            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_){
                $images[] = asset('storage/products/'.$image_);
            }
            $good['id'] = $product->id;
            $good['name'] = $product->name??null;
            if(isset($product->subCategory->name)){
                $good['subcategory'] = $product->subCategory->name;
            }else{
                $good['subcategory'] = null;
            }
            $current_category = $this->getProductCategory($product);
            $good['category'] = $current_category->name??null;
            $good['description'] = $product->description ?? null;
            $good['price'] = $product->price??null;
            $good['company'] = $product->company??null;
            $good['characters'] = $categorizedByColor??[];
            $good['characters_by_size'] = $categorizedBySize??[];
            $good['first_color_products'] = $firstColorProducts??[];
            $good['images'] = $images??[];
            $good['basket_button'] = false;
            $good['created_at'] = $product->created_at??null;
            $good['updated_at'] = $product->updated_at??null;
        }
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $good);
    }

    public function getCategoriesByProduct(Request $request, $id){
        $language = $request->header('language');
        $product = Products::find($id);
        if(isset($product->id)){
            $current_category = $this->getProductCategory($product);
            $respone = [
                'data'=>$current_category->sizes??[],
                'category'=>$current_category->name??null,
                'price'=>$product->price??null
            ];
        }else{
            $respone = [
                'data'=>[],
                'category'=>null,
                'price'=>null
            ];
        }
         $message = translate_api('Success', $language);
        return $this->success($message, 200, $respone);
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

    public function getImages($model, $text){
        if(isset($model->images)){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                if($text == 'warehouse'){
                    $images[] = asset('storage/warehouses/'.$image_);
                }elseif($text == 'product'){
                    $images[] = asset('storage/products/'.$image_);
                }
            }
        }else{
            $images = [];
        }
        return $images;
    }

}
