@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Products lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('product.create')}}">{{translate('Create')}}</a>
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
                        <td>{{$warehouse->name??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Subcategory')}}</th>
                        <td>@if(isset($warehouse->category_name)){{ $warehouse->category_name }}@endif</td>
                    </tr>
                    <tr>
                        <th>{{translate('Sum')}}</th>
                        <td>{{$warehouse->price??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('quantity')}}</th>
                        <td>{{$warehouse->quantity??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('image')}}</th>
                        <td>
                            @if(isset($warehouse->images))
                                @php
                                    $images = json_decode($warehouse->images);
                                    $is_image = 0;
                                @endphp
                                <div class="row">
                                    @foreach($images as $image)
                                        @php
                                            $avatar_main = storage_path('app/public/products/'.$image);
                                        @endphp
                                        @if(file_exists($avatar_main))
                                            @php($is_image = 1)
                                            <div class="col-4 mb-3">
                                                <img src="{{asset('storage/products/'.$image)}}" alt="">
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
                        <td>{{$warehouse->updated_at??''}}</td>
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
