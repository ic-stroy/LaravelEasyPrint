<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Coupon;


use Illuminate\Http\Request;

class CompanyCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $coupons = Coupon::where('company_id', $user->company_id)->get();
        return view('company.coupons.index', ['coupons'=> $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('company.coupons.create', ['categories'=> $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $coupon = new Coupon();
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        } elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $coupon->category_id = $request->subcategory_id;
            if (isset($request->product_id) && $request->product_id != "all" && $request->product_id != "") {
                $coupon->product_id = $request->product_id;
                if (isset($request->warehouse_id) && $request->warehouse_id != "all" && $request->warehouse_id != "") {
                    $coupon->warehouse_product_id = $request->warehouse_id;
                }
            }
        }else{
            $coupon->category_id = $request->category_id;
        }
        $coupon->company_id = $user->company_id;
        $coupon->save();
        return redirect()->route('company_coupon.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Coupon::find($id);
        if(isset($model->category->id)){
            $category = $model->category->name;
            $subcategory = '';
        }elseif(isset($model->subCategory->id)){
            $category = isset($model->subCategory->category)?$model->subCategory->category->name:'';
            $subcategory = $model->subCategory->name;
        }else {
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
        $coupon = Coupon::where('company_id', $user->company_id)->find($id);
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
        $coupon = Coupon::find($id);
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        } elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $coupon->category_id = $request->subcategory_id;
            if (isset($request->product_id) && $request->product_id != "all" && $request->product_id != "") {
                $coupon->product_id = $request->product_id;
                if (isset($request->warehouse_id) && $request->warehouse_id != "all" && $request->warehouse_id != "") {
                    $coupon->warehouse_product_id = $request->warehouse_id;
                }else{
                    $coupon->warehouse_product_id = NULL;
                }
            }else{
                $coupon->product_id = NULL;
                $coupon->warehouse_product_id = NULL;
            }
        }else{
            $coupon->category_id = $request->category_id;
            $coupon->warehouse_product_id = NULL;
            $coupon->product_id = NULL;
        }
        $coupon->company_id = $user->company_id;
        $coupon->save();
        return redirect()->route('company_coupon.index')->with('status', translate('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Coupon::find($id);
        $model->delete();
        return redirect()->route('company_coupon.index')->with('status', translate('Successfully created'));
    }
}
