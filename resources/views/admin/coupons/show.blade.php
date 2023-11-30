@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Coupon lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('coupons.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">
                <thead>
                <tr>
                    <th>{{__('Attributes')}}</th>
                    <th>{{__('Informations')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <td>{{$model->name??''}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Category')}}</th>
                        <td>
                            @if($category != '' || $subcategory != '')
                                {{$category}} {{' '.$subcategory}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Coupon quantity')}}</th>
                        <td>
                            @if ($model->price != null)
                                {{$model->price}} {{translate(' sum')}}
                            @elseif($model->percent != null)
                                {{$model->percent}} {{translate(' %')}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Company')}}</th>
                        <td>{{$model->company?$model->company->name:''}}</td>
                    </tr>
                    @if(isset($model->product))
                        <tr>
                            <th>{{__('Product')}}</th>
                            <td>{{$model->product->name??''}}</td>
                        </tr>
                    @endif
                    @if(isset($model->warehouse))
                        <tr>
                            <th>{{__('Warehouse')}}</th>
                            <td>
                                @if($model->warehouse->name != NULL)
                                    {{$model->warehouse->name}}
                                @else
                                    {{$model->product?$model->product->name:''}}
                                @endif
                               {{translate('Color: ')}} {{$model->warehouse->color?$model->warehouse->color->name.' ':''}}
                               {{translate('Size: ')}}{{$model->warehouse->size?$model->warehouse->size->name.' ':''}}
                               {{translate('Quantity: ')}}{{$model->warehouse->quantity}}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>{{__('Updated at')}}</th>
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