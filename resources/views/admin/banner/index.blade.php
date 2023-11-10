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
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Image')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Updated_at')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($banners as $banner)
                        @php
                            $i++
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
                                        if(!isset($banner->image)){
                                             $banner_image = 'no';
                                        }else{
                                            $banner_image = $banner->image;
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
                                <a class="show_page" href="{{route('banner.show', $banner->id)}}">
                                    @if(isset($banner->updated_at)){{ $banner->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('banner.edit', $banner->id)}}"><i class="fe-edit-2"></i></a>
                                    <form action="{{route('banner.destroy', $banner->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="form_functions btn btn-danger" type="submit"><i class="fe-trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
