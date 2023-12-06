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
        $discounts_distinct = Discount::distinct('discount_number')->get();
        $discounts_data = [];
        foreach ($discounts_distinct as $discount_distinct) {
            $discount_number = Discount::where('discount_number', $discount_distinct->discount_number)->get()->count();
            $discounts_data[] = [
                'discount'=>$discount_distinct,
                'number'=>$discount_number
            ];
        }
        return view('admin.discount.index', ['discounts_data'=> $discounts_data]);
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
        $discount_ = Discount::orderBy('discount_number', 'desc')->first();
        if(isset($discount_->id)){
            $discount_number = $discount_->discount_number + 1;
        }else{
            $discount_number = 1;
        }
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
                    $discount->company_id = $request->company_id;
                    $discount->discount_number = $discount_number;
                    $discount->save();
                }
            }
        }else{
            foreach($products as $product){
                $discount = $this->newDiscount($request);
                $discount->type = Constants::DISCOUNT_PRODUCT_TYPE;
                $discount->product_id = $product->id;
                $discount->discount_number = $discount_number;
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
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $discount->category_id = $request->subcategory_id;
        }else{
            $discount->category_id = $request->category_id;
        }
        return $discount;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Discount::find($id);
        $discount_number = Discount::where('discount_number', $model->discount_number)->get()->count();
        $discounts_data = [
            'discount'=>$model,
            'number'=>$discount_number
        ];
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
        return view('admin.discount.show', ['model'=>$model, 'discounts_data'=>$discounts_data, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        if(isset($discount->category->id)){
            $category_id = $discount->category->id;
            $subcategory_id = '';
        }elseif(isset($discount->subCategory->id)){
            $category_id = isset($discount->subCategory->category)?$discount->subCategory->category->id:'';
            $subcategory_id = $discount->subCategory->id;
        }else{
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
        $discount_ = Discount::orderBy('discount_number', 'desc')->first();
        if(isset($discount_->id)){
            $discount_number = $discount_->discount_number + 1;
        }else{
            $discount_number = 1;
        }
        $products = $this->getProducts($request);
        $current_discount = Discount::find($id);
        $current_discount_group = Discount::where('discount_number', $current_discount->discount_number)->get();
        foreach ($current_discount_group as $currentDiscount){
            $currentDiscount->delete();
        }
        if(isset($request->company_id)){
            foreach($products as $product){
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
                    $discount->company_id = $request->company_id;
                    $discount->discount_number = $discount_number;
                    $discount->save();
                }
            }
        }else{
            foreach($products as $product){
                $discount = $this->newDiscount($request);
                $discount->type = Constants::DISCOUNT_PRODUCT_TYPE;
                $discount->product_id = $product->id;
                $discount->discount_number = $discount_number;
                $discount->save();
            }
        }
        return redirect()->route('discount.index')->with('status', __('Successfully updated'));
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
