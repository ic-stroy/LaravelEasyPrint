@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Products lists')}}</h4>
            <ul class="nav nav-pills navtab-bg nav-justified">
                @php
                    $i = 0;
                @endphp
                @foreach($categories as $category)
                    @php
                        $i++;
                    @endphp
                    <li class="nav-item">
                        <a href="#category_{{$category->id}}" data-bs-toggle="tab" aria-expanded="{{$i == 1?'true':'false'}}" class="nav-link {{$i == 1?'active':''}}">
                            {{$category->name?translate($category->name):''}}
                            @if(!empty($all_products[$category->id]))
                                @if($all_products[$category->id]['quantity']>0)
                                    <span class="badge bg-danger">{{$all_products[$category->id]['quantity']}}</span>
                                @endif
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @php
                    $i = 0;
                @endphp
                @foreach($categories as $category)
                    @php
                        $i++;
                    @endphp
                    <div class="tab-pane{{$i == 1?' show active':''}}" id="category_{{$category->id}}">
                        <table class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="table_body">
                            @if(!empty($all_products[$category->id]))
                                @foreach($all_products[$category->id]['products'] as $product)
                                    @php $warehouse_quantity = count($product->warehouse) @endphp
                                    <tr>
                                        <td>
                                            <a class="show_page d-flex justify-content-center" href="{{route('warehouse.category.warehouse', $product->id)}}">
                                                @if(isset($product->name))
                                                    <span class="d-flex">
                                                        @if(strlen($product->name)>34)
                                                            {{ substr($product->name, 0, 34) }}...
                                                        @else
                                                            {{$product->name}}
                                                        @endif
                                                        @if($warehouse_quantity>0)
                                                            <span class="ms-1 badge bg-danger d-flex align-items-center">{{count($product->warehouse)}}</span>
                                                        @endif
                                                    </span>
                                                @else <div class="no_text"></div> @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a class="show_page_color" href="{{route('warehouse.category.warehouse', $product->id)}}">
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
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
