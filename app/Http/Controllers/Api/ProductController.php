<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Products;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }
        // return "came";
        $products = DB::table('products')
        ->select('id','name','price','images')
        ->get();
        // dd($products);

        $warehouse_products=DB::table('warehouses')
            ->select('product_id', 'id', 'name', 'price', 'images')
            ->distinct('product_id')
            ->get();
        // dd($warehouse_products);

        $data=[
            'product_list'=>$products,
            'warehouse_product_list'=>$warehouse_products
        ];
        // dd($data);
        $message=translate_api('success',$language);
        return $this->success($message, 200,$data);



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        // dd($request->all());
        // return 'came';
        $warehouse_product_id=$request->warehouse_product_id;
        if ($warehouse_product_id != null) {

            $warehouse_product = DB::table('warehouses as dt2')
                // ->join('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_id')
                ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
                ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                // ->leftJoin('coupons as dt5', 'dt5.warehouse_product_id', '=', 'dt2.id')
                ->where('dt2.id' , $warehouse_product_id)
                ->select('dt2.id as warehouse_product_id','dt2.name as warehouse_product_name','dt2.quantity as quantity', 'dt2.images as images', 'dt2.description as description','dt2.product_id as product_id', 'dt2.company_id as company_id', 'dt2.price as price', 'dt3.id as size_id','dt3.name as size_name','dt4.id as color_id','dt4.name as color_name','dt4.code as color_code')
                ->first();
            // dd($warehouse_product);


            $sizes = DB::table('warehouses as dt1')
                ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                ->where('dt1.product_id', $warehouse_product->product_id)
                ->where('dt1.company_id', $warehouse_product->company_id)
                ->select('dt1.id as id','dt3.id as size_id', 'dt3.name as size_name')
                ->distinct('size_id')
                ->get();
                // dd($sizes);

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
                            $aa_color=[
                                'id'=>$color->color_id,
                                'code'=>$color->color_code,
                                'name'=>$color->color_name,
                            ];
                            // dd($aa_color);
                            array_push($color_list,$aa_color);
                        }
                        // dd($color_list);

                        $aa_size=[
                            'id'=>$size->size_id,
                            'name'=>$size->size_name,
                            'color'=>$color_list
                        ];
                        array_push($size_list,$aa_size);

                        // dd($colors);

                }
                // dd($size_list);


            $colors = DB::table('warehouses as dt1')
                ->join('colors as dt3', 'dt3.id', '=', 'dt1.color_id')
                // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                ->where('dt1.product_id', $warehouse_product->product_id)
                ->where('dt1.company_id', $warehouse_product->company_id)
                ->select('dt1.id as id','dt3.id as color_id','dt3.code as color_code', 'dt3.name as color_name')
                ->distinct('color_id')
                ->get();
                // dd($color);

                $aaa_color_list=[];
                foreach ($colors as $color) {
                    $sizes = DB::table('warehouses as dt1')
                        ->join('sizes as dt4', 'dt4.id', '=', 'dt1.size_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->where('dt1.color_id', $color->color_id)
                        ->select('dt4.id as size_id','dt4.name as size_name')
                        // ->distinct('color_id')
                        ->get();
                        // dd($sizes);

                        $aaa_size_list=[];
                        foreach ($sizes as $size) {
                            $aas_size=[
                                'id'=>$size->size_id,
                                'name'=>$size->size_name,
                            ];
                            // dd($aa_color);
                            array_push($aaa_size_list,$aas_size);
                        }
                        // dd($aaa_size_list);

                        $aaa_color=[
                            'id'=>$color->color_id,
                            'code'=>$color->color_code,
                            'name'=>$color->color_name,
                            'sizes'=>$aaa_size_list
                        ];
                        array_push($aaa_color_list,$aaa_color);

                        // dd($colors);

                }
            // $relation_type='warehouse_product';
            // $relation_id=$order_detail->warehouse_id;

            $list=[
                "id"=>$warehouse_product->warehouse_product_id,
                "name"=>$warehouse_product->warehouse_product_name,
                // "relation_id"=>$relation_id,
                "price"=>$warehouse_product->price,
                "quantity"=>$warehouse_product->quantity,
                // "max_quantity"=>$warehouse_product->max_quantity,
                "description"=>$warehouse_product->description,
                "images"=>$warehouse_product->images,
                "color"=>[
                   "id"=>$warehouse_product->color_id,
                   "code"=>$warehouse_product->color_code,
                   "name"=>$warehouse_product->color_name,
                ],
                "size"=>[
                    "id"=>$warehouse_product->size_id,
                    "name"=>$warehouse_product->size_name,
                ],
                "color_by_size"=>$size_list,
                "size_by_color"=>$aaa_color_list
            ];


            $message=translate_api('success',$language);
            return $this->success($message, 200,$list);

            //  dd($list);
            //  return response()->json([
            //     'data'=>$list,
            //     'status'=>true,
            //     'message'=>'Success'
            // ]);

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
        $product = Products::find($request->id);
        if (isset($product->warehouse)) {
            $colors_array = [];
            foreach ($product->warehouse as $warehouse_) {
                $colors_array[] = $warehouse_->color->id;
                if($colors_array[0] == $warehouse_->color->id){
                    $firstColorProducts[] = [
                        'id'=>$warehouse_->id,
                        'size'=>$warehouse_->size ? $warehouse_->size->name:'',
                        'quantity' => $warehouse_->quantity,
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
                            'size' => $warehouse->size ? $warehouse->size->name:'',
                            'price' => $warehouse->price,
                            'quantity' => $warehouse->quantity
                        ];
                    }
                }
                $categorizedByColor[] = [
                    'color'=>$colorModel,
                    'products'=>$productsByColor
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
            $good['first_color_products'] = $firstColorProducts??[];
            $good['images'] = $images??[];
            $good['basket_button'] = false;
            $good['created_at'] = $product->created_at??null;
            $good['updated_at'] = $product->updated_at??null;
        }
        $response = [
            'status'=>true,
            'data'=>$good
        ];
        return response()->json($response, 200);
    }

    public function getCategoriesByProduct($id){
        $product = Products::find($id);
        if(isset($product->id)){
            $current_category = $this->getProductCategory($product);
            $respone = [
                'status'=>true,
                'data'=>$current_category->sizes??[],
                'category'=>$current_category->name??null,
                'price'=>$product->price??null
            ];
        }else{
            $respone = [
                'status'=>true,
                'data'=>[],
                'category'=>null,
                'price'=>null
            ];
        }

        return response()->json($respone, 200);
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
}
