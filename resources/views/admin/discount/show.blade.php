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
            @if(isset($discount_data['discounts'][0]))
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
                                @if($discount_data['category'][0] || $discount_data['subcategory'][0])
                                    {{implode(', ', [$discount_data['category'][0], $discount_data['subcategory'][0]])}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{translate('Discount percent')}}</th>
                            <td>
                                @if($discount_data['discounts'][0]->percent)
                                    {{$discount_data['discounts'][0]->percent}} {{translate(' %')}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            @switch($discount_data['discounts'][0]->type)
                                @case(\App\Constants::DISCOUNT_WAREHOUSE_TYPE)
                                    <th>{{translate('Number of warehouses')}}</th>
                                @break
                                @case(\App\Constants::DISCOUNT_PRODUCT_TYPE)
                                    <th>{{translate('Number of products')}}</th>
                                @break
                            @endswitch
                            <td>
                                @if($discount_data['number'])
                                    {{$discount_data['number']}}
                                @endif
                            </td>
                        </tr>
                        @if($discount_data['discounts'][0]->product)
                            <tr>
                                <th>{{translate('Product')}}</th>
                                <td>
                                    @foreach($discount_data['discounts'] as $discount)
                                        {{$discount->product->name??''}}. <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>{{translate('Updated at')}}</th>
                            <td>{{$discount_data['discounts'][0]->updated_at??''}}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
