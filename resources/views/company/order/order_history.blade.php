@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    {{translate('Code')}}
                                </th>
                                <th>
                                    {{translate('Status')}}
                                </th>
                                <th>
                                    {{translate('Update at')}}
                                </th>
                                <th>
                                    {{translate('User')}}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i=0;
                            @endphp
                            @foreach($order_data as $order)
                                @php($i++)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$order['code']}}</td>
                                    <td>{{$order['status']}}</td>
                                    <td>{{$order['updated_at']}}</td>
                                    <td>{{$order['user_name']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>

@endsection
