@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Category lists')}}</h4>
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table_body">
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <a class="show_page" href="{{route('company_order.index', \App\Constants::BASKED)}}">--}}
{{--                                {{translate('Basket')}}--}}
{{--                            </a>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::ORDERED)}}">
                                <span>{{translate('Ordered')}}<span class="badge bg-danger">{{translate('new')}}</span></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::PERFORMED)}}">
                                {{translate('Performed')}}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::CANCELLED)}}">
                                {{translate('Cancelled')}}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::ACCEPTED_BY_RECIPIENT)}}">
                                {{translate('Accepted by recipient')}}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
