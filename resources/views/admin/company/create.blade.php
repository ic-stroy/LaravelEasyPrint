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
                {{__('Company create')}}
            </p>
            <form action="{{route('company.store')}}" class="parsley-examples" method="POST">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Name')}}</label>
                        <input id="company_name" type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Delivery price')}}</label>
                        <input id="company_delivery_price" type="text" name="delivery_price" class="form-control" required value="{{old('delivery_price')}}"/>
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
                <div class="form-group google-map-lat-lng">
                    <div>
                        <label for="map">{{__('Select a location')}}</label>
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
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let region_id = document.getElementById('region_id')
        let map_id = document.getElementById('map')
        let district_id = document.getElementById('district_id')
        let region = document.getElementById('region')
        let district = document.getElementById('district')
        let address_lat = document.getElementById('address_lat')
        let address_long = document.getElementById('address_long')
        let company_name = document.getElementById('company_name')
        let company_delivery_price = document.getElementById('company_delivery_price')

        let districts = {}
        let region_districts = []
        let lat_lng = [41.32508826970849, 69.32866686970848];

        if(localStorage.getItem('district') != undefined && localStorage.getItem('district') != null){
            district.value = localStorage.getItem('district')
        }
        if(localStorage.getItem('region') != undefined && localStorage.getItem('region') != null){
            region.value = localStorage.getItem('region')
        }
        if(localStorage.getItem('address_lat') != undefined && localStorage.getItem('address_lat') != null){
            address_lat.value = localStorage.getItem('address_lat')
            document.getElementById('label_latitude').innerText = localStorage.getItem('address_lat')
        }
        if(localStorage.getItem('address_long') != undefined && localStorage.getItem('address_long') != null){
            address_long.value = localStorage.getItem('address_long')
            document.getElementById('label_longitude').innerText = localStorage.getItem('address_long')
        }
        if(localStorage.getItem('company_delivery_price') != undefined && localStorage.getItem('company_delivery_price') != null){
            company_delivery_price.value = localStorage.getItem('company_delivery_price')
        }
        if(localStorage.getItem('company_name') != undefined && localStorage.getItem('company_name') != null){
            company_name.value = localStorage.getItem('company_name')
        }
        company_name.addEventListener('change', function () {
            localStorage.setItem('company_name', company_name.value)
        })
        company_delivery_price.addEventListener('change', function () {
            localStorage.setItem('company_delivery_price', company_delivery_price.value)
        })
        function addOption(item, index){
            let region_option = document.createElement('option')
            region_option.value = index
            region_option.text = item.region
            if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null && localStorage.getItem('region_id') == index){
                region_option.selected = true
            }
            region_id.add(region_option)
        }
        $(document).ready(function () {
            $.ajax({
                url:"{{asset('assets/json/cities.json')}}",
                type:'GET',
                success: function (data) {
                    data.forEach(addOption)
                    if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null &&
                        localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null) {
                        districts_ = data[localStorage.getItem('region_id')].cities
                        Object.keys(districts_).forEach(function (key) {
                            let district_selected_option = document.createElement('option')
                            district_selected_option.value = key
                            district_selected_option.text = districts_[key].name
                            if (localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null && localStorage.getItem('district_id') == key) {
                                district_selected_option.selected = true
                            }
                            district_id.add(district_selected_option)
                        })
                    }
                    region_id.addEventListener('change', function () {
                        localStorage.setItem('region_id', region_id.value)
                        district_id.innerHTML = ""
                        districts = data[region_id.value].cities
                        Object.keys(districts).forEach(function (key) {
                            let district_option = document.createElement('option')
                            district_option.text = districts[key].name
                            district_option.value = key
                            district_id.add(district_option)
                        })
                    })
                    district_id.addEventListener('change', function () {
                        localStorage.setItem('region', data[region_id.value].region)
                        localStorage.setItem('district', data[region_id.value].cities[district_id.value].name)
                        localStorage.setItem('address_lat',data[region_id.value].cities[district_id.value].lat)
                        localStorage.setItem('address_long', data[region_id.value].cities[district_id.value].long)
                        localStorage.setItem('district_id', district_id.value)
                        region.value = data[region_id.value].region
                        district.value = data[region_id.value].cities[district_id.value].name
                        address_lat.value = data[region_id.value].cities[district_id.value].lat
                        address_long.value = data[region_id.value].cities[district_id.value].long
                        location.reload()
                    })
                    lat_lng = [localStorage.getItem('address_lat'), localStorage.getItem('address_long')];
                    mapOptions = {
                        center:lat_lng,
                        zoom:16
                    };

                    let marker = null;
                    let map = new L.map('map' , mapOptions);
                    let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
                    map.addLayer(layer);
                    marker = new L.marker(lat_lng);
                    marker.addTo(map);
                    map.on('click', (event)=> {
                        if(marker !== null){
                            map.removeLayer(marker);
                        }
                        marker = new L.marker([event.latlng.lat , event.latlng.lng]);
                        marker.addTo(map);
                        document.getElementById('label_latitude').innerText = event.latlng.lat;
                        document.getElementById('label_longitude').innerText = event.latlng.lng;
                        localStorage.setItem('address_lat', event.latlng.lat)
                        localStorage.setItem('address_long', event.latlng.lng)
                        address_lat.value = event.latlng.lat
                        address_long.value = event.latlng.lng
                        lat_lng = [event.latlng.lat, event.latlng.lng];
                    })

                }
            })
        })


    </script>
@endsection
