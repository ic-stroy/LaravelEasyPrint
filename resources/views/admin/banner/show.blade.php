@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    @php
        if(isset($model->image) && !is_array($model->image)){
            $banner_images = json_decode($model->image);
        }else{
            $banner_images = [];
        }
    @endphp
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Banner lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('banner.create')}}">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table dt-responsive table_show">--}}
            <table class="table dt-responsive table_show">
                <thead>
                <tr>
                    <th>{{translate('Attributes')}}</th>
                    <th>{{translate('Informations')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{translate('Title')}}</th>
                        <td>{{$model->title??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Text')}}</th>
                        <td>{!! $model->text !!}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Banner image')}}</th>
                        <td>
                            @php
                                if(!isset($banner_images->banner)){
                                     $banner_image = 'no';
                                }else{
                                    $banner_image = $banner_images->banner;
                                }
                                $avatar_main = storage_path('app/public/banner/'.$banner_image);
                            @endphp
                            @if(file_exists($avatar_main))
                                <div class="">
                                    <img src="{{asset('storage/banner/'.$banner_image)}}" alt="" height="200px">
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Carousel image')}}</th>
                        <td>
                            <div class="row">
                                @if(!is_array($banner_images))
                                    @php
                                        if(!isset($banner_images->carousel) && count($banner_images->carousel)>0){
                                             $carousel_images[] = 'no';
                                        }else{
                                            $carousel_images = $banner_images->carousel;
                                        }
                                    @endphp
                                    @foreach($carousel_images as $carousel_image)
                                        @if(file_exists(storage_path('app/public/banner/carousel/'.$carousel_image)))
                                            <div class="col-4 mb-3">
                                                <img src="{{asset('storage/banner/carousel/'.$carousel_image)}}" alt="" height="200px">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Is active')}}</th>
                        <td>{{$model->is_active = 1?'Active':'No active'}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$model->updated_at??''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
