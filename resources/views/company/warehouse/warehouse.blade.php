@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__($product_->name??'')}} {{translate(' product lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('warehouse.category.create_warehouse', $product_->id)}}">{{translate('Add products to warehouse')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Warehouse')}}</th>
                    <th>{{translate('Size')}}</th>
                    <th>{{translate('Images')}}</th>
                    <th>{{translate('Price')}}</th>
                    <th>{{translate('Color')}}</th>
                    <th>{{translate('Count')}}</th>
                    <th>{{translate('Updated_at')}}</th>
                    <th class="text-center">{{translate('Functions')}}</th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($warehouse as $product)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{route('warehouse.show', $product->id)}}">{{$i}}</a>
                        </th>
                        <td>
                            <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                @if ($product->name)
                                    @if(strlen($product->name)>34)
                                        {{ substr($product->name, 0, 34) }}...
                                    @else
                                        {{$product->name}}
                                    @endif
                                @elseif(isset($product->product->name))
                                    @if(strlen($product->product->name)>34)
                                        {{ substr($product->product->name, 0, 34) }}...
                                    @else
                                        {{$product->product->name}}
                                    @endif
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                @if(isset($product->size->id)) {{ $product->size->name }} @else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page_color" href="{{route('warehouse.show', $product->id)}}">
                                <div class="d-flex">
                                    @if(isset($product->images))
                                        @php
                                            $images = json_decode($product->images);
                                            $is_image = 0;
                                        @endphp
                                        @foreach($images as $image)
                                            @php
                                                $avatar_main = storage_path('app/public/warehouses/'.$image);
                                            @endphp
                                            @if(file_exists($avatar_main))
                                                @php($is_image = 1)
                                                <div style="margin-right: 2px">
                                                    <img src="{{ asset('storage/warehouses/'.$image) }}" alt="" height="40px">
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
                            <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                @if(isset($product->price)) {{ $product->price }} @else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page_color" href="{{route('warehouse.show', $product->id)}}">
                                <div style="background-color: {{$product->color->code??''}}; height: 40px; width: 64px; border-radius: 4px; border: solid 1px"></div>
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                @if(isset($product->quantity)){{ $product->quantity }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                @if(isset($product->updated_at)){{ $product->updated_at }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" href="{{route('warehouse.edit', $product->id)}}"><i class="fe-edit-2"></i></a>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('warehouse.destroy', $product->id)}}"><i class="fe-trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
