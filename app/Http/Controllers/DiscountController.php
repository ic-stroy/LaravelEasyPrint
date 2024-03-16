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
        $subcategory = '';
        $category = '';
        foreach ($discounts_distinct as $discount_distinct) {
            $discount_number = Discount::where('discount_number', $discount_distinct->discount_number)->get()->count();
            $discount_data = Discount::where('discount_number', $discount_distinct->discount_number)->get();
            foreach($discount_data as $discount__data){
                if(!empty($discount__data->category)){
                    $category = $discount__data->category->name;
                }elseif(!empty($discount__data->subCategory)){
                    if(!empty($discount__data->subCategory->category)){
                        $category = $discount__data->subCategory->category->name;
                    }
                    $subcategory = $discount__data->subCategory->name;
                }
            }
            $discounts_data[] = [
                'discount'=>$discount_data,
                'number'=>$discount_number
            ];
        }
        return view('admin.discount.index', ['discounts_data'=> $discounts_data, 'subcategory'=>$subcategory, 'category'=>$category]);
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
        if($discount_){
            $discount_number = $discount_->discount_number + 1;
        }else{
            $discount_number = 1;
        }
        $products = $this->getProducts($request);
        if(isset($request->company_id) && $request->company_id){
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
        return redirect()->route('discount.index')->with('status', translate('Successfully created'));
    }

    public function getProducts($request){
        $sub_category = [];
        if(isset($request->product_id) && $request->product_id != "all" && $request->product_id){
            $products = Products::where('id', $request->product_id)->get();
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $sub_category = $request->subcategory_id;
            $products = Products::where('category_id', $sub_category)->get();
        }elseif(isset($request->category_id) && $request->category_id != "all" && $request->category_id){
            $category = Category::where('step', 0)->find($request->category_id);
            foreach($category->subcategory as $subcategory){
                $sub_category[] = $subcategory->id;
            }
            array_push($sub_category, $request->category_id);
            $products = Products::whereIn('category_id', $sub_category)->get();
        }
        return $products;
    }

    public function newDiscount($request){
        $discount = new Discount();
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $discount->category_id = $request->subcategory_id;
        }else{
            $discount->category_id = $request->category_id;
        }
        return $discount;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $model = Discount::find($id);
        $discount_number = Discount::where('discount_number', $model->discount_number)->get()->count();
        $discounts = Discount::where('discount_number', $model->discount_number)->get();
        $discounts_data = [
            'discounts'=>$discounts,
            'number'=>$discount_number,
        ];
        $category = !empty($model->category)?$model->category->name:'';
        $subcategory = !empty($model->subCategory)?$model->subCategory->name:'';
        return view('admin.discount.show', ['model'=>$model, 'discounts_data'=>$discounts_data, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        $category_id = '';
        $subcategory_id = '';
        if($discount->category){
            $category_id = $discount->category->id;
        }elseif($discount->subCategory){
            $category_id = !empty($discount->subCategory->category)?$discount->subCategory->category->id:'';
            $subcategory_id = $discount->subCategory->id;
        }
        if($discount->company){
            $discount_company = $discount->company;
        }else{
            $discount_company = json_decode(json_encode([
                'id'=>null,
                'name'=>null,
            ]));
        }
        $companies = Company::all();
        return view('admin.discount.edit', ['discount'=> $discount, 'companies'=>$companies, 'categories'=>$categories, 'discount_company'=>$discount_company, 'category_id'=>$category_id, 'subcategory_id'=>$subcategory_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $discount_ = Discount::orderBy('discount_number', 'desc')->first();
        if($discount_){
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
        if(isset($request->company_id) && $request->company_id){
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
        return redirect()->route('discount.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $current_discount = Discount::find($id);
        $current_discount_group = Discount::where('discount_number', $current_discount->discount_number)->get();
        foreach ($current_discount_group as $currentDiscount){
            $currentDiscount->delete();
        }
        return redirect()->route('discount.index')->with('status', translate('Successfully created'));
    }
}
