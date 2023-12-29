@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .yandex_maps{
            height: 400px;
            min-width: 400px;
            max-width: 600px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Company informations')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('company.create')}}">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">--}}
            <table class="table dt-responsive nowrap table_show">
                <thead>
                    <tr>
                        <th>{{translate('Attributes')}}</th>
                        <th>{{translate('Informations')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{translate('Name')}}</th>
                        <td>{{$model->name??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Delivery price')}}</th>
                        <td>{{$model->delivery_price??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Region')}}</th>
                        <td>{{isset($model->address->cities->region)? $model->address->cities->region->name :''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('District')}}</th>
                        <td>{{isset($model->address->cities)?$model->address->cities->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Street, house')}}</th>
                        <td>{{isset($model->address->name)?$model->address->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Post code')}}</th>
                        <td>{{isset($model->address->postcode)?$model->address->postcode:''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Map')}}</th>
                        <td>
                            <div class="yandex_maps" id="map">

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$model->updated_at??''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ваш API-ключ&lang=ru_RU"></script>
    <script>
        @if(isset($model->address->latitude) && isset($model->address->longitude))
            let center = ["{{$model->address->latitude}}", "{{$model->address->longitude}}"]
        @else
            let center = [41.32508826970849, 69.32866686970848]
        @endif
        function init() {
            let map = new ymaps.Map('map', {
                center: center,
                zoom: 17
            });

            let placemark = new ymaps.Placemark(center, {}, {});

            map.controls.remove('geolocationControl'); // удаляем геолокацию
            map.controls.remove('searchControl'); // удаляем поиск
            map.controls.remove('trafficControl'); // удаляем контроль трафика
            // map.controls.remove('typeSelector'); // удаляем тип
            map.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
            map.controls.remove('zoomControl'); // удаляем контрол зуммирования
            map.controls.remove('rulerControl'); // удаляем контрол правил
            // map.behaviors.disable(['scrollZoom']); // отключаем скролл карты (опционально)

            map.geoObjects.add(placemark);
        }
        ymaps.ready(init);
    </script>
@endsection
