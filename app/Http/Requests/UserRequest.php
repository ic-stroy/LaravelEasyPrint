<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
//    public function rules(): array
//    {
//        return [
//            'first_name' => 'required|string',
//            'last_name' => 'nullable|string',
//            'middle_name' => 'nullable|string',
//            'phone_number' => 'nullable|string',
//            'gender' => 'nullable|integer',
//            'birth_date' => 'nullable|date',
//            'email' => 'required|string|unique:users',
//            'password' => 'required|string|min:8|confirmed',
//            'role_id' => 'required|integer',
//            'company_id' => 'nullable|integer',
//            'avatar' => 'nullable|mimes:jpeg,jpg,png,webp|max:10240',
//            'district' => 'nullable|string',
//            'address_name' => 'nullable|string',
//            'address_lat' => 'nullable|string',
//            'address_long' => 'nullable|string',
//        ];
//    }

    public function store()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'middle_name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'gender' => 'nullable|integer',
            'birth_date' => 'nullable|date',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer',
            'company_id' => 'nullable|integer',
            'avatar' => 'nullable|mimes:jpeg,jpg,png,webp|max:10240',
            'district' => 'nullable|string',
            'address_name' => 'nullable|string',
            'address_lat' => 'nullable|string',
            'address_long' => 'nullable|string',
        ];
    }
//
    public function update()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'middle_name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'gender' => 'nullable|integer',
            'birth_date' => 'nullable|date',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Route::current()->parameters()['user'])],
            'password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8',
            'new_password_confirmation' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|integer',
            'company_id' => 'nullable|integer',
            'avatar' => 'nullable|mimes:jpeg,jpg,png,webp|max:10240',
            'district' => 'nullable|string',
            'address_name' => 'nullable|string',
            'address_lat' => 'nullable|string',
            'address_long' => 'nullable|string',
        ];
    }
}
