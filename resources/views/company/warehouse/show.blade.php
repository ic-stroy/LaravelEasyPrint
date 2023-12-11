@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Warhouse product lists')}}</h4>
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
                        <th>{{translate('Product')}}</th>
                        <td>{{$model->product?$model->product->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Size')}}</th>
                        <td>
                            @if(isset($model->size->name)){{ $model->size->name }}@endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Colors')}}</th>
                        <td class="d-flex justify-content-between">
                            @if(isset($model->color->name))
                                <div class="color_content" style=" background-color: {{$model->color->code??''}};">{{$model->color->name}}</div>
                            @else
                                <div>{{translate('No color')}}</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Count')}}</th>
                        <td>{{$model->quantity??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('image')}}</th>
                        <td>
                            @if(isset($model->images))
                                @php
                                    $images = json_decode($model->images);
                                    $is_image = 0;
                                @endphp
                                <div class="row">
                                    @foreach($images as $image)
                                        @php
                                            $avatar_main = storage_path('app/public/warehouses/'.$image);
                                        @endphp
                                        @if(file_exists($avatar_main))
                                            @php
                                                $is_image = 1
                                            @endphp
                                            <div class="col-4 mb-3">
                                                <img src="{{asset('storage/warehouses/'.$image)}}" alt="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                @if($is_image == 0)
                                    <div>
                                        <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                    </div>
                                @endif
                            @elseif(isset($model->product->images))
                                @php
                                    $product_images = json_decode($model->product->images);
                                    $is_image = 0;
                                @endphp
                                <div class="row">
                                    @foreach($product_images as $product_image)
                                        @php
                                            $avatar_main = storage_path('app/public/products/'.$product_image);
                                        @endphp
                                        @if(file_exists($avatar_main))
                                            @php($is_image = 1)
                                            <div class="col-4 mb-3">
                                                <img src="{{asset('storage/products/'.$product_image)}}" alt="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                @if($is_image == 0)
                                    <div>
                                        <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                    </div>
                                @endif
                            @else
                                <div>
                                    <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                </div>
                            @endif
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
    <style>
        .color_content{
            height: 40px;
            width: 64px;
            border-radius: 4px;
            border: solid 1px;
            display: flex;
            align-items: center;
            justify-content: center
        }
    </style>
@endsection
