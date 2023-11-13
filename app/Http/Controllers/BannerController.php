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
        $this->imageSave($file, $banner, 'store');
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
        $this->imageSave($file, $model, 'update');
        $model->save();
        return redirect()->route('banner.index')->with('status', __('Successfully updated'));
    }

    public function imageSave($file, $banner, $text){
        if (isset($file)) {
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);
            if($text == 'update'){
                if(isset($banner->image)){
                    $sms_avatar = storage_path('app/public/banner/' . $banner->image);
                }else{
                    $sms_avatar = storage_path('app/public/banner/' . 'no');
                }
                if(file_exists($sms_avatar)) {
                    unlink($sms_avatar);
                }
            }
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/banner/', $image_name);
            $banner->image = $image_name;

            return $banner;
        }
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
