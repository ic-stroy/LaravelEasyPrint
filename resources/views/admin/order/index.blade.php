@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">
                @switch($id)
                    @case(1)
                    {{translate('Basked orders list')}}
                    @break
                    @case(2)
                    {{translate('Ordered orders list')}}
                    @break
                    @case(3)
                    {{translate('Finished orders list')}}
                    @break
                @endswitch
            </h4>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Price')}}</th>
                        <th>{{translate('All price')}}</th>
                        <th>{{translate('Status')}}</th>
                        <th>{{translate('User')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i=0
                @endphp
                    @foreach($orders as $order)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('company_order.show', $order->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('company_order.show', $order->id)}}">
                                    @if(isset($order->price)){{ $order->price }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_order.show', $order->id)}}">
                                    @if(isset($order->all_price)){{ $order->all_price }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_order.show', $order->id)}}">
                                    @if($order->status)
                                        @switch($order->status)
                                            @case(1)
                                            {{translate('Basked')}}
                                            @break
                                            @case(2)
                                            {{translate('Ordered')}}
                                            @break
                                            @case(3)
                                            {{translate('Finished')}}
                                            @break
                                        @endswitch
                                    @else<div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company_order.show', $order->id)}}">
                                    @if(isset($order->user->personalInfo->id))
                                        @php
                                            $first_name = isset($order->user->personalInfo->first_name)?$order->user->personalInfo->first_name.' ':'';
                                            $last_name = isset($order->user->personalInfo->last_name)?$order->user->personalInfo->last_name.' ':'';
                                            $middle_name = isset($order->user->personalInfo->middle_name)?$order->user->personalInfo->middle_name:'';
                                            $user_name = $first_name.''.$last_name.''.$middle_name;
                                        @endphp
                                        {{$user_name}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('company_order.destroy', $order->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
