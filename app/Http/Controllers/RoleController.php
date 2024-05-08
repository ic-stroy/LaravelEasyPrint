<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Role;
use App\Models\RoleTranslations;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('created_at', 'desc')->get();
        return view('admin.role.index', ['roles'=> $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Role();
        $model->name = $request->name;
        $model->save();
        foreach (Language::all() as $language) {
            $role_translations = RoleTranslations::firstOrNew(['lang' => $language->code, 'role_id' => $model->id]);
            $role_translations->lang = $language->code;
            $role_translations->name = $model->name;
            $role_translations->role_id = $model->id;
            $role_translations->save();
        }
        return redirect()->route('role.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Role::find($id);
        return view('admin.role.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        return view('admin.role.edit', ['role'=> $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Role::find($id);
        if($request->name != $model->name) {
            foreach (Language::all() as $language) {
                $role_translations = RoleTranslations::firstOrNew(['lang' => $language->code, 'role_id' => $model->id]);
                $role_translations->lang = $language->code;
                $role_translations->name = $request->name;
                $role_translations->role_id = $model->id;
                $role_translations->save();
            }
        }
        $model->name = $request->name;
        $model->save();
        return redirect()->route('role.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Role::find($id);
        foreach (Language::all() as $language) {
            $role_translations = RoleTranslations::where(['lang' => $language->code, 'role_id' => $model->id])->get();
            foreach ($role_translations as $role_translation){
                $role_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('role.index')->with('status', translate('Successfully deleted'));
    }
}
