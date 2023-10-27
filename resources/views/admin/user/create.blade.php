@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-14">
                {{__('User create')}}
            </p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('user.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('First name')}}</label>
                        <input type="text" class="form-control" name="first_name" required value="{{old('first_name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Last name')}}</label>
                        <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Middle name')}}</label>
                        <input type="text" class="form-control" name="middle_name" value="{{old('middle_name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Phone number')}}</label>
                        <input type="text" class="form-control" name="phone_number" value="{{old('phone_number')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Avatar')}}</label>
                        <input type="file" class="form-control" name="avatar" value="{{old('avatar')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="gender" class="form-label">{{__('Gender')}}</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="">{{__('Choose..')}}</option>
                            <option value="1" {{old('gender')==1??'selected'}}>{{__('Man')}}</option>
                            <option value="2" {{old('gender')==2??'selected'}}>{{__('Woman')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="role" class="form-label">{{__("Users' role")}}</label><br>
                        <select id="role_id" class="form-select" name="role_id">
                            <option value="">{{__('Choose..')}}</option>
                            <option value="0" {{old('role_id')==0??'selected'}}>{{__('Super admin')}}</option>
                            <option value="1" {{old('role_id')==1??'selected'}}>{{__('Admin')}}</option>
                            <option value="2" {{old('role_id')==2??'selected'}}>{{__('Manager')}}</option>
                            <option value="3" {{old('role_id')==2??'selected'}}>{{__('User')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Birth date')}}</label>
                        <input type="date" class="form-control" name="birth_date" value="{{old('birth_date')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="role" class="form-label">{{__("Select Company")}}</label><br>
                        <select id="role_id" class="form-select" name="company_id">
                            <option value="">{{__('Choose..')}}</option>
                            @foreach($companies as $company)
                                <option value="{{$company->id}}" {{old('address_id')==$company->id??'selected'}}>{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="address_id" class="form-label">{{__("Select address")}}</label><br>
                        <select id="address_id" class="form-select" name="address_id">
                            <option value="">{{__('Choose..')}}</option>
                            @foreach($addresses as $address)
                                <option value="{{$address->id}}" {{old('address_id')==$address->id??'selected'}}>{{$address->region ?? " " }} {{$address->district ?? " " }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Login')}}</label>
                    <input type="email" class="form-control" name="email" required value="{{old('login')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Password')}}</label>
                    <input type="password" class="form-control" name="password" required value="{{old('password')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Password confirmation')}}</label>
                    <input type="password" class="form-control" name="password_confirmation" required value="{{old('password_confirmation')}}"/>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
