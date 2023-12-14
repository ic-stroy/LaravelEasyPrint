<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Translation;
use App\Models\CityTranslations;
use App\Models\ClassTranslations;
use App\Models\ColorTranslations;
use App\Models\CountryTranslations;
use App\Models\OptionTranslations;
use App\Models\RoleTranslations;
use App\Models\StaffTranslations;
use App\Models\StatusTranslations;
use Illuminate\Support\Facades\DB;


class TableTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('language.tables');
    }
    
    public function show($type){
        $languages = Language::orderBy('id', 'ASC')->get();
        return view('language.table_lang', ['type'=>$type, 'languages'=>$languages]);
    }
    
    public function tableShow(Request $request ){
        // dd($request->all());
        $type=$request->type;
        $id=$request->language_id;
        $language = Language::findOrFail($id);
       // $lang_keys = Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
        $sort_search = null;
        switch ($type){
            case 'city':
                $lang_keys = CityTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'class':
                $lang_keys = ClassTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'color':
                $lang_keys = ColorTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'country':
                $lang_keys = CountryTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                // dd($lang_keys);
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'option':
                $lang_keys = OptionTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'role':
                $lang_keys = RoleTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'status':
                $lang_keys = StatusTranslations::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            default:
        }
    }


    public function translation_save(Request $request)
    {
        // dd($request->all());
        switch ($request->type){
            case 'city':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($value);
                    $translation_def = CityTranslations::where('city_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    } 
                }
        
                return back();
                break;
            case 'class':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($value);
                    $translation_def = ClassTranslations::where('class_list_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    } 
                }
        
                return back();
                break;
            case 'color':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($key);
                    $translation_def = ColorTranslations::where('color_list_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    } 
                }
        
                return back();
                break;
            case 'country':
                // dd($request->all());
                $language = Language::findOrFail($request->id);
                // dd($language);
                foreach ($request->values as $key => $value) {
                    // dd($key);
                    $translation_def = CountryTranslations::where('country_id', $key)->where('lang', $language->code)->first();
                    // dd($translation_def);
                    // dd($translation_def);    
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    } 
                }
        
                return back();
                break;
            case 'option':
                $language = Language::findOrFail($request->id);
                    foreach ($request->values as $key => $value) {
                        // dd($value);
                        $translation_def = OptionTranslations::where('option_id', $key)->where('lang', $language->code)->first();
                        if ($translation_def) {
                            $translation_def->name = $value;
                            $translation_def->save();
                        } 
                    }
            
                    return back();
                    break;
            case 'role':
                    $language = Language::findOrFail($request->id);
                    foreach ($request->values as $key => $value) {
                    // dd($value);
                        $translation_def = RoleTranslations::where('role_id', $key)->where('lang', $language->code)->first();
                        if ($translation_def) {
                            $translation_def->name = $value;
                            $translation_def->save();
                        } 
                }
        
                return back();
                break;
            case 'staff':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($value);
                    $translation_def = StaffTranslations::where('staff_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    } 
                }
        
                return back();
                break;
            case 'status':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($value);
                    $translation_def = StatusTranslations::where('status_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    } 
                }
        
                return back();
                break;
            
            default:
        }
        
    }
}
