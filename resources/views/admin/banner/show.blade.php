@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Banner lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('banner.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">
                <thead>
                <tr>
                    <th>{{__('Attributes')}}</th>
                    <th>{{__('Informations')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{__('Title')}}</th>
                        <td>{{$model->title??''}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Text')}}</th>
                        <td>{!! $model->text !!}</td>
                    </tr>
                    <tr>
                        <th>{{__('Image')}}</th>
                        <td>
                            @php
                                if(!isset($model->image)){
                                     $banner_image = 'no';
                                }else{
                                    $banner_image = $model->image;
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
                        <th>{{__('Is active')}}</th>
                        <td>{{$model->is_active = 1?'Active':'No active'}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Updated at')}}</th>
                        <td>{{$model->updated_at??''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
