<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
        $id=auth()->user()->company_id;


        $coupons = DB::table('coupons as dt1')
            // ->Leftjoin('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_product_id')
            // ->Leftjoin('categories as dt3', 'dt3.id', '=', 'dt1.category_id')
            ->where('dt1.company_id', $id)
            // ->select('dt1.*', 'dt2.*', 'dt3.*')
            ->get();
            // dd($coupons);

            return view('company.coupons.index', ['coupons'=> $coupons]);



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id=auth()->user()->company_id;
        $warehouse_products= DB::table('warehouses')
        ->where('company_id', $id)
        ->latest()
        ->get();
        // dd($warehouse_products);

        $categories = Category::where('step', 2)->orderBy('created_at', 'desc')->get();

        return view('company.coupons.create', ['warehouse_products'=> $warehouse_products, 'categories'=> $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->relation_type);

        $company_id=auth()->user()->company_id;
        if ($request->relation_type == "product") {
            $category_id=null;
            $warehouse_id=$request->relation_id;
        }elseif ($request->relation_type == "category") {
            $warehouse_id=null;
            $category_id=$request->relation_id;
            // dd($category_id);
        }
        if ($request->coupon_type == "price") {
            $price=$request->sum;
            $percent=null;
        } elseif ($request->coupon_type == "percent") {
            $percent=$request->sum;
            $price=null;
        }
        // dd($category_id);

            $warehouse=Coupon::create([
                'percent'=>$percent,
                'price'=>$price,
                'category_id'=>$category_id,
                'warehouse_id'=>$warehouse_id,
                'company_id'=>$company_id
            ]);
        // dd($warehouse);

        return redirect()->route('company_coupon.index')->with('status', __('Successfully created'));

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

        $coupon = DB::table('coupons as dt1')
        // ->Leftjoin('warehouses as dt2', 'dt2.id', '=', 'dt1.warehouse_product_id')
        // ->Leftjoin('categories as dt3', 'dt3.id', '=', 'dt1.category_id')
        ->where('dt1.id', $id)
        // ->select('dt1.*', 'dt2.*', 'dt3.*')
        ->first();
        // dd($coupons);

        return view('company.coupons.edit', ['coupon'=> $coupon]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($id);
        $company_id=auth()->user()->company_id;
        if ($request->relation_type == "product") {
            $category_id=null;
            $warehouse_id=$request->relation_id;
        }elseif ($request->relation_type == "category") {
            $warehouse_id=null;
            $category_id=$request->relation_id;
            // dd($category_id);
        }
        if ($request->coupon_type == "price") {
            $price=$request->sum;
            $percent=null;
        } elseif ($request->coupon_type == "percent") {
            $percent=$request->sum;
            $price=null;
        }
        // dd($category_id);
            $coupon=Coupon::where('id',$id)->first();
            // dd($coupon);

            $coupon=$coupon->update([
                'percent'=>$percent,
                'price'=>$price,
                'category_id'=>$category_id,
                'warehouse_product_id'=>$warehouse_id
            ]);
        // dd($warehouse);

        return redirect()->route('company_coupon.index')->with('status', __('Successfully created'));
    }

    public function relation(Request $request)
    {
        // dd($request->all());

        // return $request->all();


        if ($request->relation == "product") {
            $id=auth()->user()->company_id;
            $warehouse_products= DB::table('warehouses')
            ->where('company_id', $id)
            ->latest()
            ->get();
            // dd($warehouse_products);



            // $aaa="@foreach($warehouse_products as $warehouse_product)
            //             <option value='{{$warehouse_product->id}}'>{{$warehouse_product->name}}</option>
            //       @endforeach";



            return $warehouse_products;
        }
        $categories= DB::table('categories')
            ->latest()
            ->get();
            return $categories;
        // $model = Category::where('parent_id', $id)->get();
        // if(isset($model) && count($model)>0){
        //     return response()->json([
        //         'status'=>true,
        //         'data'=>$model
        //     ]);
        // }else{
        //     return response()->json([
        //         'status'=>false,
        //         'data'=>[]
        //     ]);
        // }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
