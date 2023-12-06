<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Category;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Products;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discount.index', ['discounts'=> $discounts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        $companies = Company::all();
        return view('admin.discount.create', ['companies'=> $companies, 'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $products = $this->getProducts($request);
        if(isset($request->company_id)){
            foreach ($products as $product){
                $warehouses_id = Warehouse::where('company_id', $request->company_id)->where('product_id', $product->id)->pluck('id');
                $datas[] = [
                  'product_id'=>$product->id,
                  'warehouses_id'=>$warehouses_id
                ];
            }
            foreach ($datas as $data){
                foreach($data['warehouses_id'] as $warehouse_id){
                    $discount = $this->newDiscount($request);
                    $discount->type = Constants::DISCOUNT_WAREHOUSE_TYPE;
                    $discount->warehouse_id = $warehouse_id;
                    $discount->product_id = $data['product_id'];
                    $discount->save();
                }
            }
        }else{
            foreach($products as $product){
                $discount = $this->newDiscount($request);
                $discount->type = Constants::DISCOUNT_PRODUCT_TYPE;
                $discount->product_id = $product->id;
                $discount->save();
            }
        }
        return redirect()->route('discount.index')->with('status', __('Successfully created'));
    }

    public function getProducts($request){
        $sub_category = [];
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $sub_category[] = $request->subcategory_id;
        }else{
            $category = Category::where('step', 0)->find($request->category_id);
            foreach($category->subcategory as $subcategory){
                $sub_category[] = $subcategory->id;
            }
        }
        $products = Products::whereIn('category_id', $sub_category)->get();
        return $products;
    }

    public function newDiscount($request){
        $discount = new Discount();
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        return $discount;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Discount::find($id);
        if(isset($model->category->id)){
            $category = $model->category->name;
            $subcategory = '';
        }elseif(isset($model->subCategory->id)){
            $category = isset($model->subCategory->category)?$model->subCategory->category->name:'';
            $subcategory = $model->subCategory->name;
        }else{
            $category = '';
            $subcategory = '';
        }
        return view('admin.discount.show', ['model'=>$model, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        if(isset($discount->product->category->id)){
            $category_id = $discount->product->category->id;
            $subcategory_id = '';
        }elseif(isset($discount->product->subCategory->id)){
            $category_id = isset($discount->product->subCategory->category)?$discount->product->subCategory->category->id:'';
            $subcategory_id = $discount->product->subCategory->id;
        }else {
            $category_id = '';
            $subcategory_id = '';
        }
        $companies = Company::all();
        return view('admin.discount.edit', ['discount'=> $discount, 'companies'=>$companies, 'categories'=>$categories, 'category_id'=>$category_id, 'subcategory_id'=>$subcategory_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $discount = Discount::find($id);
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        $sub_category = [];
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $sub_category[] = $request->subcategory_id;
        }else{
            $category = Category::where('step', 0)->find($request->category_id);
            foreach($category->subcategory as $subcategory){
                $sub_category[] = $subcategory->id;
            }
        }
        $products = Products::whereIn('category_id', $sub_category)->get();
        if(isset($request->company_id)){
            $discount->type = Constants::DISCOUNT_WAREHOUSE_TYPE;
            foreach ($products as $product){
                $warehouses_id = Warehouse::where('company_id', $request->company_id)->where('product_id', $product->id)->pluck('id');
                $datas[] = [
                    'product_id'=>$product->id,
                    'warehouses_id'=>$warehouses_id
                ];
            }
            foreach ($datas as $data){
                foreach($data['warehouses_id'] as $warehouse_id){
                    $discount->warehouse_id = $warehouse_id;
                    $discount->product_id = $data['product_id'];
                    $discount->save();
                }
            }
        }else{
            $discount->type = Constants::DISCOUNT_PRODUCT_TYPE;
            foreach($products as $product){
                $discount->product_id = $product->id;
                $discount->save();
            }
        }
        return redirect()->route('discount.index')->with('status', __('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Discount::find($id);
        $model->delete();
        return redirect()->route('discount.index')->with('status', __('Successfully created'));
    }
}
