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
                {{__('Company update')}}
            </p>
            <form action="{{route('company.update', $company->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Name')}}</label>
                        <input id="company_name" type="text" name="name" class="form-control" required value="{{$company->name}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Delivery price')}}</label>
                        <input id="company_delivery_price" type="text" name="delivery_price" class="form-control" required value="{{$company->delivery_price}}"/>
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
                        <label class="form-label">{{__('Street, house')}}</label>
                        <input class="form-control" type="text" name="address_name" value="{{$company->address?$company->address->name:''}}">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Postcode')}}</label>
                        <input class="form-control" type="text" name="postcode" value="{{$company->address?$company->address->postcode:''}}">
                    </div>
                </div>
                <div class="form-group google-map-lat-lng">
                    <div>
                        <label for="map">{{__('Select a location')}}</label>
                    </div>
                    <div>
                        <span>Lat: <b id="label_latitude">{{$company->address?$company->address->latitude:''}}</b></span>&nbsp;&nbsp;
                        <span>Lng: <b id="label_longitude">{{$company->address?$company->address->longitude:''}}</b></span>
                    </div>
                </div>
                <div class="form-group">
                    <div id="map" class="google_maps"></div>
                </div>
                <input type="hidden" name="region" id="region" value="{{$company->address?$company->address->region:''}}">
                <input type="hidden" name="district" id="district" value="{{$company->address?$company->address->district:''}}">
                <input type="hidden" name="address_lat" id="address_lat" value="{{$company->address?$company->address->latitude:''}}">
                <input type="hidden" name="address_long" id="address_long" value="{{$company->address?$company->address->longitude:''}}">
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
        @if(isset($company->address) && isset($company->address->cities))
            let current_region = "{{$company->address->cities->region->id??''}}"
            let current_district = "{{$company->address->cities->id??''}}"
        @else
            let current_region = ''
            let current_district = ''
        @endif
        let current_latitude = "{{$company->address?$company->address->latitude:''}}"
        let current_longitude = "{{$company->address?$company->address->longitude:''}}"
    </script>
    <script src="{{asset('assets/js/company.js')}}"></script>
@endsection
