@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <style>
        .google_maps{
            height: 400px;
            width: 100%;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-14">
                {{__('User edit')}}
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
            <form action="{{route('user.update', $user->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('First name')}}</label>
                        <input type="text" class="form-control" name="first_name" required value="{{$user->personalInfo?$user->personalInfo->first_name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Last name')}}</label>
                        <input type="text" class="form-control" name="last_name" value="{{$user->personalInfo?$user->personalInfo->last_name:''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Region')}}</label>
                        <select name="region_id" class="form-control" id="region_id" required>
                            <option disabled selected>{{__('Select region')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('District')}}</label>
                        <select name="district_id" class="form-control" id="district_id" required>
                            <option disabled selected>{{__('Select district')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Middle name')}}</label>
                        <input type="text" class="form-control" name="middle_name" value="{{$user->personalInfo?$user->personalInfo->middle_name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Phone number')}}</label>
                        <input type="text" class="form-control" name="phone_number" value="{{$user->phone_number??''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <div style="text-align: center">
                            @if(isset($user->personalInfo->avatar))
                                @php
                                    $avatar = storage_path('app/public/user/'.$user->personalInfo->avatar)
                                @endphp
                            @else
                                @php
                                    $avatar = 'no'
                                @endphp
                            @endif
                            @if(file_exists($avatar))
                                <img src="{{asset('storage/user/'.$user->personalInfo->avatar)}}" alt="" height="94px">
                            @endif
                        </div>
                        <label class="form-label">{{__('Avatar')}}</label>
                        <input type="file" class="form-control" name="avatar" value=""/>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="gender" class="form-label">{{__('Gender')}}</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="">{{__('Choose..')}}</option>
                            <option value="1" @if(isset($user->personalInfo)){{$user->personalInfo->gender==1?'selected':''}}@endif>{{__('Man')}}</option>
                            <option value="2" @if(isset($user->personalInfo)){{$user->personalInfo->gender==2?'selected':''}}@endif>{{__('Woman')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="role" class="form-label">{{__("Users' role")}}</label><br>
                        <select id="role_id" class="form-select" name="role_id">
                            <option value="" disabled selected>{{__('Choose..')}}</option>
                            @foreach($roles as $role)
                                <option {{$user->role_id == $role->id?'selected':''}} value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="company_content">
                        <label for="company_id" class="form-label">{{__("Select Company")}}</label><br>
                        <select id="company_id" class="form-select" name="company_id">
                            <option value="">{{__('Choose..')}}</option>
                            @foreach($companies as $company)
                                <option value="{{$company->id}}" {{$user->company_id==$company->id??'selected'}}>{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Birth date')}}</label>
                        @php
                            $birth_date = explode(' ', $user->personalInfo->birth_date??'');
                        @endphp
                        <input type="date" class="form-control" name="birth_date" value="{{$birth_date[0]}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Login')}}</label>
                        <input type="email" class="form-control" name="email" required value="{{$user->email??''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Current password')}}</label>
                        <input type="password" class="form-control" name="password" value=""/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('New password')}}</label>
                        <input type="password" class="form-control" name="new_password" value=""/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Password confirmation')}}</label>
                        <input type="password" class="form-control" name="new_password_confirmation" value=""/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Street, house')}}</label>
                        <input type="text" class="form-control" name="address_name" value="{{$user->address?$user->address->name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Postcode')}}</label>
                        <input type="number" class="form-control" name="postcode" value="{{$user->address?$user->address->postcode:''}}"/>
                    </div>
                </div>
                <div class="form-group google-map-lat-lng">
                    <div>
                        <label for="map">{{__('Select a location')}}</label>
                    </div>
                    <div>
                        <span>Lat: <b id="label_latitude">{{$user->address?$user->address->latitude:''}}</b></span>&nbsp;&nbsp;
                        <span>Lng: <b id="label_longitude">{{$user->address?$user->address->longitude:''}}</b></span>
                    </div>
                </div>
                <div class="form-group">
                    <div id="map" class="google_maps"></div>
                </div>
                <input type="hidden" name="region" id="region" value="{{$user->address->region??''}}">
                <input type="hidden" name="district" id="district" value="{{$user->address->district??''}}">
                <input type="hidden" name="address_lat" id="address_lat" value="{{$user->address->latitude??''}}">
                <input type="hidden" name="address_long" id="address_long" value="{{$user->address->longitude??''}}">
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let page = true
        let company_content = document.getElementById('company_content')
        let company_id = document.getElementById('company_id')
        let role_id = document.getElementById('role_id')
        if([2, 3].includes(parseInt(role_id.value))){
            if(company_content.classList.contains('display-none')){
                company_content.classList.remove('display-none')
            }
            if(!company_id.hasAttribute('required')){
                company_id.required = true
            }
        }
        role_id.addEventListener('change', function(){
            if([2, 3].includes(parseInt(role_id.value))){
                if(company_content.classList.contains('display-none')){
                    company_content.classList.remove('display-none')
                }
                if(!company_id.hasAttribute('required')){
                    company_id.required = true
                }
            }else{
                if(!company_content.classList.contains('display-none')){
                    company_content.classList.add('display-none')
                }
                if(company_id.hasAttribute('required')){
                    company_id.required = false
                }
            }
        })
        @if(isset($user->address) && isset($user->address->cities))
            let current_region = "{{$user->address->cities->region->id??''}}"
            let current_district = "{{$user->address->cities->id??''}}"
        @else
            let current_region = ''
            let current_district = ''
        @endif
        let current_latitude = "{{$user->address->latitude??''}}"
        let current_longitude = "{{$user->address->longitude??''}}"
    </script>
    <script src="{{asset('assets/js/company.js')}}"></script>

@endsection
