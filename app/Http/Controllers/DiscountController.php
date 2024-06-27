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
            $subcategory = [];
            $category = [];
            $discount_number = Discount::where('discount_number', $discount_distinct->discount_number)->count();
            $discount_data = Discount::where('discount_number', $discount_distinct->discount_number)->get();
            foreach($discount_data as $discount__data){
                if(!empty($discount__data->category)){
                    if(!in_array($discount__data->category->name, $category)){
                        $category[] = $discount__data->category->name;
                    }
                    $subcategory = [1, 2];
                }elseif(!empty($discount__data->subCategory)){
                    if(!empty($discount__data->subCategory->category)){
                        if(!in_array($discount__data->subCategory->category->name, $category)){
                            $category[] = $discount__data->subCategory->category->name;
                        }
                    }
                    if(!in_array($discount__data->subCategory->name, $subcategory)){
                        $subcategory[] = $discount__data->subCategory->name;
                    }
                }
            }
            if(count($category) == 1){
                $category = [$category[0]];
            }elseif(count($category) > 1){
                $category = [translate('All categories')];
            }else{
                $category = [''];
            }

            if(count($subcategory) == 1){
                $subcategory = [$subcategory[0]];
            }elseif(count($subcategory) > 1){
                $subcategory = [translate('All subcategories')];
            }else{
                $subcategory = [''];
            }

            $discounts_data[] = [
                'discount'=>$discount_data,
                'number'=>$discount_number,
                'category'=>$category,
                'subcategory'=>$subcategory
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
        if($discount_){
            $discount_number = $discount_->discount_number + 1;
        }else{
            $discount_number = 1;
        }
        $products = $this->getProducts($request);
        if($products->isEmpty()){
            return redirect()->back()->with('error', translate('There is no product in this category'));
        }
        if(isset($request->company_id) && $request->company_id){
            foreach ($products as $product){
                $warehouses_id = Warehouse::where('company_id', $request->company_id)->where('product_id', $product->id)->pluck('id');
                $datas[] = [
                  'product_id'=>$product->id,
                  'category_id'=>$product->category_id,
                  'warehouses_id'=>$warehouses_id
                ];
            }
            foreach ($datas as $data){
                foreach($data['warehouses_id'] as $warehouse_id){
                    $discount = $this->newDiscount($request, $data['category_id']);
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
                $discount = $this->newDiscount($request, $product->category_id);
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
        }elseif(isset($request->category_id) && $request->category_id){
            $category = Category::where('step', 0)->find($request->category_id);
            foreach($category->subcategory as $subcategory){
                $sub_category[] = $subcategory->id;
            }
            array_push($sub_category, $request->category_id);
            $products = Products::whereIn('category_id', $sub_category)->get();
        }else{
            $categories = Category::where('step', 0)->get();
            $categories_id = [];
            foreach($categories as $category){
                foreach($category->subcategory as $subcategory){
                    $sub_category[] = $subcategory->id;
                }
                $categories_id[] = $category->id;
            }
            $products = Products::whereIn('category_id', array_merge($sub_category, $categories_id))->get();
        }
        return $products;
    }

    public function newDiscount($request, $category_id){
        $discount = new Discount();
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $discount->category_id = $request->subcategory_id;
        }elseif(isset($request->category_id) && $request->category_id){
            $discount->category_id = $request->category_id;
        }else{
            $discount->category_id = $category_id;
        }
        return $discount;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $model = Discount::select('discount_number')->find($id);
        $discount_number = Discount::where('discount_number', $model->discount_number)->count();
        $discounts = Discount::where('discount_number', $model->discount_number)->get();
        $subcategory = [];
        $category = [];
        foreach($discounts as $discount){
            if(!empty($discount->category)){
                if(!in_array($discount->category->name, $category)){
                    $category[] = $discount->category->name;
                }
                $subcategory = [1, 2];
            }elseif(!empty($discount->subCategory)){
                if(!empty($discount->subCategory->category)){
                    if(!in_array($discount->subCategory->category->name, $category)){
                        $category[] = $discount->subCategory->category->name;
                    }
                }
                if(!in_array($discount->subCategory->name, $subcategory)){
                    $subcategory[] = $discount->subCategory->name;
                }
            }
        }

        if(count($category) == 1){
            $category = [$category[0]];
        }elseif(count($category) > 1){
            $category = [__('All categories')];
        }else{
            $category = [''];
        }

        if(count($subcategory) == 1){
            $subcategory = [$subcategory[0]];
        }elseif(count($subcategory) > 1){
            $subcategory = [__('All subcategories')];
        }else{
            $subcategory = [''];
        }

        $discount_data = [
            'discounts'=>$discounts,
            'number'=>$discount_number,
            'category'=>$category,
            'subcategory'=>$subcategory,
        ];
        return view('admin.discount.show', ['discount_data'=>$discount_data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $category_id = [];
        $subcategory_id = [];
        $quantity = 0;
        $discount_data = Discount::where('discount_number', $discount->discount_number)->get();
        foreach($discount_data as $discount__data){
            if($discount__data->type == Constants::DISCOUNT_PRODUCT_TYPE){
                $quantity++;
            }
            if(!empty($discount__data->category)){
                if(!in_array($discount__data->category->id, $category_id)){
                    $category_id[] = $discount__data->category->id;
                }
                $subcategory_id = ['a', 'a'];
            }elseif(!empty($discount__data->subCategory)){
                if(!empty($discount__data->subCategory->category)){
                    if(!in_array($discount__data->subCategory->category->id, $category_id)){
                        $category_id[] = $discount__data->subCategory->category->id;
                    }
                }
                if(!in_array($discount__data->subCategory->id, $subcategory_id)){
                    $subcategory_id[] = $discount__data->subCategory->id;
                }
            }
        }
        if(count($category_id) == 1){
            $category_id = $category_id[0];
        }elseif(count($category_id) > 1){
            $category_id = 'two';
        }else{
            $category_id = '';
        }

        if(count($subcategory_id) == 1){
            $subcategory_id = $subcategory_id[0];
        }elseif(count($subcategory_id) > 1){
            $subcategory_id = 'two';
        }else{
            $subcategory_id = '';
        }

        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();

        if($discount->company){
            $discount_company = $discount->company;
        }else{
            $discount_company = json_decode(json_encode([
                'id'=>null,
                'name'=>null,
            ]));
        }
        $companies = Company::all();
        return view('admin.discount.edit', [
            'discount'=> $discount,
            'companies'=>$companies,
            'categories'=>$categories,
            'discount_company'=>$discount_company,
            'category_id'=>$category_id,
            'subcategory_id'=>$subcategory_id,
            'quantity'=>$quantity,
        ]);
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
        if($products->isEmpty()){
            return redirect()->back()->with('error', translate('There is no product in this category'));
        }
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
                    'category_id'=>$product->category_id,
                    'warehouses_id'=>$warehouses_id
                ];
            }
            foreach ($datas as $data){
                foreach($data['warehouses_id'] as $warehouse_id){
                    $discount = $this->newDiscount($request, $data['category_id']);
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
                $discount = $this->newDiscount($request, $product->category_id);
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
