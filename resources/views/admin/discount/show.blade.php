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
                            @if($discounts_data['discount']->percent != null)
                                {{$discounts_data['discount']->percent}} {{translate(' %')}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Number of warehouses')}}</th>
                        <td>
                            @if($discounts_data['number'] != null)
                                {{$discounts_data['number']}}
                            @endif
                        </td>
                    </tr>
                    @if(isset($discounts_data['discount']->product))
                        <tr>
                            <th>{{translate('Product')}}</th>
                            <td>{{$discounts_data['discount']->product->name??''}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$discounts_data['discount']->updated_at??''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .color_content{
            height: 40px;
            width: 64px;
            border-radius: 4px;
            border: solid 1px;
            display: flex;
            align-items: center;
            justify-content: center
        }
    </style>
@endsection
