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
            if(isset($banner->image) && !is_array($banner->image)){
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

            $banners[] = [
              'id'=>$banner->id,
              'title'=>$banner->title,
              'banner_image'=>$bannerImage,
              'carousel_image'=>$carouselImage,
              'text'=>$banner->text,
              'is_active'=>$banner->is_active == 1 ? 'active':'no active',
            ];
        }
        $message = translate_api('success', $language);
        return $this->success($message, 200, $banners);
    }
}
