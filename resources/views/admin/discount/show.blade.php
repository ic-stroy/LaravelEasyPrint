@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Discount lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('discount.create')}}">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">--}}
            @if(isset($discounts_data['discounts'][0]))
                <table class="table dt-responsive nowrap table_show">
                    <thead>
                        <tr>
                            <th>{{translate('Attributes')}}</th>
                            <th>{{translate('Informations')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>{{translate('Category')}}</th>
                            <td>
                                @if($category != '' || $subcategory != '')
                                    {{$category}} {{' '.$subcategory}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{translate('Discount percent')}}</th>
                            <td>
                                @if($discounts_data['discounts'][0]->percent != null)
                                    {{$discounts_data['discounts'][0]->percent}} {{translate(' %')}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            @switch($discounts_data['discounts'][0]->type)
                                @case(\App\Constants::DISCOUNT_WAREHOUSE_TYPE)
                                    <th>{{translate('Number of warehouses')}}</th>
                                @case(\App\Constants::DISCOUNT_PRODUCT_TYPE)
                                    <th>{{translate('Number of products')}}</th>
                            @endswitch
                            <td>
                                @if($discounts_data['number'] != null)
                                    {{$discounts_data['number']}}
                                @endif
                            </td>
                        </tr>
                        @if(!empty($discounts_data['discounts'][0]->product))
                            <tr>
                                <th>{{translate('Product')}}</th>
                                <td>
                                    @foreach($discounts_data['discounts'] as $discount_data)
                                        {{$discount_data->product->name??''}}. <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>{{translate('Updated at')}}</th>
                            <td>{{$discounts_data['discounts'][0]->updated_at??''}}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
