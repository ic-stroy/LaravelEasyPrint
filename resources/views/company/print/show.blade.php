@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Warhouse print product lists')}}</h4>
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
                        @if($model->name)
                            <td>{{$model->name}}</td>
                        @else
                            <td>{{$model->product?$model->product->name:''}}</td>
                        @endif
                    </tr>
                    <tr>
                        <th>{{translate('Current category')}}</th>
                        <td>@if(!empty($category_array)){{ implode(", ", $category_array) }}@endif</td>
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
                        <th>{{translate('Quantity')}}</th>
                        <td>{{$model->quantity??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Front image')}}</th>
                        <td>
                            @if($model->image_front)
                                @php
                                    $is_image = 0;
                                @endphp
                                <div class="row">
                                    @php
                                        $avatar_main = storage_path('app/public/warehouse/'.$model->image_front);
                                    @endphp
                                    @if(file_exists($avatar_main))
                                        @php
                                            $is_image = 1
                                        @endphp
                                        <div class="col-4 mb-3">
                                            <img src="{{asset('storage/warehouse/'.$model->image_front)}}" alt="">
                                        </div>
                                    @endif
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
                        <th>{{translate('Back image')}}</th>
                        <td>
                            @if($model->image_back)
                                @php
                                    $is_image = 0;
                                @endphp
                                <div class="row">
                                    @php
                                        $avatar_main = storage_path('app/public/warehouse/'.$model->image_back);
                                    @endphp
                                    @if(file_exists($avatar_main))
                                        @php
                                            $is_image = 1
                                        @endphp
                                        <div class="col-4 mb-3">
                                            <img src="{{asset('storage/warehouse/'.$model->image_back)}}" alt="">
                                        </div>
                                    @endif
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
                        <th>{{translate('Anime images')}}</th>
                        <td>
                            @if(!empty($model->uploads))
                                @foreach($model->uploads as $upload)
                                    @php
                                        $is_image = 0;
                                    @endphp
                                    <div class="row">
                                        @php
                                            $avatar_main = storage_path('app/public/warehouse/'.$model->image_back);
                                        @endphp
                                        @if(file_exists($avatar_main))
                                            @php
                                                $is_image = 1
                                            @endphp
                                            <div class="col-4 mb-3">
                                                <img src="{{asset('storage/warehouse/'.$model->image_back)}}" alt="">
                                            </div>
                                        @endif
                                    </div>
                                    @if($is_image == 0)
                                        <div>
                                            <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div>
                                    <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Price')}}</th>
                        <td>{{$model->price??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Company')}}</th>
                        <td>{{$model->company?$model->company->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Manfacturer country')}}</th>
                        <td>{{$model->manufacturer_country??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Material composition')}}</th>
                        <td>{{$model->material_composition??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Description')}}</th>
                        <td>{{$model->description??''}}</td>
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
