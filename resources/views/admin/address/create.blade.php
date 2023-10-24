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
                {{__('Address create')}}
            </p>
            <form action="{{route('address.store')}}" class="parsley-examples" method="POST">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Longitude')}}</label>
                    <input type="text" name="longitude" class="form-control" required value="{{old('longitude')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Latitude')}}</label>
                    <input type="text" name="latitude" class="form-control" required value="{{old('latitude')}}"/>
                </div>
                <div class="form-group">
                    <label for="addressId">{{__('locale.address')}}</label>
                    <input type="text" id="addressValue" class="form-control @error('address') error-data-input is-invalid @enderror" value="{{ old('address') }}" autocomplete="off">
                    <input type="hidden" name="address" id="addressId" class="form-control @error('address') error-data-input is-invalid @enderror" value="{{ old('address') }}" autocomplete="off">
                    <span class="error-data"></span>
                </div>
                <div class="form-group">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>
                <div class="form-group google-map-lat-lng">
                    <div>
                        <label for="map">{{__('locale.Select a location')}}</label>
                    </div>
                    <div>
                        <span>Lat: <b id="label_latitude">41.314560</b></span>&nbsp;&nbsp;
                        <span>Lng: <b id="label_longitude">69.269780</b></span>
                    </div>
                </div>
                <div class="form-group">
                    <div id="map" class="google_maps"></div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://api-maps.yandex.ru/2.1.79/?apikey=ваш API-ключ&lang=ru_RU"></script>
{{--    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>--}}
    <script>

        let lat_lng = [41.32508826970849, 69.32866686970848];
        let mapOptions = {
            center:lat_lng,
            zoom:16
        };

        function init() {
            let map = new ymaps.Map('map', {
                center:lat_lng,
                zoom:16
            });

            let placemark = new ymaps.Placemark(lat_lng, {

            })

            map.controls.remove('geolocationControl'); // удаляем геолокацию
            map.controls.remove('searchControl'); // удаляем поиск
            map.controls.remove('trafficControl'); // удаляем контроль трафика
            // map.controls.remove('typeSelector'); // удаляем тип
            map.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
            map.controls.remove('zoomControl'); // удаляем контрол зуммирования
            map.controls.remove('rulerControl'); // удаляем контрол правил
            map.behaviors.disable(['scrollZoom']); // отключаем скролл карты (опционально)
        }

        ymaps.ready(init);



        // let marker = null;
        //
        // let map = new L.map('map' , mapOptions);
        //
        // let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        // map.addLayer(layer);
        // marker = new L.marker(lat_lng);
        // marker.addTo(map);
        // map.on('click', (event)=> {
        //     console.log(event)
        //     if(marker !== null){
        //         map.removeLayer(marker);
        //     }
        //     marker = new L.marker([event.latlng.lat , event.latlng.lng]);
        //     marker.addTo(map);
        //     document.getElementById('latitude').value = event.latlng.lat;
        //     document.getElementById('longitude').value = event.latlng.lng;
        //     document.getElementById('label_latitude').innerText = event.latlng.lat;
        //     document.getElementById('label_longitude').innerText = event.latlng.lng;
        //     lat_lng = [event.latlng.lat, event.latlng.lng];
        // })


    </script>
@endsection
