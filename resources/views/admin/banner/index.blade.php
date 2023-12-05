@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Banner lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('banner.create')}}">{{translate('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Title')}}</th>
                        <th>{{translate('Main')}}</th>
                        <th>{{translate('Carousel')}}</th>
                        <th>{{translate('Text')}}</th>
                        <th>{{translate('Updated_at')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($banners as $banner)
                        @php
                            $i++;
                            if(isset($banner->image) && !is_array($banner->image)){
                                $banner_images = json_decode($banner->image);
                            }else{
                                $banner_images = [];
                            }
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('banner.show', $banner->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('banner.show', $banner->id)}}">
                                    @if(isset($banner->title)){{ $banner->title }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page_color" href="{{route('banner.show', $banner->id)}}">
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
                                            <img src="{{asset('storage/banner/'.$banner_image)}}" alt="" height="40px">
                                        </div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page_color" href="{{route('banner.show', $banner->id)}}">
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
                                                <div class="">
                                                    <img src="{{asset('storage/banner/carousel/'.$carousel_image)}}" alt="" height="40px">
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('banner.show', $banner->id)}}">
                                    @if(isset($banner->text))
                                        @if(strlen($banner->text)>0)
                                            {{ substr($banner->text, 0, 10).' ...' }}
                                        @else
                                            {{$banner->text}}
                                        @endif
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('banner.show', $banner->id)}}">
                                    @if(isset($banner->updated_at)){{ $banner->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('banner.edit', $banner->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('banner.destroy', $banner->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
