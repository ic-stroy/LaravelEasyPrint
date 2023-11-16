@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Category lists')}}</h4>
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table_body">
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', 1)}}">
                                {{translate('Basket')}}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', 2)}}">
                                {{translate('Ordered')}}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', 3)}}">
                                {{translate('Finished')}}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
