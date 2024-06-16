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
        translate('Your password is incorrect');
        translate('Your new password confirmation is incorrect');
        translate('You cannot delete this category because here is product associated with this size.');
        translate('You cannot delete this size because here is product associated with this size.');
        translate('You cannot delete this role because here is user associated with this role.');
        translate('You cannot delete this product because here is some products in warehouse');
        translate('You cannot delete this company because here is product associated with this company.');
        translate('You cannot delete this color because here is product associated with this color.');
        translate('You cannot delete this category because it has subcategories');
        translate('You cannot delete this category because it has products');
        translate('You cannot delete this product because here is product associated with an order.');
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
        return redirect()->route('banner.index')->with('status', translate('Successfully created'));
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
        return redirect()->route('banner.index')->with('status', translate('Successfully updated'));
    }

    public function imageSave($file, $banner, $carusel_images, $text){
        if($text == 'update'){
            if($banner->image && !is_array($banner->image)){
                $banner_images = json_decode($banner->image);
                $banner_image_name = $banner_images->banner;
                $carousel_image_names = $banner_images->carousel;
            }else{
                $banner_images = [];
                $banner_image_name = '';
                $carousel_image_names = [];
            }
            if($file && !is_array($banner_images)){
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

        }
        if($file){
            $random = $this->setRandom();
            $banner_name = $random . '' . date('Y-m-dh-i-s') . '.' . $file->extension();
            $file->storeAs('public/banner/', $banner_name);
        }
        if(isset($carusel_images)){
            $carouselImage = [];
            if(!isset($banner_images->carousel)){
                $carousel_images_base = [];
            }else{
                $carousel_images_base = $banner_images->carousel;
            }
            foreach($carusel_images as $carusel_image){
                $random = $this->setRandom();
                $carusel_image_name = $random.''.date('Y-m-dh-i-s').'.'.$carusel_image->extension();
                $carusel_image->storeAs('public/banner/carousel/', $carusel_image_name);
                $carouselImage[] = $carusel_image_name;
            }
            $all_carousel_images = array_values(array_merge($carousel_images_base, $carouselImage));
        }
        $banner = json_encode(['banner'=>$banner_name??$banner_image_name, 'carousel'=>$all_carousel_images??$carousel_image_names]);
        return $banner;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);
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
            $avatar_main = storage_path('app/public/banner/'.$banner_image);
            if(file_exists($avatar_main)){
                unlink($avatar_main);
            }
        }
        if(!is_array($banner_images)){
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
        $banner->delete();
        return redirect()->route('banner.index')->with('status', translate('Successfully deleted'));
    }

}
