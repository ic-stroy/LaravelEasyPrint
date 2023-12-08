@extends('layout.layout')

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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-muted font-14">
                {{translate('Company create')}}
            </p>
            <form action="{{route('company.store')}}" class="parsley-examples" method="POST">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Name')}}</label>
                        <input id="company_name" type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Delivery price')}}</label>
                        <input id="company_delivery_price" type="text" name="delivery_price" class="form-control" required value="{{old('delivery_price')}}"/>
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
                        <label class="form-label">{{translate('Street, house')}}</label>
                        <input class="form-control" type="text" name="address_name">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Postcode')}}</label>
                        <input class="form-control" type="text" name="postcode">
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
