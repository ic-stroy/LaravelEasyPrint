@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Discount lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('discount.create')}}">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Discount percent')}}</th>
                        <th>{{translate('Category')}}</th>
                        <th>{{translate('Product')}}</th>
                        <th>{{translate('Type')}}</th>
                        <th>{{translate('Number of products')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($discounts_data as $discount_data)
                        <tr>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount_data['discount'][0]->id)}}">
                                    {{$i}}
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount_data['discount'][0]->id)}}">
                                    @if($discount_data['discount'][0]->percent != null)
                                       {{$discount_data['discount'][0]->percent.'  %'}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount_data['discount'][0]->id)}}">
                                    @if(!empty($discount_data['category'][0]) || !empty($discount_data['subcategory'][0]))
                                        {{implode(', ', [$discount_data['category'][0], $discount_data['subcategory'][0]])}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount_data['discount'][0]->id)}}">
                                    @if(count($discount_data['discount']) == 1)
                                        {{$discount_data['discount'][0]->product->name}}
                                    @elseif(count($discount_data['discount']) > 1)
                                        {{translate('All products')}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount_data['discount'][0]->id)}}">
                                    @if(isset($discount_data['discount'][0]->type))
                                        @switch($discount_data['discount'][0]->type)
                                            @case(\App\Constants::DISCOUNT_PRODUCT_TYPE)
                                                {{translate('Product')}}
                                            @break
                                            @case(\App\Constants::DISCOUNT_WAREHOUSE_TYPE)
                                                {{translate('Warehouse')}}
                                            @break
                                        @endswitch
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount_data['discount'][0]->id)}}">
                                    @if(isset($discount_data['number']))
                                        {{$discount_data['number']}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('discount.edit', $discount_data['discount'][0]->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('discount.destroy', $discount_data['discount'][0]->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
