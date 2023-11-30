<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $discounts = Discount::where('company_id', $user->company_id)->get();
        return view('company.discount.index', ['discounts'=> $discounts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('company.discount.create', ['categories'=> $categories]);
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
        return redirect()->route('company_discount.index')->with('status', __('Successfully created'));
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
        return view('company.discount.show', ['model'=>$model, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $discount = Discount::where('company_id', $user->company_id)->find($id);
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        if(isset($discount->category->id)){
            $category_id = $discount->category->id;
            $subcategory_id = '';
        }elseif(isset($discount->subCategory->id)){
            $category_id = isset($discount->subCategory->category)?$discount->subCategory->category->id:'';
            $subcategory_id = $discount->subCategory->id;
        }else {
            $category_id = '';
            $subcategory_id = '';
        }
        return view('company.discount.edit', ['discount'=> $discount, 'categories'=>$categories, 'category_id'=>$category_id, 'subcategory_id'=>$subcategory_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $discount = Discount::find($id);
        $discount->percent = $request->percent;
        if (isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id != ""){
            $discount->category_id = $request->subcategory_id;
            if (isset($request->product_id) && $request->product_id != "all" && $request->product_id != "") {
                $discount->product_id = $request->product_id;
            }else{
                $discount->product_id = NULL;
            }
        }else{
            $discount->category_id = $request->category_id;
            $discount->product_id = NULL;
        }
        $discount->company_id = $user->company_id;
        $discount->save();
        return redirect()->route('company_discount.index')->with('status', __('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Discount::find($id);
        $model->delete();
        return redirect()->route('company_discount.index')->with('status', __('Successfully created'));
    }
}
