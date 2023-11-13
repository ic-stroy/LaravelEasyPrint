@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Products lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('warehouse.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Product')}}</th>
                        <th>{{__('Size')}}</th>
                        <th>{{__('Sum')}}</th>
                        <th>{{__('Color')}}</th>
                        <th>{{__('Count')}}</th>
                        <th>{{__('Updated_at')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($products as $product)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('warehouse.show', $product->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                    @if(isset($product->product->name))
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
                                <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                    @if(isset($product->sum)) {{ $product->sum }} @else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                    @if(isset($product->color->id)){{ $product->color->name }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('warehouse.show', $product->id)}}">
                                    @if(isset($product->count)){{ $product->count }}@else <div class="no_text"></div> @endif
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
                                    <form action="{{route('warehouse.destroy', $product->id)}}" method="POST">
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
