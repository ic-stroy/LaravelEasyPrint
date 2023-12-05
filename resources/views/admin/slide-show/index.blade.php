@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{asset('assets/css/toogle-switch.css')}}">
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Products lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('product.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Current category')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Images')}}</th>
                        <th>{{__('Updated_at')}}</th>
                        <th class="text-center">{{__('On Slide show')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($products as $product)
                        @php
                            $i++;
                            if(isset($product->subCategory->id)){
                                $current_category = $product->subCategory->name;
                            }elseif(isset($product->category->id)){
                                $current_category = $product->category->name;
                            }
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('product.show', $product->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('product.show', $product->id)}}">
                                    @if(isset($product->name))
                                        @if(strlen($product->name)>34)
                                            {{ substr($product->name, 0, 34) }}...
                                        @else
                                            {{$product->name}}
                                        @endif
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('product.show', $product->id)}}">
                                    @if(isset($current_category)){{ $current_category }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('product.show', $product->id)}}">
                                    {{$product->status == 1?__('Active'):__('No active') }}
                                </a>
                            </td>
                            <td>
                                <a class="show_page_color" href="{{route('product.show', $product->id)}}">
                                    <div class="d-flex">
                                        @if(isset($product->images))
                                            @php
                                                $images = json_decode($product->images);
                                                $is_image = 0;
                                            @endphp
                                            @foreach($images as $image)
                                                @php
                                                    $avatar_main = storage_path('app/public/products/'.$image);
                                                @endphp
                                                @if(file_exists($avatar_main))
                                                    @php($is_image = 1)
                                                    <div style="margin-right: 2px">
                                                        <img src="{{ asset('storage/products/'.$image) }}" alt="" height="40px">
                                                    </div>
                                                @endif
                                            @endforeach
                                            @if($is_image == 0)
                                                <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="40px">
                                            @endif
                                        @else <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="40px"> @endif
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('product.show', $product->id)}}">
                                    @if(isset($product->updated_at)){{ $product->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <input type="hidden" class="productId" name="product_id" value="{{$product->id}}">
                                <div class="d-flex justify-content-center">
                                    <label class="switch">
                                        <input name="slide_show" class="slideShow" type="checkbox" {{$product->slide_show == 1?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            let slideShow = document.getElementsByClassName('slideShow')
            let productId = document.getElementsByClassName('productId')

            function slideShowFunc(item, val) {
                slideShow[val].addEventListener('change', function () {
                    if (this.checked) {
                        $.ajax({
                            type: "GET",
                            url: "/../slide-show/status/",
                            data: {
                                "id": productId[val].value,
                                "checked": true,
                            },
                            success: function (data) {
                                if(data.status == true){
                                    toastr.success(data.message)
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            type: "GET",
                            url: "/../slide-show/status/",
                            data: {
                                "id": productId[val].value,
                                "checked": false,
                            },
                            success: function (data) {
                                if(data.status == true){
                                    toastr.warning(data.message)
                                }
                            }
                        });
                    }
                })
            }
            Object.keys(slideShow).forEach(slideShowFunc)
        })
    </script>
@endsection
