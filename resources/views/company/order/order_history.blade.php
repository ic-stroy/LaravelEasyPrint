@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')

    <style>
        /*#headingNine{*/
        /*    height: auto;*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*}*/
        /*.function-column{*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*}*/
        /*.order_content{*/
        /*    display: flex;*/
        /*    flex-direction: column;*/
        /*    color:#98A6AD;*/
        /*}*/
        /*.color_order{*/
        /*    color:#98A6AD;*/
        /*}*/
        /*.white_text{*/
        /*    color:white*/
        /*}*/
        /*.carousel-inner{*/
        /*    padding:0px;*/
        /*}*/
        /*.order_product_images{*/
        /*    display: flex;*/
        /*    justify-content: center;*/
        /*}*/
        /*.color_green{*/
        /*    color:forestgreen;*/
        /*}*/
        /*.color_red{*/
        /*    color:red;*/
        /*}*/
        /*.color_reddish{*/
        /*    color:#ff8000;*/
        /*}*/
        /*.color_text{*/
        /*    width:24px;*/
        /*    height: 24px;*/
        /*    border-radius:50%;*/
        /*    border:solid 1px green*/
        /*}*/
        /*.hr_no_margin{*/
        /*    margin: 0px !important;*/
        /*}*/
        /*.order_content{*/
        /*    text-align: start;*/
        /*}*/
        /*.order_number{*/
        /*    font-family: Inter;*/
        /*    font-weight: 700;*/
        /*    font-size:13px;*/
        /*    line-height:20px;*/
        /*    color: black !important;*/
        /*}*/
        /*.order_created{*/
        /*    font-family: Inter;*/
        /*    font-weight: 500;*/
        /*    font-size:13px;*/
        /*    line-height: 18px;*/
        /*    color: black !important;*/
        /*    opacity: 0.8;*/
        /*}*/
        /*.order_created_date{*/
        /*    font-family: Inter;*/
        /*    font-weight: 500;*/
        /*    font-size:12px;*/
        /*    line-height: 18px;*/
        /*    color: black !important;*/
        /*    opacity: 0.6;*/
        /*}*/
        /*.order_payment{*/
        /*    font-family: Inter;*/
        /*    font-weight: 700;*/
        /*    font-size:11px;*/
        /*    line-height: 18px;*/
        /*    border-radius: 6px;*/
        /*    padding: 4px 8px;*/
        /*}*/
        /*.status{*/
        /*    font-family: Inter;*/
        /*    font-weight: 500;*/
        /*    font-size:13px;*/
        /*    line-height: 18px;*/
        /*    color: black !important;*/
        /*    margin-right: 8px;*/
        /*}*/
        /*.order_status{*/
        /*    font-family: Inter;*/
        /*    font-weight: 700;*/
        /*    font-size:11px !important;*/
        /*    line-height: 18px;*/
        /*    color: black !important;*/
        /*}*/
        /*.order_hr_05{*/
        /*    color: #CDCDCF;*/
        /*    height: 0.5px !important;*/
        /*    opacity: 0.4;*/
        /*    margin: 10px 0px;*/
        /*}*/
        /*.order_content_header{*/
        /*    font-family: Inter;*/
        /*    opacity: 0.7;*/
        /*    font-weight: 500;*/
        /*    font-size:13px;*/
        /*    line-height: 16px;*/
        /*    text-align: start;*/
        /*    margin-bottom: 6px;*/
        /*    color: black !important;*/
        /*}*/
        /*.order_cost{*/
        /*    font-family: Inter;*/
        /*    opacity: 0.7;*/
        /*    font-weight: 500;*/
        /*    font-size:10px;*/
        /*    line-height: 16px;*/
        /*    text-align: start;*/
        /*    margin-bottom: 6px;*/
        /*    color: black !important;*/
        /*}*/
        /*.order_content_item{*/
        /*    font-family: Inter;*/
        /*    font-weight: 800;*/
        /*    font-size:13px;*/
        /*    line-height: 18px;*/
        /*    text-align: start;*/
        /*    color: black !important;*/
        /*}*/
        /*.order_product_quantity{*/
        /*    font-family: Inter;*/
        /*    font-weight: 500;*/
        /*    font-size:13px;*/
        /*    line-height: 19px;*/
        /*    color: black !important;*/
        /*}*/
        /*.custom-accordion{*/
        /*    border-bottom: solid 1px;*/
        /*    color: #CDCDCF;*/
        /*}*/
        /*.bg_light{*/
        /*    background-color: white !important;*/
        /*    color: black !important;*/
        /*    font-weight: 700;*/
        /*    font-size: 11px;*/
        /*}*/
        /*.bg_info{*/
        /*    background-color: #CFF1FF !important;*/
        /*    color: black !important;*/
        /*    !*padding:8px 8px;*!*/
        /*    border-radius: 6px;*/
        /*    font-size: 13px;*/
        /*    width: min-content;*/
        /*}*/
        /*.products-content{*/
        /*    width: auto;*/
        /*    overflow: auto;*/
        /*    margin: 14px 0px;*/
        /*    background-color: white;*/
        /*    padding: 14px 0px;*/
        /*    border-radius: 8px;*/
        /*    color: black !important;*/
        /*}*/
        /*.btn-success{*/
        /*    background-color: #0EC568 !important;*/
        /*}*/
        /*.btn-default{*/
        /*    color:red !important;*/
        /*    background-color: #F7F7F7;*/
        /*    font-weight: 700;*/
        /*    border-radius: 6px !important;*/
        /*}*/
        /*.btn-info{*/
        /*    border-radius: 6px !important;*/
        /*}*/
        /*.images-content{*/
        /*    display: flex;*/
        /*    width: 100%;*/
        /*    overflow:auto;*/
        /*}*/
        /*.images-content img{*/
        /*    margin-right: 14px;*/
        /*}*/
        /*.accordion_arrow{*/
        /*    font-size: 24px;*/
        /*}*/
        /*.bg_warning{*/
        /*    background-color: #F8EAB9 !important;*/
        /*    color: black !important;*/
        /*    !*padding:8px 8px;*!*/
        /*    border-radius: 6px;*/
        /*    font-size: 13px;*/
        /*    width: min-content;*/
        /*}*/
        /*.bg_danger{*/
        /*    background-color: #FFE4E4 !important;*/
        /*    color: black !important;*/
        /*    !*padding:8px 8px;*!*/
        /*    border-radius: 6px;*/
        /*    font-size: 13px;*/
        /*    width: min-content;*/
        /*}*/
        /*.table_body tr td{*/
        /*    outline: 1px #3A4250 !important;*/
        /*}*/
        /*.modal-content{*/
        /*    width: auto !important;*/
        /*    margin-top: 20%;*/
        /*}*/
        /*.bg-warning{*/
        /*    background-color: #DDB732 !important;*/
        /*}*/
        /*.card-header {*/
        /*    padding: 1.005rem 1.5rem 0rem 1.5rem*/
        /*}*/
        /*.user-info-button{*/
        /*    text-decoration-line: underline;*/
        /*    text-decoration-style: solid;*/
        /*}*/
        /*.client_data{*/
        /*    border-radius: 20px;*/
        /*}*/
        /*.client-title{*/
        /*    font-family: Inter;*/
        /*    font-weight: 500;*/
        /*    font-size:13px;*/
        /*    line-height: 20px;*/
        /*    color: black !important;*/
        /*    opacity: 0.6;*/
        /*    margin-right: 7px;*/
        /*}*/
        /*.client-info{*/
        /*    padding: 8px 16px;*/
        /*}*/
        /*.client-data{*/
        /*    font-family: Inter;*/
        /*    font-weight: 500;*/
        /*    font-size:16px;*/
        /*    line-height: 20px;*/
        /*    color: black !important;*/
        /*}*/
    </style>
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
                                    {{translate('Client')}}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i=0;
                            @endphp
                            @forelse($order_data as $order)
                                @php($i++)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$order['code']}}</td>
                                    <td>{{$order['status']}}</td>
                                    <td>{{$order['updated_at']}}</td>
                                    <td>{{$order['user_name']}}</td>
                                </tr>
                            @empty
                                <span class="badge bg-warning">
                                    <h2>{{translate('No orders')}}</h2>
                                </span>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>

@endsection
