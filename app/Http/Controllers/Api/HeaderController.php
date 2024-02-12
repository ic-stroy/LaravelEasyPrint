<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Images;
use App\Models\Advertising;


class HeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $language=$request->language;
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        $categories=Category::pluck('id','name');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getImage(Request $request)
    {
        $language = $request->header('language');
        $images = Images::get();

        $response = [];
        if (count($images) > 0) {
            foreach ($images as $key => $value) {
                $response[] =  asset('storage/images/'.$value->name) ;
                
            }
            $message=translate_api('Success',$language);
            return $this->success($message, 200, $response);
        }
        else{
            $message=translate_api('Images not found',$language);
            return $this->error($message, 500);
        }
    }

    public function getAdvertising(Request $request)
    {
        $language = $request->header('language');
        $response = [];
        $ads = Advertising::get();

         if (count($ads) > 0) {
            foreach ($ads as $key => $value) {
                $response[] = [
                    'title' => $value->title,
                    'url' => $value->url,
                    'image' =>  asset('storage/images/'.$value->image)
                ] ;
                
            }

            $message=translate_api('Success',$language);
            return $this->success($message, 200, $response);
        }
        else{
            $message=translate_api('Images not found',$language);
            return $this->error($message, 500);
        }
    }


}
