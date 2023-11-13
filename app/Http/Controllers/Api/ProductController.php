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
    public function show(string $id)
    {
        //
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

    public function getCategoriesByProduct($id){
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        $respone = [
            'status'=>true,
            'data'=>$current_category->sizes??[],
            'category'=>$current_category->name??"",
            'sum'=>$product->sum
        ];
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
