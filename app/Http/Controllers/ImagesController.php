<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImagesController extends Controller
{
    public function index(){
        $images = Images::all();
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
        $image = $request->file('image');
        if ($image) {
            $random = $this->setRandom();
            $product_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
            $image->storeAs('public/images/', $product_image_name);
            $model->name = $product_image_name;
            $model->save();
        }

        return redirect()->route('images.index')->with('status', translate('Successfully created'));
    }

    public function edit($id)
    {
        $image = Images::find($id);
        return view('admin.images.edit', ['image'=>$image]);
    }

    public function show($id)
    {
        $image = Images::find($id);
        return view('admin.images.show', ['image'=>$image]);
    }

    public function update(Request $request, $id)
    {
        $model = Images::find($id);
        $image = $request->file('image');
        if ($image) {
            if($model->name) {
                if($model->name){
                    $model->name = $model->name;
                }else{
                    $model->name = 'no';
                }
                $model_image = storage_path('app/public/images/'.$model->name);
            } else {
                $model_image = 'no';
            }
            if (file_exists($model_image)) {
                unlink($model_image);
            }
            $random = $this->setRandom();
            $product_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
            $image->storeAs('public/images/', $product_image_name);
            $model->name = $product_image_name;
            $model->save();
        }

        return redirect()->route('images.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Images::find($id);
        if($model){
            if($model->name) {
                if($model->name){
                    $model->name = $model->name;
                }else{
                    $model->name = 'no';
                }
                $model_image = storage_path('app/public/images/'.$model->name);
            } else {
                $model_image = 'no';
            }
            if (file_exists($model_image)) {
                unlink($model_image);
            }
            $model->delete();
            return redirect()->route('images.index')->with('status', translate('Successfully deleted'));
        }else{
            return redirect()->route('images.index')->with('error', translate('Image not found'));
        }
    }

}
