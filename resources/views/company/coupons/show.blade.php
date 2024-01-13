@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Coupon lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('coupons.create')}}">{{translate('Create')}}</a>
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
                        <th>{{translate('Name')}}</th>
                        <td>{{$model->name??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Quantity')}}</th>
                        <td>
                            @if ($model->price != null)
                                {{$model->price}} {{translate(' sum')}}
                            @elseif($model->percent != null)
                                {{$model->percent}} {{translate(' %')}}
                            @endif
                        </td>
                    </tr>
                    @if($model->min_price)
                        <tr>
                            <th>{{translate('Minimum price')}}</th>
                            <td>{{$model->min_price??''}}</td>
                        </tr>
                    @endif
                    @if($model->company)
                        <tr>
                            <th>{{translate('Company')}}</th>
                            <td>{{$model->company->name??''}}</td>
                        </tr>
                    @endif
                    @if($model->type == 0)
                        <tr>
                            <th>{{translate('Quantity of orders')}}</th>
                            <td>{{$model->order_count??''}}</td>
                        </tr>
                    @endif
                    @if($model->type == 1)
                        <tr>
                            <th>{{translate('The number of order')}}</th>
                            <td>{{$model->order_count??''}}</td>
                        </tr>
                    @endif
                    @if($model->start_date)
                        <tr>
                            <th>{{translate('Start date')}}</th>
                            <td>{{$model->start_date}}</td>
                        </tr>
                    @endif
                    @if($model->end_date)
                        <tr>
                            <th>{{translate('End date')}}</th>
                            <td>{{$model->end_date}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$model->updated_at??''}}</td>
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
