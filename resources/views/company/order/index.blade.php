@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Orders list')}}</h4>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Price')}}</th>
                        <th>{{__('All price')}}</th>
                        <th>{{__('User')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{route('company_order.show', $product->id)}}">{{$i}}</a>
                        </th>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $product->id)}}">
                                @if(isset($product->category_name)){{ $product->category_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $product->id)}}">
                                @if(isset($product->color_name)){{ $product->color_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $product->id)}}">
                                @if(isset($product->size_name)){{ $product->size_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <form action="{{route('company_order.destroy', $product->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="form_functions btn btn-danger" type="submit"><i class="fe-trash-2"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
