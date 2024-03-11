@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Coupon lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('company_coupons.create')}}">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th>{{translate('Coupon quantity')}}</th>
                        <th>{{translate('Minimum price')}}</th>
                        <th>{{translate('Active')}}</th>
                        <th>{{translate('Quantity of orders')}}</th>
                        <th>{{translate('The number of order')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($coupons as $coupon)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    {{$i}}
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    @if(isset($coupon->name))
                                        {{$coupon->name}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    @if ($coupon->price != null)
                                       {{$coupon->price}} {{translate(' sum')}}
                                    @elseif($coupon->percent != null)
                                       {{$coupon->percent}} {{translate(' %')}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    @if(isset($coupon->min_price))
                                        {{$coupon->min_price}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    @if ($coupon->status == 1)
                                        {{translate('Active')}}
                                    @else
                                        {{translate('Not active')}}
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    @if($coupon->type == 0)
                                        @if(isset($coupon->order_count))
                                            {{$coupon->order_count}}
                                        @else
                                            <div class="no_text"></div>
                                        @endif
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_coupons.show', $coupon->id)}}">
                                    @if($coupon->type == 1)
                                        @if(isset($coupon->order_count))
                                            {{$coupon->order_count}}
                                        @else
                                            <div class="no_text"></div>
                                        @endif
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('company_coupons.edit', $coupon->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('company_coupons.destroy', $coupon->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
