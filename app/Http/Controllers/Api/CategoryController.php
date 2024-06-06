<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Products;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function getCategories(Request $request){
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        foreach ($categories as $category){
            $translate_category_name = table_translate($category,'category',$language);
            $subcategory = [];
            foreach ($category->subcategory as $subcategory_){
                $translate_subcategory_name=table_translate($subcategory_,'category',$language);
                $subcategory[] = [
                    'id'=>$subcategory_->id,
                    'name'=>$translate_subcategory_name,
                ];
            }
            $all_categories[] = [
                'id'=>$category->id,
                'name'=>$translate_category_name,
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

    public function getProductsByCategory(Request $request)
    {
        $language = $request->header('language');
        $category = Category::find($request->category_id);
        $data = [];
        $products_data = [];
        $category_ = [];
        $subCategory = [];
        $categories_id = [];
        $productsId = [];
        $category_active = null;
        if ($category) {
            $translate_category_name=table_translate($category,'category', $language);


            $product_default = Products::where('category_id', $category->id)->whereIn('name', ['Футболка', 'Свитшот', 'Худи'])->first();
            $productDefault = [];
            if($product_default){
                $productDefault[] = [
                    'id' => $product_default->id,
                    'name' => $product_default->name,
                    'category_id' => $product_default->category_id,
                    'price' => $product_default->price,
                    'discount' => $product_default->discount ? $product_default->discount->percent : NULL,
                    'price_discount' => $product_default->discount ? $product_default->price - ($product_default->price / 100 * $product_default->discount->percent) : NULL,
                    'images' => $this->getImages($product_default, 'product'),
                    'material_id' => $product_default->material_id,
                    'description' => $product_default->description,
                    'manufacturer_country' => $product_default->manufacturer_country,
                    'material_composition' => $product_default->material_composition,
                ];
            }

            if ($category->step == 0) {
                $category_active = true;
                // $translate_category_name=table_translate($warehouse_product,'category',$language);
                $category_ = [
                    'id' => $category->id,
                    'name' => $translate_category_name,
                ];
                $categories_id[] = $category->id;
                foreach($category->subcategory as $sub_category){
                    $translate_sub_category_name=table_translate($sub_category,'category', $language);
                    $subCategory[] = [
                        'id' => $sub_category->id,
                        'name' => $translate_sub_category_name,
                    ];
                    $categories_id[] = $sub_category->id;
                }
            } elseif ($category->step == 1) {
                $category_active = false;
                $translate_category_name=table_translate($category->category,'category',$language);
                $category_ = [
                    'id' => $category->category->id,
                    'name' => $translate_category_name,
                ];
                $categories_id[] = $category->category->id;
                $translate_sub_category_name=table_translate($category,'category',$language);
                $subCategory[] = [
                    'id' => $category->id,
                    'name' => $translate_sub_category_name,
                ];
                $categories_id[] = $category->id;
            }

            $products = Products::select('id', 'name', 'category_id', 'images', 'material_id', 'manufacturer_country', 'material_composition', 'price', 'description')->with('discount')->whereIn('category_id', $categories_id)->get();
            $productsId = Products::whereIn('category_id', $categories_id)->pluck('id')->all();
        } else {
            $subCategory = [];
            $products = [];
            $category_= [];
        }
        foreach ($products as $product) {
            $images_array = [];
            if (!is_array($product->images)) {
                $images = json_decode($product->images);
            }
            foreach ($images as $image) {
                if (!$image) {
                    $product_image = 'no';
                } else {
                    $product_image = $image;
                }

                $avatar_main = storage_path('app/public/products/' . $product_image);
                if (file_exists($avatar_main)) {
                    $images_array[] = asset('storage/products/' . $image);
                }
            }

            $translate_product_name=table_translate($product,'product', $language);

            $products_data[] = [
                'id' => $product->id,
                'name' => $translate_product_name,
                'category_id' => $product->category_id,
                'images' => $images_array,
                'material_id' => $product->material_id,
                'description' => $product->description,
                'price' => $product->price,
                'discount' => (isset($product->discount)) > 0 ? $product->discount->percent : NULL,
                'price_discount' => (isset($product->discount)) > 0 ? $product->price - ($product->price / 100 * $product->discount->percent) : NULL,
                'manufacturer_country' => $product->manufacturer_country,
                'material_composition' => $product->material_composition,
            ];
        }

        $warehouse_products_ = Warehouse::whereIn('product_id', $productsId)->distinct('product_id')->get();
        $warehouse_products = [];

        foreach ($warehouse_products_ as $warehouse_product_) {

            if($warehouse_product_->type == 0){
                if (count($this->getImages($warehouse_product_, 'warehouse'))>0) {
                    $warehouseProducts = $this->getImages($warehouse_product_, 'warehouse');
                } else {
                    $parentProduct = Products::find($warehouse_product_->product_id);
                    if($parentProduct){
                        $warehouseProducts = $this->getImages($parentProduct, 'product');
                    }
                }
            }else{
                $warehouseProducts = [];
                if (!$warehouse_product_->image_front) {
                    $warehouse_product_->image_front = 'no';
                }
                $model_image_front = storage_path('app/public/warehouse/'.$warehouse_product_->image_front);
                if (!$warehouse_product_->image_back) {
                    $warehouse_product_->image_back = 'no';
                }
                $model_image_back = storage_path('app/public/warehouse/'.$warehouse_product_->image_back);
                if(file_exists($model_image_front)){
                    $warehouseProducts[] = asset("/storage/warehouse/$warehouse_product_->image_front");
                }
                if(file_exists($model_image_back)){
                    $warehouseProducts[] = asset("/storage/warehouse/$warehouse_product_->image_back");
                }
            }

            $translate_name=table_translate($warehouse_product_,'warehouse_category', $language);
            if($warehouse_product_->product_discount){
                $discount = $warehouse_product_->product_discount->percent;
                $price_discount = $warehouse_product_->price - ($warehouse_product_->price / 100 * $warehouse_product_->product_discount->percent);
            }elseif($warehouse_product_->discount){
                $discount = $warehouse_product_->discount->percent;
                $price_discount = $warehouse_product_->price - ($warehouse_product_->price / 100 * $warehouse_product_->discount->percent);
            }
            if(!empty($warehouse_product_->product)){
                $translate_product_name=table_translate($warehouse_product_->product,'product', $language);
            }
            //  join qilish kere
            $warehouse_products[] = [
                'id' => $warehouse_product_->id,
                'name' => $translate_name ?? $translate_product_name,
                'price' => $warehouse_product_->price,
                'category_id' => !empty($warehouse_product_->product)?$warehouse_product_->product->category_id:'',
                'material_id' => !empty($warehouse_product_->product)?$warehouse_product_->product->material_id:'',
                'description' => !empty($warehouse_product_->product)?$warehouse_product_->product->description:'',
                'manufacturer_country' => !empty($warehouse_product_->product)?$warehouse_product_->product->manufacturer_country:'',
                'material_composition' => !empty($warehouse_product_->product)?$warehouse_product_->product->material_composition:'',
                'discount' => $discount??NULL,
                'price_discount' => $price_discount??NULL,
                'images' => $warehouseProducts,
                'product_id' => $warehouse_product_->product_id,
            ];
        }
        $products_data = array_merge($productDefault, $products_data);
        $data[] = [
            'category_active'=>$category_active,
            'category' => $category_,
            'sub_category' => $subCategory,
            'products' => $warehouse_products,
            'products_data' => $products_data
        ];

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
                if ($image) {
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
        $token = $request->header('token');
        $languages = Language::select('id', 'name', 'code')->get();
        $options = [
            'headers'=>[
                'Accept'        => 'application/json',
                'Authorization' => "Bearer $token"
            ]
        ];
        if(isset($token) && $token){
            $client = new \GuzzleHttp\Client();
            $url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'];
            $guzzle_request = new GuzzleRequest('GET', $url.'/api/user-info');
            try{
                $res = $client->sendAsync($guzzle_request, $options)->wait();
                $result = $res->getBody();
                $result = json_decode($result);
                $basket_quantity = $result->basket_quantity??0;
                $profile = [
                    $result->name??null,
                    $result->avatar??null,
                ];
            }catch (\Exception $e){
                $basket_quantity = 0;
                $profile = [];
            }
        }else{
            $basket_quantity = 0;
            $profile = [];
        }

        $data = [
            'language'=>$languages,
            'basket_count'=>$basket_quantity,
            'profile'=>$profile
        ];
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $data);
    }

    public function userInfo(){
        $user = Auth::user();
        if($user->orderBasket){
            $basket_quantity = $user->orderBasket->orderDetail?count($user->orderBasket->orderDetail):0;
        }else{
            $basket_quantity = 0;
        }
        $personalInfo = $user->personalInfo?$user->personalInfo:[];
        if($user->personalInfo){
            if($user->personalInfo->avatar){
                $sms_avatar = storage_path('app/public/user/'.$user->personalInfo->avatar);
            }else{
                $sms_avatar = 'no';
            }
        }else{
            $sms_avatar = 'no';
        }
        $profile = [
            'name'=>$personalInfo->first_name?$personalInfo->first_name:null,
            'avatar'=>file_exists($sms_avatar)?asset('storage/user/'.$personalInfo->avatar):asset('assets/images/man.jpg'),
            'basket_quantity'=>$basket_quantity
        ];
        return response()->json($profile);
    }

    public function getImages($model, $text)
    {
        if ($model->images) {
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_) {
                if ($text == 'warehouse') {
                    $images[] = asset('storage/warehouses/'.$image_);
                } elseif ($text == 'product') {
                    $images[] = asset('storage/products/'.$image_);
                }
            }
        } else {
            $images = [];
        }

        return $images;
    }
}
