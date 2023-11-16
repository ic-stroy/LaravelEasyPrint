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
            if(!isset($banner->image)){
                $banner_image = 'no';
            }else{
                $banner_image = $banner->image;
            }
            $avatar_main = storage_path('app/public/banner/'.$banner_image);
            $banners[] = [
              'id'=>$banner->id,
              'title'=>$banner->title,
              'image'=>file_exists($avatar_main)?'storage/banner/'.$banner->image:null,
              'text'=>$banner->text,
              'is_active'=>$banner->is_active == 1 ? 'active':'no active',
            ];
        }
        $message = translate_api('success', $language);
        return $this->success($message, 200, $banners);
    }
}
