<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function getProductsByCategories(Request $request){
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        $data = [];
        foreach ($categories as $category){
            $category_ids = [];
            $subcategories = $category->subcategory;
            foreach ($subcategories as $subcategory){
                $category_ids[] = $subcategory->id;
            }
            $subsubcategories = Category::WhereIn('parent_id', $category_ids)->get();
            foreach ($subsubcategories as $subsubcategory){
                $category_ids[] = $subsubcategory->id;
            }
            $category_ids[] = $category->id;
            $products = Products::select('id', 'name', 'category_id', 'images', 'material_id', 'manufacturer_country', 'material_composition', 'price', 'description')->whereIn('category_id', $category_ids)->get();
            $data[] = [
                'category'=>[
                    'id'=>$category->id,
                    'name'=>$category->name,
                ],
                'products'=>[
                    $products
                ]
            ];
        }
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }

    public function profileInfo(Request $request){
        $language = $request->header('language');
        $languages = Language::select('id', 'name', 'code')->get();
        $user = Auth::user();
        $basket_count = count(isset($user->orderBasket->order_detail)?$user->orderBasket->order_detail:[]);
        $personalInfo = isset($user->personalInfo)?$user->personalInfo:[];
        if(isset($user->personalInfo)){
            $sms_avatar = storage_path('app/public/user/'.$user->personalInfo->avatar);
        }else{
            $sms_avatar = 'no';
        }

        $profile = [
            'name'=>isset($personalInfo->first_name)?$personalInfo->first_name:null,
            'avatar'=>file_exists($sms_avatar)?asset('storage/user/'.$personalInfo->avatar):asset('assets/images/man.jpg')
        ];
        $data = [
            'language'=>$languages,
            'basket_count'=>$basket_count,
            'profile'=>$profile,
        ];
         $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }
}
