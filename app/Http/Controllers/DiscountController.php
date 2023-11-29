<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $coupons = Discount::where('company_id', $user->company_id)->get();
        return view('admin.coupons.index', ['coupons'=> $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('admin.coupons.create', ['categories'=> $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $discount = new Discount();
        $discount->percent = $request->percent;
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $discount->category_id = $request->subcategory_id;
            if (isset($request->product_id) && $request->product_id != "all" && $request->product_id != "") {
                $discount->product_id = $request->product_id;
            }
        }else{
            $discount->category_id = $request->category_id;
        }
        $discount->company_id = $user->company_id;
        $discount->save();
        return redirect()->route('company_coupon.index')->with('status', __('Successfully created'));
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
        return view('company.coupons.show', ['model'=>$model, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $coupon = Discount::where('company_id', $user->company_id)->find($id);
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        if(isset($coupon->category->id)){
            $category_id = $coupon->category->id;
            $subcategory_id = '';
        }elseif(isset($coupon->subCategory->id)){
            $category_id = isset($coupon->subCategory->category)?$coupon->subCategory->category->id:'';
            $subcategory_id = $coupon->subCategory->id;
        }else {
            $category_id = '';
            $subcategory_id = '';
        }
        return view('company.coupons.edit', ['coupon'=> $coupon, 'categories'=>$categories, 'category_id'=>$category_id, 'subcategory_id'=>$subcategory_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $coupon = Discount::find($id);
        $coupon->name = $request->name;
        $coupon->percent = $request->percent;
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $coupon->category_id = $request->subcategory_id;
            if (isset($request->product_id) && $request->product_id != "all" && $request->product_id != "") {
                $coupon->product_id = $request->product_id;
            }else{
                $coupon->product_id = NULL;
            }
        }else{
            $coupon->category_id = $request->category_id;
            $coupon->product_id = NULL;
        }
        $coupon->company_id = $user->company_id;
        $coupon->save();
        return redirect()->route('company_coupon.index')->with('status', __('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Discount::find($id);
        $model->delete();
        return redirect()->route('company_coupon.index')->with('status', __('Successfully created'));
    }
}
