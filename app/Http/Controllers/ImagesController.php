<?php

namespace App\Http\Controllers;

use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImagesController extends Controller
{
    public function index(){
        $images = Images::get();
        return view('admin.images.index', [
            'images'=>$images
        ]);
    }

    public function create()
    {
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $model = new Images();
        $images = $request->file('images');
        $product_image_name = '';
        $model->name = $this->imageSave($images);
        if (!empty($images)) {
            foreach($images as $image){
                $random = $this->setRandom();
                $product_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
                $image->storeAs('public/images/', $product_image_name);
            }
        }
        $model->name = $product_image_name;
        $model->save();

        return redirect()->route('images.index')->with('status', translate('Successfully created'));
    }

   public function destroy(Request $request)
    {
        print_r($id);
        die();
        $model = Images::find($id);
        if($model->images){
            $images = json_decode($model->images);
            foreach ($images as $image){
                $avatar_main = storage_path('app/public/images/'.$image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
        }
        $model->delete();
        return redirect()->route('images.index')->with('status', translate('Successfully deleted'));
    }
    

}
