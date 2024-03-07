<?php

namespace App\Http\Controllers\Api;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Language;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Uploads;
use App\Models\Warehouse;
use App\Models\WarehouseTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $language = $request->header('language');
        $user = Auth::user();
        if(!in_array($user->role_id, [2, 3])){
            return $this->error(translate_api("You are not admin or manager", $language), 400);
        }
        $model = new Warehouse();
        $model->quantity = $request->quantity;
        if(!Color::where('id', $request->color_id)->exists()){
            return $this->error(translate_api('Color not found', $language), 400);
        }
        if(!Sizes::where('id', $request->size_id)->exists()){
            return $this->error(translate_api('Size not found', $language), 400);
        }
        if(isset($request->product_id)){
            if(!Products::where('id', $request->product_id)->exists()){
                return $this->error(translate_api('Product not found', $language), 400);
            }
        }
        $model->size_id = $request->size_id;
        $model->status = $request->status;
        $model->color_id = $request->color_id;
        $model->product_id = $request->product_id;
        $model->type = Constants::PRINT_TYPE;
        if ($request->name) {
            $model->name = $request->name;
        }
        $model->company_id = $user->company_id;
        if ($request->price) {
            $model->price = $request->price;
        }
        if ($request->description) {
            $model->description = $request->description;
        }
        if ($request->manufacturer_country) {
            $model->manufacturer_country = $request->manufacturer_country;
        }
        if ($request->material_composition) {
            $model->material_composition = $request->material_composition;
        }
        $model->material_id = $request->material_id;
        $image_front = $request->file('image_front');
        $image_back = $request->file('image_back');
        $model->image_front = $this->saveImage($image_front, 'warehouse');
        $model->image_back = $this->saveImage($image_back, 'warehouse');
        $images_print = $request->file('uploads');
        $model->save();

        if ($images_print) {
            foreach ($images_print as $image_print) {
                $uploads = new Uploads();
                $uploads->image = $this->saveImage($image_print, 'print');
                $uploads->relation_type = Constants::WAREHOUSE;
                $uploads->relation_id = $model->id;
                $uploads->save();
            }
        }

        foreach (Language::all() as $language) {
            $warehouse_translations = WarehouseTranslations::where(['lang' => $language->code, 'warehouse_id' => $model->id])->firstOrNew();
            $warehouse_translations->lang = $language->code;
            $warehouse_translations->name = $model->name;
            $warehouse_translations->warehouse_id = $model->id;
            $warehouse_translations->save();
        }
        $message = translate_api('Success', $language);
        return $this->success($message, 200);
    }

    public function saveImage($file, $url){
        if ($file) {
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/'.$url.'/', $image_name);
            return $image_name;
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $language = $request->header('language');
        $user = Auth::user();
        if(!in_array($user->role_id, [2, 3])){
            return $this->error(translate_api("You are not admin or manager", $language), 400);
        }
        if(!Warehouse::where('id', $request->id)->exists()){
            return $this->error(translate_api('Warehouse not found', $language), 400);
        }
        if(!Color::where('id', $request->color_id)->exists()){
            return $this->error(translate_api('Color not found', $language), 400);
        }
        if(!Sizes::where('id', $request->size_id)->exists()){
            return $this->error(translate_api('Size not found', $language), 400);
        }
        if(isset($request->product_id)){
            if(!Products::where('id', $request->product_id)->exists()){
                return $this->error(translate_api('Product not found', $language), 400);
            }
        }

        $model = Warehouse::find($request->id);
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->product_id = $request->product_id;
        $model->name = $request->name;
        $model->status = $request->status;
        $model->company_id = $user->company_id;
        $model->quantity = $request->quantity;
        $model->price = $request->price;
        $model->description = $request->description;
        $model->manufacturer_country = $request->manufacturer_country;
        $model->material_composition = $request->material_composition;
        $model->material_id = $request->material_id;

        if (!$model->image_front) {
            $model->image_front = 'no';
        }
        $model_image_front = storage_path('app/public/warehouse/'.$model->image_front);
        if (!$model->image_back) {
            $model->image_back = 'no';
        }
        $model_image_back = storage_path('app/public/warehouse/'.$model->image_back);
        if(file_exists($model_image_front)){
            unlink($model_image_front);
        }
        if(file_exists($model_image_back)){
            unlink($model_image_back);
        }

        $image_front = $request->file('image_front');
        $image_back = $request->file('image_back');
        $model->image_front = $this->saveImage($image_front, 'warehouse');
        $model->image_back = $this->saveImage($image_back, 'warehouse');
        $images_print = $request->file('uploads');
        $model->save();

        if ($images_print) {
            $uploads=Uploads::where('relation_type', Constants::WAREHOUSE)->where('relation_id', $model->id)->get();
            if (!empty($uploads)) {
                foreach ($uploads as $upload){
                    if (!$upload->image) {
                        $upload->image = 'no';
                    }
                    $order_detail_upload = storage_path('app/public/print/'.$upload->image);
                    if(file_exists($order_detail_upload)){
                        unlink($order_detail_upload);
                    }
                    $upload->delete();
                }
            }
            foreach ($images_print as $image_print) {
                $uploads = new Uploads();
                $uploads->image = $this->saveImage($image_print, 'print');
                $uploads->relation_type = Constants::WAREHOUSE;
                $uploads->relation_id = $model->id;
                $uploads->save();
            }
        }
        $message = translate_api('Successfully updated', $language);
        return $this->success($message, 200);
    }



    public function imageSave($warehouse, $images, $text)
    {
        if ($text == 'update') {
            if ($warehouse->images && !is_array($warehouse->images)) {
                $warehouse_images = json_decode($warehouse->images);
            } else {
                $warehouse_images = [];
            }
        } else {
            $warehouse_images = [];
        }
        if (isset($images)) {
            $WarehouseImage = [];
            foreach ($images as $image) {
                $random = $this->setRandom();
                $warehouse_image_name = $random . '' . date('Y-m-dh-i-s') . '.' . $image->extension();
                $image->storeAs('public/warehouses/', $warehouse_image_name);
                $WarehouseImage[] = $warehouse_image_name;
            }
            $all_warehouse_images = array_values(array_merge($warehouse_images, $WarehouseImage));
        }
        $warehouseImages = json_encode($all_warehouse_images ?? $warehouse_images);
        return $warehouseImages;
    }

    // backend json api

    public function getWarehousesByProduct(Request $request)
    {
        $user = Auth::user();
        $warehouses_ = Warehouse::where('product_id', $request->product_id)->where('company_id', $user->company_id)->get();
        $warehouses = [];
        foreach ($warehouses_ as $warehouse_) {
            $warehouses[] = [
                'id' => $warehouse_->id,
                'name' => isset($warehouse_->name) ? $warehouse_->name : $warehouse_->product->name,
                'color' => isset($warehouse_->color->name) ? $warehouse_->color->name : '',
                'size' => isset($warehouse_->size->name) ? $warehouse_->size->name : ''
            ];
        }
        return response()->json([
            'data' => $warehouses,
            'status' => true,
            'message' => 'Success'
        ]);
    }

    public function deleteWarehouseImage(Request $request)
    {
        $warehouse = Warehouse::find($request->id);
        if (isset($warehouse->images) && !is_array($warehouse->images)) {
            $warehouse_images_base = json_decode($warehouse->images);
        } else {
            $warehouse_images_base = [];
        }
        if (is_array($warehouse_images_base)) {
            if (isset($request->warehouse_name)) {
                $selected_warehouse_key = array_search($request->warehouse_name, $warehouse_images_base);
                $warehouse_main = storage_path('app/public/warehouses/' . $request->warehouse_name);
                if (file_exists($warehouse_main)) {
                    unlink($warehouse_main);
                }
                unset($warehouse_images_base[$selected_warehouse_key]);
            }
            $warehouse->images = json_encode(array_values($warehouse_images_base));
            $warehouse->save();
        }
        return response()->json([
            'status' => true,
            'message' => 'Success'
        ], 200);
    }

}
