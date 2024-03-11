@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Image')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('images.create')}}">{{translate('Create')}}</a>
            </div>
            <table class="table dt-responsive nowrap table_show">
                <thead>
                    <tr>
                        <th>{{translate('Attributes')}}</th>
                        <th>{{translate('Informations')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{translate('Image')}}</th>
                        <td>
                            <div class="row">
                                @php
                                    $avatar_main = storage_path('app/public/images/'.$image->name);
                                @endphp
                                @if(file_exists($avatar_main))
                                    @php($has_image = 1)
                                    <div class="col-4 mb-3">
                                        <img src="{{asset('storage/images/'.$image->name)}}" alt="">
                                    </div>
                                @endif
                            </div>
                            @if($has_image == 0)
                                <div>
                                    <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$image->updated_at??''}}</td>
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
