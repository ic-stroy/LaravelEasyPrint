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
                {{translate('User edit')}}
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
            <form action="{{route('company_user.update', $user->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('First name')}}</label>
                        <input type="text" class="form-control" name="first_name" required value="{{$user->personalInfo?$user->personalInfo->first_name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Last name')}}</label>
                        <input type="text" class="form-control" name="last_name" value="{{$user->personalInfo?$user->personalInfo->last_name:''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Region')}}</label>
                        <select name="region_id" class="form-control" id="region_id" required>
                            <option disabled selected>{{translate('Select region')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('District')}}</label>
                        <select name="district_id" class="form-control" id="district_id" required>
                            <option disabled selected>{{translate('Select district')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Middle name')}}</label>
                        <input type="text" class="form-control" name="middle_name" value="{{$user->personalInfo?$user->personalInfo->middle_name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Phone number')}}</label>
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
                        <label class="form-label">{{translate('Avatar')}}</label>
                        <input type="file" class="form-control" name="avatar" value=""/>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="gender" class="form-label">{{translate('Gender')}}</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="">{{translate('Choose..')}}</option>
                            <option value="1" @if(isset($user->personalInfo)){{$user->personalInfo->gender==1?'selected':''}}@endif>{{translate('Man')}}</option>
                            <option value="2" @if(isset($user->personalInfo)){{$user->personalInfo->gender==2?'selected':''}}@endif>{{translate('Woman')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Birth date')}}</label>
                        @php
                            $birth_date = explode(' ', $user->personalInfo->birth_date??'');
                        @endphp
                        <input type="date" class="form-control" name="birth_date" value="{{$birth_date[0]}}"/>
                    </div>
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Login')}}</label>
                        <input type="email" class="form-control" name="email" required value="{{$user->email??''}}"/>
                    </div>
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Current password')}}</label>
                        <input type="password" class="form-control" name="password" value=""/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('New password')}}</label>
                        <input type="password" class="form-control" name="new_password" value=""/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Password confirmation')}}</label>
                        <input type="password" class="form-control" name="new_password_confirmation" value=""/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Street, house')}}</label>
                        <input type="text" class="form-control" name="address_name" value="{{$user->address?$user->address->name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Postcode')}}</label>
                        <input type="number" class="form-control" name="postcode" value="{{$user->address?$user->address->postcode:''}}"/>
                    </div>
                </div>
                <div class="form-group google-map-lat-lng">
                    <div>
                        <label for="map">{{translate('Select a location')}}</label>
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
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let page = true

        @if($user->address && isset($user->address->cities))
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
