<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('created_at', 'desc')->get();
        return view('admin.banner.index', [
            'banners' => $banners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->text = $request->text;
        $banner->is_active = $request->is_active;
        $file = $request->file('image');
        $carusel_images = $request->file('carusel_images');
        $banner->image = $this->imageSave($file, $banner, $carusel_images, 'store');
        $banner->save();
        return redirect()->route('banner.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Banner::find($id);
        return view('admin.banner.show', [
            'model' => $model
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::find($id);
        return view('admin.banner.edit', [
            'banner' => $banner
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Banner::find($id);
        $model->title = $request->title;
        $model->text = $request->text;
        $model->is_active = $request->is_active;
        $file = $request->file('image');
        $carusel_images = $request->file('carusel_images');
        $model->image = $this->imageSave($file, $model, $carusel_images, 'update');
        $model->save();
        return redirect()->route('banner.index')->with('status', __('Successfully updated'));
    }

    public function setRandom(){
        $letters = range('a', 'z');
        $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
        $random = implode("", $random_array);
        return $random;
    }

    public function imageSave($file, $banner, $carusel_images, $text){

        if($text == 'update'){
            if(isset($banner->image) && !is_array($banner->image)){
                $banner_images = json_decode($banner->image);
                $banner_image_name = $banner_images->banner;
                $carousel_image_names = $banner_images->carousel;
            }else{
                $banner_images = [];
                $banner_image_name = '';
                $carousel_image_names = [];
            }
            if(isset($file) && !is_array($banner_images)){
                if(!isset($banner_images->banner) || $banner_images->banner == ""){
                    $banner_image = 'no';
                }else{
                    $banner_image = $banner_images->banner;
                }
                $avatar_main = storage_path('app/public/banner/'.$banner_image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
            if(isset($carusel_images) && !is_array($banner_images)){
                if(!isset($banner_images->carousel) && count($banner_images->carousel) == 0){
                    $carousel_images_base[] = 'no';
                }else{
                    $carousel_images_base = $banner_images->carousel;
                }
                foreach ($carousel_images_base as $carousel_image_base){
                    $carousel_main = storage_path('app/public/banner/carousel/'.$carousel_image_base);
                    if(file_exists($carousel_main)){
                        unlink($carousel_main);
                    }
                }
            }
        }
        if(isset($file)) {
            $random = $this->setRandom();
            $banner_name = $random . '' . date('Y-m-dh-i-s') . '.' . $file->extension();
            $file->storeAs('public/banner/', $banner_name);
        }
        if(isset($carusel_images)){
            foreach ($carusel_images as $carusel_image){
                $random = $this->setRandom();
                $carusel_image_name = $random.''.date('Y-m-dh-i-s').'.'.$carusel_image->extension();
                $carusel_image->storeAs('public/banner/carousel/', $carusel_image_name);
                $carouselImage[] = $carusel_image_name;
            }
        }
        if($text == 'update'){
            $banner = json_encode(['banner'=>$banner_name??$banner_image_name, 'carousel'=>$carouselImage??$carousel_image_names]);
        }elseif($text == 'store'){
            $banner = json_encode(['banner'=>$banner_name??$banner_image_name, 'carousel'=>$carouselImage??$carousel_image_names]);
        }
        return $banner;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Banner::find($id);
        if(isset($model->image)) {
            $sms_avatar = storage_path('app/public/banner/'.$model->image);
        } else {
            $sms_avatar = 'no';
        }
        if (file_exists($sms_avatar)) {
            unlink($sms_avatar);
        }
        $model->delete();
        return redirect()->route('banner.index')->with('status', __('Successfully deleted'));
    }
}
