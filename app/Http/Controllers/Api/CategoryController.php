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
    public function getCategories(Request $request){
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        foreach ($categories as $category){
            $subcategory = [];
            foreach ($category->subcategory as $subcategory_){
                $subcategory[] = [
                    'id'=>$subcategory_->id,
                    'name'=>$subcategory_->name,
                ];
            }
            $all_categories[] = [
                'id'=>$category->id,
                'name'=>$category->name,
                'sub_category'=>$subcategory
            ];
        }
        if(count($all_categories)>0){
            $data = [$all_categories];
        }else{
            $data = [];
        }
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }

    public function getProductsByCategory(Request $request){
        $language = $request->header('language');
        $category = Category::find($request->category_id);
        $data = [];
        $products_data = [];
        $category_ = [];
        $subCategory = [];
        if(isset($category->id)){
            if($category->step == 0){
                $category_ = [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
                $subCategory = [];
            }elseif($category->step == 1){
                $category_ = [
                    'id' => $category->category->id,
                    'name' => $category->category->name,
                ];
                $subCategory = [
                    'id'=>$category->id,
                    'name'=>$category->name,
                ];
            }
            $products = Products::select('id', 'name', 'category_id', 'images', 'material_id', 'manufacturer_country', 'material_composition', 'price', 'description')->where('category_id', $category->id)->get();
        }else{
            $subCategory = [];
            $products = [];
            $category_= [];
        }
        foreach ($products as $product) {
            if(!is_array($product->images)){
                $images = json_decode($product->images);
            }
            foreach ($images as $image){
                if(!isset($image)){
                    $product_image = 'no';
                }else{
                    $product_image = $image;
                }
                $avatar_main = storage_path('app/public/products/'.$product_image);
                if(file_exists($avatar_main)){
                    $images_array[] = asset('storage/products/'.$image);
                }
            }

            $products_data[] = [
                'id'=>$product->id,
                'name'=>$product->name,
                'category_id'=>$product->category_id,
                'images'=>$images_array,
                'material_id'=>$product->material_id,
                'description'=>$product->description,
                'price'=>$product->price,
                'manufacturer_country'=>$product->manufacturer_country,
                'material_composition'=>$product->material_composition,
            ];
        }
        $data[] = [
            'category'=>$category_,
            'sub_category'=>$subCategory,
            'products'=>[
                $products_data
            ]
        ];
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }

    public function getProductsByCategories(Request $request){
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        $data = [];
        $products_data = [];
        $category_ = [];
        $subCategory = [];
        foreach ($categories as $category) {
            $subCategory = [];
            $category_products = Products::select('id', 'name', 'category_id', 'images', 'material_id', 'manufacturer_country', 'material_composition', 'price', 'description')->where('category_id', $category->id)->get();
            $category_products_data = $this->getProductsList($category_products);
            foreach ($category->subcategory as $subcategory_){
                $products = Products::select('id', 'name', 'category_id', 'images', 'material_id', 'manufacturer_country', 'material_composition', 'price', 'description')->where('category_id', $subcategory_->id)->get();
                $products_data = $this->getProductsList($products);
                $subCategory = [
                    'id' => $subcategory_->id,
                    'name' => $subcategory_->name,
                    'products'=>$products_data
                ];
            }
            $data[] = [
                'id' => $category->id,
                'name' => $category->name,
                'products'=>$category_products_data,
                'subcategory'=>$subCategory
            ];
        }

        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }
    public function getProductsList($products){
        $products_data = [];
        foreach ($products as $product) {
            if (!is_array($product->images)) {
                $images = json_decode($product->images);
            }
            foreach ($images as $image) {
                if (!isset($image)) {
                    $product_image = 'no';
                } else {
                    $product_image = $image;
                }
                $avatar_main = storage_path('app/public/products/' . $product_image);
                if (file_exists($avatar_main)) {
                    $images_array[] = asset('storage/products/' . $image);
                }
            }

            $products_data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'images' => $images_array,
                'material_id' => $product->material_id,
                'description' => $product->description,
                'price' => $product->price,
                'manufacturer_country' => $product->manufacturer_country,
                'material_composition' => $product->material_composition,
            ];
        }
        return $products_data;
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
