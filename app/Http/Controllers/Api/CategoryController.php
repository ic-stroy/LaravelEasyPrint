<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function getSubcategory($id)
    {
        $model = Category::where('parent_id', $id)->get();
        if(isset($model) && count($model)>0){
            return response()->json([
                'status'=>true,
                'data'=>$model
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'data'=>[]
            ]);
        }

    }

}
