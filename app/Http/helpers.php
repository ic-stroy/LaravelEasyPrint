<?php

use App\Models\Translation;
use App\Models\Language;
// use Modules\ForTheBuilder\Entities\Language;
use Illuminate\Support\Facades\App;


if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE", 'ru');
    }
}
if (!function_exists('translate')) {
    function translate($key, $lang = null)
    {

        if ($lang === null) {
            $lang = App::getLocale();
            // dd($lang);
        }
        // $lang = App::getLocale();
        // dd($lang);
    //  dd($key);
    // dd($lang);
        // $app = $key.$lang . va($lang);


        // $function = function () use ($key, $lang) {

            // dd($lang);
            // dd()
            $translate = Translation::where('lang_key', $key)
                ->where('lang', $lang)
                ->first();
                // dd($translate);
            if ($translate === null){
                // dd($translate);
                foreach (Language::all() as $language) {
                    if(!Translation::where('lang_key', $key)->where('lang', $language->code)->exists()){
                        Translation::create([
                            'lang'=>$language->code,
                            'lang_key'=> $key,
                            'lang_value'=>$key
                        ]);
                    }
                }
                // dd($translate);
                $data = $key;
            }else{
                $data = $translate->lang_value;
            }

            return $data;
        // };

        // return tkram(Translation::class, $app, $function);
    }
}

if (!function_exists('translate_api')) {
    function translate_api($key, $lang = null)
    {

        if ($lang === null) {
            $lang = App::getLocale();
        }
        // dd($lang);

            $translate = Translation::where('lang_key', $key)
                ->where('lang', $lang)
                ->first();
                // dd($translate);
            if ($translate === null){
                // dd($translate);
                foreach (Language::all() as $language) {
                    if(!Translation::where('lang_key', $key)->where('lang', $language->code)->exists()){
                        Translation::create([
                            'lang'=>$language->code,
                            'lang_key'=> $key,
                            'lang_value'=>$key
                        ]);
                    }
                }
                // dd($translate);
                $data = $key;
            }else{
                $data = $translate->lang_value;
            }

            return $data;
        // };

        // return tkram(Translation::class, $app, $function);
    }
}
if (!function_exists('table_translate')) {
    function table_translate($key, $type, $lang)
    {
        switch ($type) {
            case 'product':
                if ($product_translation=DB::table('product_translations')->where('product_id',$key->id)->where('lang',$lang)->first()) {
                    return $product_translation->name;
                }else {
                    return $key->name;
                }
                break;

            case 'warehouse':
                if ($warehouse_translation=DB::table('warehouse_translations')->where('warehouse_id',$key->warehouse_product_id)->where('lang',$lang)->first()) {
                    return $warehouse_translation->warehouse_product_name;
                }else {
                    return $key->warehouse_product_name;
                }
                break;
            case 'category':
                if ($category_translations=DB::table('category_translations')->where('category_id',$key->id)->where('lang',$lang)->first()) {
                    return $category_translations->name;
                }else {
                    return $key->name;
                }
                break;
            case 'color':
                if ($color_translations=DB::table('color_translations')->where('color_id',$key->id)->where('lang',$lang)->first()) {
                    return $color_translations->name;
                }else {
                    return $key->name;
                }
                break;
            default:
                break;
        }


    }
}


