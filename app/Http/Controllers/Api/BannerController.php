<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function getBanner(Request $request){
        $language = $request->header('language');
        $all_banners = Banner::all();
        $banners = [];
        foreach ($all_banners as $banner){
            if($banner->image && !is_array($banner->image)){
                $banner_images = json_decode($banner->image);
            }else{
                $banner_images = [];
            }
            if(!isset($banner_images->banner)){
                $banner_image = 'no';
            }else{
                $banner_image = $banner_images->banner;
            }
            $avatar_main = storage_path('app/public/banner/'.$banner_image);
            if(file_exists($avatar_main)){
                $bannerImage = asset('storage/banner/'.$banner_image);
            }else{
                $bannerImage = null;
            }

            if(!isset($banner_images->carousel) && count($banner_images->carousel)>0){
                $carousel_images[] = 'no';
            }else{
                $carousel_images = $banner_images->carousel;
            }
            $carouselImage = [];
            foreach($carousel_images as $carousel_image){
                if(file_exists(storage_path('app/public/banner/carousel/'.$carousel_image))){
                    $carouselImage[] = asset('storage/banner/carousel/'.$carousel_image);
                }
            }
            $banner_title = translate_api($banner->title, $language);
            $banner_text = translate_api($banner->text, $language);

            $banners[] = [
              'id'=>$banner->id,
              'title'=>$banner_title,
              'banner_image'=>$bannerImage,
              'carousel_image'=>$carouselImage,
              'text'=>$banner_text,
              'is_active'=>$banner->is_active == 1 ? 'active':'no active',
            ];
        }
        $message = translate_api('success', $language);
        return $this->success($message, 200, $banners);
    }

    public function deleteCarousel(Request $request){
        $banner = Banner::find($request->id);
        if($banner->image && !is_array($banner->image)){
            $banner_images = json_decode($banner->image);
        }else{
            $banner_images = [];
        }
        if(!is_array($banner_images)){
            if(!isset($banner_images->banner) || $banner_images->banner == ""){
                $banner_image = 'no';
            }else{
                $banner_image = $banner_images->banner;
            }
            if(!isset($banner_images->carousel) && count($banner_images->carousel) == 0){
                $carousel_images_base[] = 'no';
            }else{
                $carousel_images_base = $banner_images->carousel;
            }
            if(isset($request->carousel_name)){
                $selected_carousel_key = array_search($request->carousel_name, $carousel_images_base);
                $carousel_main = storage_path('app/public/banner/carousel/'.$request->carousel_name);
                if(file_exists($carousel_main)){
                    unlink($carousel_main);
                }
                unset($carousel_images_base[$selected_carousel_key]);
            }
            $banner->image = json_encode([
                'banner'=>$banner_image,
                'carousel'=>array_values($carousel_images_base)
            ]);
            $banner->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>'Success'
        ], 200);
    }
}
