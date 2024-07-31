<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cities;
use App\Models\Color;
use App\Models\ColorTranslations;
use App\Models\Company;
use App\Models\CompanyTranslations;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->get();
        return view('admin.company.index', ['companies'=> $companies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Company();
        if($request->district || $request->address_name || $request->postcode) {
            $address = new Address();
            $address->city_id = $request->district;
            $address->latitude = $request->address_lat;
            $address->longitude = $request->address_long;
            $address->name = $request->address_name;
            $address->postcode = $request->postcode;
            $address->save();
            $model->address_id = $address->id;
        }
        $file = $request->file('company_image');
        $this->imageSave($file, $model, 'store');
        $model->name = $request->name;
        $model->delivery_price = $request->delivery_price;
        $model->save();
        foreach (Language::all() as $language) {
            $company_translations = CompanyTranslations::firstOrNew(['lang' => $language->code, 'company_id' => $model->id]);
            $company_translations->lang = $language->code;
            $company_translations->name = $model->name;
            $company_translations->company_id = $model->id;
            $company_translations->save();
        }
        return redirect()->route('company.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Company::find($id);
        return view('admin.company.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::find($id);
        return view('admin.company.edit', ['company'=> $company]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Company::find($id);
        if($request->district || $request->address_name || $request->postcode) {
            if ($model->address) {
                $address = $model->address;
            } else {
                $address = new Address();
            }
            $address->city_id = $request->district;
            $address->name = $request->address_name;
            $address->postcode = $request->postcode;
            $address->latitude = $request->address_lat;
            $address->longitude = $request->address_long;
            $address->save();
            $model->address_id = $address->id;
        }
        if($request->name != $model->name){
            foreach (Language::all() as $language) {
                $company_translations = CompanyTranslations::firstOrNew(['lang' => $language->code, 'company_id' => $model->id]);
                $company_translations->lang = $language->code;
                $company_translations->name = $request->name;
                $company_translations->company_id = $model->id;
                $company_translations->save();
            }
        }

        $file = $request->file('company_image');
        $this->imageSave($file, $model, 'update');
        $model->name = $request->name;
        $model->delivery_price = $request->delivery_price;
        $model->save();
        return redirect()->route('company.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Company::find($id);
        if($model->warehouse){
            return redirect()->back()->with('error', translate('You cannot delete this company because here is product associated with this company.'));
        }
        $address = $model->address;
        foreach (Language::all() as $language) {
            $company_translations = CompanyTranslations::where(['lang' => $language->code, 'company_id' => $model->id])->get();
            foreach ($company_translations as $company_translation){
                $company_translation->delete();
            }
        }
        $model->delete();
        if($address){
            $address->delete();
        }
        return redirect()->route('company.index')->with('status', translate('Successfully deleted'));
    }

    public function imageSave($file, $model, $text){
        if (isset($file)) {
            $letters = range('a', 'z');
            $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
            $random = implode("", $random_array);

            if($text == 'update'){
                if($model->image){
                    $sms_avatar = storage_path('app/public/company/' . $model->image);
                }else{
                    $sms_avatar = storage_path('app/public/company/' . 'no');
                }
                if (file_exists($sms_avatar)) {
                    unlink($sms_avatar);
                }
            }
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/company/', $image_name);
            $model->image = $image_name;

            return $model;
        }
    }


}
