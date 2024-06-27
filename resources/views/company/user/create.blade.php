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
                {{translate('User create')}}
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
            <form action="{{route('company_user.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('First name')}}</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" required value="{{old('first_name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Last name')}}</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{old('last_name')}}"/>
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
                        <input type="text" class="form-control" name="middle_name" value="{{old('middle_name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Phone number')}}</label>
                        <input type="number" class="form-control" name="phone_number" value="{{old('phone_number')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Avatar')}}</label>
                        <input type="file" class="form-control" name="avatar"  value="{{old('avatar')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="gender" class="form-label">{{translate('Gender')}}</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="">{{translate('Choose..')}}</option>
                            <option value="1" {{old('gender')==1??'selected'}}>{{translate('Man')}}</option>
                            <option value="2" {{old('gender')==2??'selected'}}>{{translate('Woman')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Birth date')}}</label>
                        <input type="date" class="form-control" name="birth_date" value="{{old('birth_date')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Login')}}</label>
                        <input type="email" class="form-control" name="email" required value="{{old('login')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Password')}}</label>
                        <input type="password" class="form-control" name="password" required value="{{old('password')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Password confirmation')}}</label>
                        <input type="password" class="form-control" name="password_confirmation" required value="{{old('password_confirmation')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Street, house')}}</label>
                        <input type="text" class="form-control" name="address_name" value="{{old('address_name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Postcode')}}</label>
                        <input type="number" class="form-control" name="postcode" value="{{old('postcode')}}"/>
                    </div>
                </div>
                <div class="form-group google-map-lat-lng">
                    <div>
                        <label for="map">{{translate('Select a location')}}</label>
                    </div>
                    <div>
                        <span>Lat: <b id="label_latitude">41.314560</b></span>&nbsp;&nbsp;
                        <span>Lng: <b id="label_longitude">69.269780</b></span>
                    </div>
                </div>
                <div class="form-group">
                    <div id="map" class="google_maps"></div>
                </div>
                <input type="hidden" name="region" id="region" value="{{old('region')}}">
                <input type="hidden" name="district" id="district" value="{{old('district')}}">
                <input type="hidden" name="address_lat" id="address_lat" value="{{old('address_lat')}}">
                <input type="hidden" name="address_long" id="address_long" value="{{old('address_long')}}">
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let page = false
        let current_region = ''
    </script>
    <script src="{{asset('assets/js/company.js')}}"></script>
@endsection
