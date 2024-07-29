@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')

    <style>
        #headingNine{
            height: auto;
            display: flex;
            align-items: center;
        }
        #headingNine a{
            width: 100%;
            font-size: 15px;
        }
        #headingNine .card-header{
            padding: 14px;
        }
        .function-column{
            display: flex;
            align-items: center;
        }
        .function-column>div{
            width: 100%;
        }
        .order_content{
            display: flex;
            flex-direction: column;
            color:#98A6AD;
        }
        .color_order{
            color:#98A6AD;
        }
        .white_text{
            color:white
        }
        .carousel-control-prev, .carousel-control-next{
            top:50%;
            background-color: transparent;
        }
        .carousel-control-prev{
            margin-left: -30px;
        }
        .carousel-control-next{
            margin-right: -30px;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon{
            color:#6C8BC0 !important;
            width: 34px;
            height: 34px;
        }
        .carousel-inner{
            padding:0px;
        }
        .order_product_images{
            display: flex;
            justify-content: center;
        }
        .order_product_images>img{
            transition: 0.4s;
        }
        .order_product_images>img:hover{
            transform: scale(1.14);
        }
        .color_green{
            color:forestgreen;
        }
        .color_red{
            color:red;
        }
        .color_reddish{
            color:#ff8000;
        }
        .color_text{
            width:24px;
            height: 24px;
            border-radius:50%;
            border:solid 1px green
        }
        .table.dataTable.nowrap th, table.dataTable.nowrap td{
            white-space: normal !important;
        }
        .custom-accordion .card{
            border:0px !important;
        }
        .table-striped{
            width:100% !important;
        }
        .card{
            margin: 0px !important;
        }
        .table.dataTable tbody td.focus, table.dataTable tbody th.focus{
            color: rgb(0, 0, 0, 0.54) !important;
        }
        .width_auto{
            width: auto !important;
        }
        .hr_no_margin{
            margin: 0px !important;
        }
        .order_content{
            text-align: start;
        }
        .order_number{
            font-family: Inter;
            font-weight: 500;
            font-size:14px;
            line-height:20px;
        }
        .order_created{
            font-family: Manrope;
            font-weight: 500;
            font-size:13px;
            line-height: 18px;
            opacity: 0.8;
        }
        .order_created_date{
            font-family: Manrope;
            font-weight: 500;
            font-size:12px;
            line-height: 18px;
            opacity: 0.6;
        }
        .order_payment{
            font-family: Manrope;
            font-weight: 700;
            font-size:11px;
            line-height: 18px;
            border-radius: 6px;
            padding: 4px 8px;
        }
        .status{
            font-family: Manrope;
            font-weight: 500;
            font-size:13px;
            line-height: 18px;
            margin-right: 8px;
        }
        .order_status{
            font-family: Manrope;
            font-weight: 700;
            font-size:11px !important;
            line-height: 18px;
        }
        .order_hr_05{
            color: #808080;
            height: 0.5px !important;
            opacity: 0.2;
            margin: 10px 0px;
        }
        .order_content_header{
            font-family: Manrope;
            opacity: 0.7;
            font-weight: 500;
            font-size:11px;
            line-height: 16px;
            text-align: start;
            margin-bottom: 6px;
        }
        .order_cost{
            font-family: Manrope;
            opacity: 0.7;
            font-weight: 500;
            font-size:9px;
            line-height: 16px;
            text-align: start;
            margin-bottom: 6px;
        }
        .order_content_item{
            font-family: Manrope;
            font-weight: 600;
            font-size:12px;
            line-height: 18px;
            text-align: start;
        }
        .order_product_quantity{
            font-family: Inter;
            font-weight: 400;
            font-size:13px;
            line-height: 19px;
        }
        .custom-accordion{
            border-bottom: solid;
            color: #808080;
        }
        .bg_light{
            background-color: white !important;
            color: #808096 !important;
        }
        .products-content{
            width: auto;
            overflow: auto;
            margin: 14px 0px;
            background-color: white;
            padding: 14px 0px;
            border-radius: 8px;
        }
        .btn-success{
            background-color: #0EC568 !important;
        }
        .btn-default{
            color:red !important;
            background-color: #F7F7F7;
            font-weight: 700;
            border-radius: 6px !important;
        }
        .btn-info{
            border-radius: 6px !important;
        }
        .images-content{
            display: flex;
            width: 100%;
            overflow:auto;
        }
        .images-content img{
            margin-right: 14px;
        }
        .accordion_arrow{
            font-size: 24px;
        }
        .bg_warning{
            background-color: #F8EAB9 !important;
            color: black !important;
            /*padding:8px 8px;*/
            border-radius: 6px;
            font-size: 12px;
            width: min-content;
        }
        .bg_danger{
            background-color: #FFE4E4 !important;
            color: black !important;
            /*padding:8px 8px;*/
            border-radius: 6px;
            font-size: 12px;
            width: min-content;
        }
        .table_body tr td{
            border-color: #3A4250 !important;
            outline: 1px #3A4250 !important;
        }
    </style>
    @if(!empty($all_orders['orderedOrders']) || !empty($all_orders['performedOrders']) || !empty($all_orders['cancelledOrders']) || !empty($all_orders['deliveredOrders']) || !empty($all_orders['acceptedByRecipientOrders']))
        <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-center">
                                <i class="dripicons-warning h1 text-success"></i>
                                <h4 class="mt-2">{{ translate('Are you sure you want to accept this order')}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-around" id="product_image"></div>
                            <div id="product_name"></div>
                            <div id="order_size"></div>
                            <div id="order_color"></div>
                            <div id="remaining_quantity"></div>
                            <div id="order_quantity"></div>
                            <div id="order_url"></div>
                        </div>
                        <div class="card-footer">
                            <form class="d-flex justify-content-between" action="" method="POST" id="perform_order_">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
                                <button type="submit" class="btn btn-success my-2" id="perform_order_button"> {{ translate('Yes')}} </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="accepted-by-recipient-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('This order accepted by user ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="accepted_by_recipient_order">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="order-delivered-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('This order accepted by user ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="order_delivered">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="cancell-accepted-by-recipient-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('This order not accepted by user ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_accepted_by_recipient_order">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="cancell-order-delivered-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('Are you going to cancell to deliver order?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_order_delivered">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="carousel-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #989CA2">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel_product_images">

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- /.modal -->
        <div id="images-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #989CA2">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="product_image_content">

                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- /.modal -->
        <div id="carousel-upload-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #989CA2;">
                    <div id="carouselExample_Fade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel_product_upload_images"></div>
                        <a class="carousel-control-prev" href="#carouselExample_Fade" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample_Fade" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                    <div class="d-none" id="carousel-upload-modal-order">
                    <span class="badge bg-warning">
                        <h2>{{translate('No orders')}}</h2>
                    </span>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="warning-order-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-warning"></i>
                            <h4 class="mt-2">{{ translate('Are you sure to cancel this order')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_order">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-warning my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="accepted-success-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            {{--                        <h4 class="mt-2">{{translate('Accepted by super admin')}}</h4>--}}
                            <h4 class="mt-2">{{translate('Performed by admin')}}</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="accepted-by-recepient-success-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{translate('Accepted by recepient')}}</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#ordered" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            {{translate('Ordered')}}
                            @if(count($all_orders['orderedOrders']) > 0)
                                <span class="badge bg-danger"> {{translate('new')}} {{count($all_orders['orderedOrders'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#performed" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Performed')}}
                            @if(count($all_orders['performedOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['performedOrders'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#cancelled" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Cancelled')}}
                            @if(count($all_orders['cancelledOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['cancelledOrders'])<101?count($all_orders['cancelledOrders']):'+101'}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#order__delivered" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Delivered')}}
                            @if(count($all_orders['deliveredOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['deliveredOrders'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#accepted_by_recepient" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Accepted by recepient')}}
                            @if(count($all_orders['acceptedByRecipientOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['acceptedByRecipientOrders'])<101?count($all_orders['acceptedByRecipientOrders']):'+101'}}</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    @php
                        $i=0;
                    @endphp
                    @foreach($all_orders as $key_order => $all_order)
                        @switch($key_order)
                            @case("orderedOrders")
                            <div class="tab-pane show active" id="ordered">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th>
                                            <h4 class="mt-0 header-title">{{translate('Ordered orders list')}}</h4>
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                                    @case("performedOrders")
                                    <div class="tab-pane" id="performed">
                                        <table id="selection-datatable" class="table table-striped table-bordered dt-responsive">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <h4 class="mt-0 header-title">{{translate('Performed orders list')}}</h4>
                                                </th>
                                            </tr>
                                            </thead>
                                            @break
                                            @case("cancelledOrders")
                                            <div class="tab-pane" id="cancelled">
                                                <table id="key-table" class="table table-striped table-bordered dt-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th class="d-flex justify-content-between width_auto">
                                                            <h4 class="mt-0 header-title">{{translate('Cancelled orders list')}}</h4>
                                                            @if(count($all_orders['cancelledOrders'])>100)
                                                                <a href="{{route('company_order.finished_all_orders')}}">{{translate('All cancelled orders')}}</a>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    @break
                                                    @case("deliveredOrders")
                                                    <div class="tab-pane" id="order__delivered">
                                                        <table class="table table-striped table-bordered dt-responsive">
                                                            <thead>
                                                            <tr>
                                                                <th class="d-flex justify-content-between width_auto">
                                                                    <h4 class="mt-0 header-title">{{translate('Delivered orders list')}}</h4>
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            @break
                                                            @case("acceptedByRecipientOrders")
                                                            <div class="tab-pane" id="accepted_by_recepient">
                                                                <table id="responsive-datatable" class="table table-striped table-bordered dt-responsive">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="d-flex justify-content-between width_auto">
                                                                            <h4 class="mt-0 header-title">{{translate('Accepted recepient orders list')}}</h4>
                                                                            @if(count($all_orders['acceptedByRecipientOrders'])>100)
                                                                                <a href="{{route('company_order.finished_all_orders')}}">{{translate('All cancelled orders')}}</a>
                                                                            @endif
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    @break
                                                                    @endswitch
                                                                    <tbody class="table_body">
                                                                    @foreach($all_order as $order)
                                                                        @php
                                                                            $i++;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div class="accordion custom-accordion">
                                                                                    <div class="card mb-0">
                                                                                        <div class="card-header" id="headingNine">
                                                                                            @if($order['order'])
                                                                                                <span class="m-0 position-relative" style="width: 100%">
                                                            <div class="custom-accordion-title text-reset d-block">
                                                                <div class="row text-start d-flex align-items-center">
                                                                    <div class="col-3">
                                                                        <span class="order_number">{{translate('Order number')}} {{$order['order']->code}}</span>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <span class="order_created">{{translate('The order was created in: ')}} <span class="order_created_date">{{$order['order']->updated_at}}</span></span>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <span class="badge bg-info order_payment">
                                                                            @switch($order['order']->payment_method)
                                                                                @case(\App\Constants::CASH_ON_DELIVERY)
                                                                                {{translate('Payment upon receipt')}}
                                                                                @break
                                                                                @case(\App\Constants::BANK_CARD)
                                                                                {{translate('Online payment')}}
                                                                                @break
                                                                            @endswitch
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-2 text-end">
                                                                        <span class="status">{{translate('Status:')}}</span>
                                                                        @switch($order['order']->status)
                                                                            @case(\App\Constants::ORDERED)
                                                                            <span class="badge bg_light order_status">{{translate('New order')}}</span>
                                                                            @break
                                                                            @case(\App\Constants::PERFORMED)
                                                                            <span class="badge bg_warning order_status">{{translate('Performed')}}</span>
                                                                            @break
                                                                            @case(\App\Constants::CANCELLED)
                                                                            <span class="badge bg_danger order_status">{{translate('Cancelled')}}</span>
                                                                            @break
                                                                            @case(\App\Constants::ORDER_DELIVERED)
                                                                            <span class="badge bg_danger order_status">{{translate('Delivered')}}</span>
                                                                            @break
                                                                            @case(\App\Constants::ACCEPTED_BY_RECIPIENT)
                                                                            <span class="badge bg_success order_status">{{translate('Accepted by recipient')}}</span>
                                                                            @break
                                                                        @endswitch
                                                                    </div>
                                                                </div>
                                                                <hr class="order_hr_05">
                                                                <div class="row text-start">
                                                                    <div class="col-3">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="order_content_header">{{translate('Customer')}}</span>
                                                                            <span class="order_content_item">{{$order['user_name']}}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="order_content_header">{{translate('Address of the recipient:')}}</span>
                                                                            @if(!empty($order['order']->address))
                                                                                <span class="order_content_item">
                                                                                    @if(!empty($order['order']->address->cities))
                                                                                        @if(!empty($order['order']->address->cities->region))
                                                                                            {{$order['order']->address->cities->region->name??""}}
                                                                                        @endif
                                                                                        {{$order['order']->address->cities->name??""}}
                                                                                    @endif
                                                                                    {{$order['order']->address->name??''}}
                                                                                    {{$order['order']->address->postcode??''}}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @if($order['performed_company_product_price'] > 0)
                                                                        @if($order['performed_product_types'] > 0)
                                                                            <div class="col-2">
                                                                                <div class="d-flex flex-column">
                                                                                    <span class="order_content_header">{{translate('Order price:')}}</span>
                                                                                    <span class="order_content_item">
                                                                                        {{$order['performed_company_product_price'] + $order['performed_order_coupon_price'] + $order['performed_company_discount_price']}}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            @if($order['performed_company_discount_price']>0)
                                                                                <div class="col-2">
                                                                                    <div class="d-flex flex-column">
                                                                                        <span class="order_content_header">{{translate('Discount:')}}</span>
                                                                                        <span class="order_content_item">
                                                                                            {{$order['performed_company_discount_price']}}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            @if($order['performed_order_coupon_price'] > 0)
                                                                                <span class="order_content_item">{{translate('Promo code:')}}
                                                                                    @if($order['performed_order_coupon_price'] > 0)
                                                                                        <span class="order_content_header">{{$order['performed_order_coupon_price']}}</span>
                                                                                    @endif
                                                                                </span>
                                                                            @endif
                                                                            <div class="col-2">
                                                                                <div class="d-flex flex-column">
                                                                                    <span class="order_content_header">{{translate('Total amount:')}}</span>
                                                                                    <span class="order_content_item">{{$order['performed_company_product_price']}}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @elseif($order['company_product_price']>0)
                                                                        <div class="col-2">
                                                                            <div class="d-flex flex-column">
                                                                                <span class="order_content_header">{{translate('Order price:')}}</span>
                                                                                <span class="order_content_item">{{$order['company_product_price'] + $order['order_coupon_price'] + $order['company_discount_price']}}</span>
                                                                            </div>
                                                                        </div>
                                                                        @if($order['company_discount_price']>0)
                                                                            <div class="col-2">
                                                                                <div class="d-flex flex-column">
                                                                                    <span class="order_content_header">{{translate('Discount:')}}</span>
                                                                                    <span class="order_content_item">
                                                                                        {{$order['company_discount_price']}}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        @if($order['order_coupon_price'] > 0)
                                                                            <span class="order_content_item">{{translate('Promo code:')}}
                                                                                <span class="order_content_header">{{$order['order_coupon_price']}}</span>
                                                                            </span>
                                                                        @endif
                                                                        <div class="col-2">
                                                                            <div class="d-flex flex-column">
                                                                                <span class="order_content_header">{{translate('Total amount:')}}</span>
                                                                                <span class="order_content_item">{{$order['company_product_price']}}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <hr class="order_hr_05">
                                                                <div id="collapseNine{{$i}}" class="collapse fade product_card_"
                                                                     aria-labelledby="headingFour"
                                                                     data-bs-parent="#custom-accordion-one">
                                                                    @foreach($order['products'] as $products)
                                                                        @php
                                                                            if(!empty($products[0]->warehouse) && $products[0]->warehouse->name){
                                                                                $product_name = $products[0]->warehouse->name??'';
                                                                            }else if(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name){
                                                                                $product_name = $products[0]->warehouse->product->name??'';
                                                                            }
                                                                            $product_costs = (int)$products[0]->quantity*(int)$products[0]->price;
                                                                        @endphp
                                                                        <div class="row products-content">
                                                                            <div class="col-7 d-flex align-items-center">
                                                                                <div class="images-content">
                                                                                    @foreach($products['images'] as $product_images)
                                                                                        <img onclick="showImage('{{$product_images}}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="{{$product_images?$product_images:asset('icon/no_photo.jpg')}}" alt="" height="94px">
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="d-flex flex-column text-start" style="width: 100%">
                                                                                    <span class="order_number">{{$product_name??''}}</span>
                                                                                    @if(!empty($products[0]->size))
                                                                                        <span class="order_content_item">{{translate('Size:')}} <span class="order_content_header">{{translate($products[0]->size->name)}}</span></span>
                                                                                    @endif
                                                                                    @if(!empty($products[0]->color))
                                                                                        <span class="order_content_item">{{translate('Color:')}} <span class="order_content_header">{{translate($products[0]->color->name)}}</span></span>
                                                                                    @endif
                                                                                    <span class="order_content_item">{{translate('Quantity:')}} <span class="order_content_header">{{(int)$products[0]->quantity}}</span></span>
                                                                                    @if((int)$products[0]->discount_price > 0)
                                                                                        @if($products['discount_withouth_expire'] > 0)
                                                                                            <span class="order_content_item">{{translate('Discount:')}} <span class="order_content_header">{{(int)$products['discount_withouth_expire']}} %</span></span>
                                                                                        @elseif($products['product_discount_withouth_expire'] > 0)
                                                                                            <span class="order_content_item">{{translate('Discount')}}: <span class="order_content_header">{{(int)$products['product_discount_withouth_expire']}} %</span></span>
                                                                                        @endif
                                                                                    @endif
                                                                                    <span class="order_content_item">{{translate('Price:')}} <span class="order_content_header">{{$products[1]}}</span> @if($products[1]<$product_costs)<del class="order_cost">{{$product_costs}}</del>@endif</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 d-flex align-items-center">
                                                                                <div class="d-flex flex-column justify-content-around" style="height:80px">
                                                                                    @switch($key_order)
                                                                                        @case("orderedOrders")
                                                                                        @switch($products[0]->status)
                                                                                            @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                    onclick='accepting_order(
                                                                                                        "{{(int)$products[0]->quantity}}",
                                                                                                        "{{(int)$products[0]->warehouse->quantity + (int)$products[0]->quantity  }}",
                                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                        "{{$product_name}}",
                                                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                        )'>
                                                                                                    {{translate('Accept')}}
                                                                                                </button>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                            <div class="text-end">
                                                                                                        <span class="badge bg_warning">{{translate('Performed')}}</span>
                                                                                                    </div>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                            <div class="text-end">
                                                                                                        <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                    </div>
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                    onclick='accepting_order(
                                                                                                        "{{(int)$products[0]->quantity}}",
                                                                                                        "{{(int)$products[0]->warehouse->quantity}}",
                                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                        "{{$product_name}}",
                                                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                        )'>
                                                                                                        {{translate('Accept')}}
                                                                                                    </button>
                                                                                            @break
                                                                                            {{--                                                                                                @case(\App\Constants::ORDER_DELIVERED)--}}
                                                                                            {{--                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                            {{--                                                                                                @break--}}
                                                                                            {{--                                                                                                @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)--}}
                                                                                            {{--                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recepient-success-modal" title="{{translate('Order accepted by recipient')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                            {{--                                                                                                @break--}}
                                                                                        @endswitch
                                                                                        @break
                                                                                        @case("performedOrders")
                                                                                        @switch($products[0]->status)
                                                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#order-delivered-modal" onclick='order_delivered("{{route('order_delivered', $order['order']->id)}}")' data-url="">{{translate('Delivered')}}</button>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                            <div class="text-end">
                                                                                                    <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                </div>
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                    onclick='accepting_order(
                                                                                                        "{{(int)$products[0]->quantity}}",
                                                                                                        "{{(int)$products[0]->warehouse->quantity}}",
                                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                        "{{$product_name}}",
                                                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                        )'>
                                                                                                    {{translate('Perform')}}
                                                                                                </button>
                                                                                            @break
                                                                                        @endswitch
                                                                                        @break
                                                                                        @case("cancelledOrders")
                                                                                        <div class="text-end">
                                                                                                <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                            </div>
                                                                                        <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                onclick='accepting_order(
                                                                                                    "{{(int)$products[0]->quantity}}",
                                                                                                    "{{(int)$products[0]->warehouse->quantity}}",
                                                                                                    "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                    "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                    "{{$product_name}}",
                                                                                                    "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                    "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                    "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                    )'>
                                                                                                {{translate('Accept')}}
                                                                                            </button>
                                                                                        @break
                                                                                        @case("deliveredOrders")
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#order-delivered-modal" onclick='order_delivered("{{route('order_delivered', $order['order']->id)}}")' data-url="">{{translate('Delivered')}}</button>
                                                                                        <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-order-delivered-modal" onclick='cancell_order_delivered("{{route('cancell_order_delivered', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                        @break
                                                                                        @case("acceptedByRecipientOrders")
                                                                                        <div class="d-flex justify-content-around">
                                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>
                                                                                        </div>
                                                                                        @break
                                                                                    @endswitch
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    @foreach($order['products_with_anime'] as $products_with_anime)
                                                                        @php
                                                                            if(!empty($products_with_anime[0]->product)){
                                                                                $product_name = $products_with_anime[0]->product->name??'';
                                                                            }
                                                                            $products_with_anime_costs = (int)$products_with_anime[0]->quantity*(int)$products_with_anime[0]->price;
                                                                        @endphp
                                                                        <div class="row products-content">
                                                                            <div class="col-7 d-flex align-items-center">
                                                                                <div class="images-content">
                                                                                    @foreach($products_with_anime['images'] as $product_anime_images)
                                                                                        <img onclick='showImage("{{$product_anime_images}}")' data-bs-toggle="modal" data-bs-target="#images-modal" src="{{$product_anime_images?$product_anime_images:asset('icon/no_photo.jpg')}}" alt="" height="94px">
                                                                                    @endforeach
                                                                                    @foreach($products_with_anime[2] as $product_anime_images_)
                                                                                        <img onclick='showImage("{{$product_anime_images_}}")' data-bs-toggle="modal" data-bs-target="#images-modal" src="{{$product_anime_images_?$product_anime_images_:asset('icon/no_photo.jpg')}}" alt="" height="94px">
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="d-flex flex-column text-start" style="width: 100%">
                                                                                    <span class="order_number">{{$product_name??''}}</span>
                                                                                    @if(!empty($products_with_anime[0]->size))
                                                                                        <span class="order_content_item">{{translate('Size:')}} <span class="order_content_header">{{translate($products_with_anime[0]->size->name)}}</span></span>
                                                                                    @endif
                                                                                    @if(!empty($products_with_anime[0]->color))
                                                                                        <span class="order_content_item">{{translate('Color:')}} <span class="order_content_header">{{translate($products_with_anime[0]->color->name)}}</span></span>
                                                                                    @endif
                                                                                    <span class="order_content_item">{{translate('Quantity:')}} <span class="order_content_header">{{(int)$products_with_anime[0]->quantity}}</span></span>
                                                                                    @if((int)$products_with_anime[0]->discount_price > 0)
                                                                                        @if($products_with_anime['product_discount_withouth_expire'] > 0)
                                                                                            <span class="order_content_item">{{translate('Discount')}}: <span class="order_content_header">{{(int)$products_with_anime['product_discount_withouth_expire']}} %</span></span>
                                                                                        @endif
                                                                                    @endif
                                                                                    <span class="order_content_item">{{translate('Price:')}} <span class="order_content_header">{{$products_with_anime[1]}}</span> @if($products_with_anime[1]<$products_with_anime_costs)<del class="order_cost">{{$products_with_anime_costs}}</del>@endif</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 d-flex align-items-center">
                                                                                <div class="d-flex flex-column justify-content-around" style="height:80px">
                                                                                    @switch($key_order)
                                                                                        @case("orderedOrders")
                                                                                        @switch($products_with_anime[0]->status)
                                                                                            @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"
                                                                                                    onclick='accepting_anime_order(
                                                                                                        "{{$products_with_anime[0]->quantity??''}}",
                                                                                                        "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                                                        "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",
                                                                                                        "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}"
                                                                                                        )' data-url="">
                                                                                                    {{translate('Accept')}}
                                                                                                </button>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                            <div class="text-end">
                                                                                                        <span class="badge bg_warning">{{translate('Performed')}}</span>
                                                                                                    </div>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                            <div class="text-end">
                                                                                                    <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                </div>
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"
                                                                                                    onclick='accepting_anime_order(
                                                                                                        "{{$products_with_anime[0]->quantity??''}}",
                                                                                                        "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                                                        "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",
                                                                                                        "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}"
                                                                                                        )' data-url="">
                                                                                                    {{translate('Accept')}}
                                                                                                </button>
                                                                                            @break
                                                                                            {{--                                                                                                @case(\App\Constants::ORDER_DELIVERED)--}}
                                                                                            {{--                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                            {{--                                                                                                @break--}}
                                                                                            {{--                                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)--}}
                                                                                            {{--                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                            {{--                                                                                                @break--}}
                                                                                            {{--                                                                                                @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)--}}
                                                                                            {{--                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recepient-success-modal" title="{{translate('Order accepted by recipient')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                            {{--                                                                                                @break--}}
                                                                                        @endswitch
                                                                                        @break
                                                                                        @case("cancelledOrders")
                                                                                        <div class="text-end">
                                                                                            <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                        </div>
                                                                                        <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                onclick='accepting_anime_order(
                                                                                                    "{{$products_with_anime[0]->quantity??''}}",
                                                                                                    "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                                                    "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                                                    "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                                                    "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",
                                                                                                    "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",
                                                                                                    "{{route('perform_order_detail', $products_with_anime[0]->id)}}")' >
                                                                                            {{translate('Accept')}}
                                                                                        </button>
                                                                                        @break
                                                                                        @case("performedOrders")
                                                                                        @switch($products_with_anime[0]->status)
                                                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#order-delivered-modal" onclick='order_delivered("{{route('order_delivered', $order['order']->id)}}")' data-url="">{{translate('Delivered')}}</button>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                    onclick='accepting_anime_order(
                                                                                                        "{{$products_with_anime[0]->quantity??''}}",
                                                                                                        "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                                                        "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",
                                                                                                        "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}")'>
                                                                                                {{translate('Accept')}}
                                                                                            </button>
                                                                                            @break
                                                                                        @endswitch
                                                                                        @break
                                                                                        @case("deliveredOrders")
                                                                                        @switch($products_with_anime[0]->status)
                                                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>
                                                                                            <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                            @break
                                                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                    onclick='accepting_order(
                                                                                                        "{{(int)$products_with_anime[0]->quantity}}",
                                                                                                        "{{(int)$products_with_anime[0]->warehouse->quantity}}",
                                                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                                                        "{{$product_name}}",
                                                                                                        "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",
                                                                                                        "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}"
                                                                                                        )'>
                                                                                                {{translate('Accept')}}
                                                                                            </button>
                                                                                            @break
                                                                                        @endswitch
                                                                                        @break
                                                                                        @case("acceptedByRecipientOrders")
                                                                                        <div class="d-flex justify-content-around">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>
                                                                                    </div>
                                                                                        @break
                                                                                    @endswitch
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="row text-start">
                                                                    <a class="d-flex justify-content-between
                                                                    custom-accordion-title text-reset d-block"
                                                                       data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                                                       aria-expanded="true" aria-controls="collapseNine">
                                                                        <span class="order_product_quantity">{{$order['products_quantity']}} {{translate('products')}}</span>
                                                                        <span>
                                                                            <i class="mdi mdi-chevron-down accordion_arrow"></i>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                                                            @endif
                                                                                        </div>

                                                                                        {{--                                                    <div id="collapseNine{{$i}}" class="collapse fade product_card_"--}}
                                                                                        {{--                                                         aria-labelledby="headingFour"--}}
                                                                                        {{--                                                         data-bs-parent="#custom-accordion-one">--}}
                                                                                        {{--                                                        @foreach($order['products'] as $products)--}}
                                                                                        {{--                                                            @php--}}
                                                                                        {{--                                                                if(!empty($products[0]->warehouse) && $products[0]->warehouse->name){--}}
                                                                                        {{--                                                                    $product_name = $products[0]->warehouse->name??'';--}}
                                                                                        {{--                                                                }else if(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name){--}}
                                                                                        {{--                                                                    $product_name = $products[0]->warehouse->product->name??'';--}}
                                                                                        {{--                                                                }--}}
                                                                                        {{--                                                            @endphp--}}
                                                                                        {{--                                                            <div class="row products-content">--}}
                                                                                        {{--                                                                <div class="col-7">--}}
                                                                                        {{--                                                                    <div class="row">--}}
                                                                                        {{--                                                                        @foreach($products['images'] as $product_images)--}}
                                                                                        {{--                                                                            <div class="col-2">--}}
                                                                                        {{--                                                                                <img onclick='showImage({{$product_images}})' data-bs-toggle="modal" data-bs-target="#images-modal" src="{{$product_images?$product_images:asset('icon/no_photo.jpg')}}" alt="" height="94px">--}}
                                                                                        {{--                                                                            </div>--}}
                                                                                        {{--                                                                        @endforeach--}}
                                                                                        {{--                                                                    </div>--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="col-3">--}}
                                                                                        {{--                                                                    <div class="d-flex flex-column text-start" style="width: 100%">--}}
                                                                                        {{--                                                                        <span class="order_number">{{$product_name??''}}</span>--}}
                                                                                        {{--                                                                        @if(!empty($products[0]->size))--}}
                                                                                        {{--                                                                            <span class="order_content_item">{{translate('Size:')}} <span class="order_content_header">{{translate($products[0]->size->name)}}</span></span>--}}
                                                                                        {{--                                                                        @endif--}}
                                                                                        {{--                                                                        @if(!empty($products[0]->color))--}}
                                                                                        {{--                                                                            <span class="order_content_item">{{translate('Color:')}} <span class="order_content_header">{{translate($products[0]->color->name)}}</span></span>--}}
                                                                                        {{--                                                                        @endif--}}
                                                                                        {{--                                                                        <span class="order_content_item">{{translate('Quantity:')}} <span class="order_content_header">{{(int)$products[0]->quantity}}</span></span>--}}
                                                                                        {{--                                                                        @if((int)$products[0]->discount_price > 0)--}}
                                                                                        {{--                                                                            @if($products['discount_withouth_expire'] > 0)--}}
                                                                                        {{--                                                                                <span class="order_content_item">{{translate('Discount:')}} <span class="order_content_header">{{(int)$products['discount_withouth_expire']}} %</span></span>--}}
                                                                                        {{--                                                                            @elseif($products['product_discount_withouth_expire'] > 0)--}}
                                                                                        {{--                                                                                <span class="order_content_item">{{translate('Discount')}}: <span class="order_content_header">{{(int)$products['product_discount_withouth_expire']}} %</span></span>--}}
                                                                                        {{--                                                                            @endif--}}
                                                                                        {{--                                                                        @endif--}}
                                                                                        {{--                                                                        <span class="order_content_item">{{translate('Price:')}} <span class="order_content_header"></span></span>--}}
                                                                                        {{--                                                                    </div>--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="col-2">--}}

                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                            </div>--}}

                                                                                        {{--                                                        @endforeach--}}
                                                                                        {{--                                                    </div>--}}
                                                                                        {{--                                                    <div id="collapseNine{{$i}}" class="collapse fade product_card_"--}}
                                                                                        {{--                                                         aria-labelledby="headingFour"--}}
                                                                                        {{--                                                         data-bs-parent="#custom-accordion-one">--}}
                                                                                        {{--                                                        @foreach($order['products_with_anime'] as $products_with_anime)--}}
                                                                                        {{--                                                            <hr class="hr_no_margin">--}}
                                                                                        {{--                                                            <div class="row" style="margin:20px 0px">--}}
                                                                                        {{--                                                                <div class="col-2 order_product_images">--}}
                                                                                        {{--                                                                    <img  onclick='getImages("{{implode(" ", $products_with_anime['images'])}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal" src="{{!empty($products_with_anime['images'])?$products_with_anime['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                @if(!empty($products_with_anime[2]))--}}
                                                                                        {{--                                                                    <div class="col-2 order_product_images">--}}
                                                                                        {{--                                                                        <img onclick='getUploads("{{implode(" ", $products_with_anime[2])}}")' data-bs-toggle="modal" data-bs-target="#carousel-upload-modal" src="{{!empty($products_with_anime[2])?$products_with_anime[2][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">--}}
                                                                                        {{--                                                                    </div>--}}
                                                                                        {{--                                                                @else--}}
                                                                                        {{--                                                                    <div class="col-2 order_product_images"></div>--}}
                                                                                        {{--                                                                @endif--}}
                                                                                        {{--                                                                <div class="col-4 order_content">--}}
                                                                                        {{--                                                                    <h4>{{translate('Animated order')}}</h4>--}}
                                                                                        {{--                                                                    <span>{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}</span>--}}
                                                                                        {{--                                                                    @if($products_with_anime['product_discount_withouth_expire'] > 0 && (int)$products_with_anime[0]->discount_price > 0 )--}}
                                                                                        {{--                                                                        <span>{{translate('Discount')}}: <b style="color:red">{{$products_with_anime['product_discount_withouth_expire']}} %</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if($products_with_anime[1])--}}
                                                                                        {{--                                                                        <span>{{translate('Price')}}: <b>{{$products_with_anime[0]->price}}</b> {!! $products_with_anime[0]->quantity?translate('Quantity').": <b>". $products_with_anime[0]->quantity."</b>":'' !!}</span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if($products_with_anime[1])--}}
                                                                                        {{--                                                                        <span>{{translate('Sum')}}: <b>{{$products_with_anime[1]}}</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if(!empty($products_with_anime[0]->size))--}}
                                                                                        {{--                                                                        <span>{{translate('Size')}}: <b>{{$products_with_anime[0]->size->name}}</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if(!empty($products_with_anime[0]->color))--}}
                                                                                        {{--                                                                        <span>{{translate('Color')}}: <b>{{$products_with_anime[0]->color->name}}</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if(!empty($products_with_anime[0]->font))--}}
                                                                                        {{--                                                                        <span>{{translate('Text font')}}: <b>{{$products_with_anime[0]->font}}</b>--}}
                                                                                        {{--                                                                            {!! $products_with_anime[0]->font_text?translate('Text').": <b>". $products_with_anime[0]->font_text."</b>":'' !!}--}}
                                                                                        {{--                                                                             {!! $products_with_anime[0]->font_color?translate('Text color').": <div class='color_text' style=".--}}
                                                                                        {{--                                                                              'background-color:'.$products_with_anime[0]->font_color."></div>":'' !!}--}}
                                                                                        {{--                                                                        </span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if(!empty($products_with_anime[0]->updated_at))--}}
                                                                                        {{--                                                                        <span>{{translate('Ordered')}}: <b>{{$products_with_anime[0]->updated_at}}</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="col-1 d-flex justify-content-around align-items-center">--}}
                                                                                        {{--                                                                    @switch($products_with_anime[0]->status)--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-danger">{{translate('New')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-success">{{translate('Performed')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-info">{{translate('Finished')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                    @endswitch--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="function-column col-3">--}}

                                                                                        {{--                                                                    @switch($key_order)--}}
                                                                                        {{--                                                                        @case("orderedOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            @switch($products_with_anime[0]->status)--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_ORDERED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"--}}
                                                                                        {{--                                                                                        onclick='accepting_anime_order(--}}
                                                                                        {{--                                                                                            "{{$products_with_anime[0]->quantity??''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products_with_anime[0]->id)}}"--}}
                                                                                        {{--                                                                                            )' data-url="">--}}
                                                                                        {{--                                                                                    <i class="fa fa-check"></i>--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)--}}
                                                                                        {{--                                                                                                                                    <button type="button" class="btn btn-warning delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#waiting-to-perform-alert-modal" title="{{translate('Waiting for superadmin performing')}}"><i class="fa fa-question"></i></button>--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"--}}
                                                                                        {{--                                                                                        onclick='accepting_anime_order(--}}
                                                                                        {{--                                                                                            "{{$products_with_anime[0]->quantity??''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products_with_anime[0]->id)}}"--}}
                                                                                        {{--                                                                                            )' data-url="">--}}
                                                                                        {{--                                                                                    <i class="fa fa-check"></i>--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recepient-success-modal" title="{{translate('Order accepted by recipient')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                            @endswitch--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case("cancelledOrders")--}}
                                                                                        {{--                                                                        <div class="function-column col-3">--}}
                                                                                        {{--                                                                            <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""--}}
                                                                                        {{--                                                                                        onclick='accepting_anime_order(--}}
                                                                                        {{--                                                                                            "{{$products_with_anime[0]->quantity??''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products_with_anime[0]->id)}}")' >--}}
                                                                                        {{--                                                                                    {{translate('Perform')}}--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                            </div>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case("performedOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            @switch($products_with_anime[0]->status)--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-check"></i></button>--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""--}}
                                                                                        {{--                                                                                        onclick='accepting_anime_order(--}}
                                                                                        {{--                                                                                            "{{$products_with_anime[0]->quantity??''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][0])?$products_with_anime['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products_with_anime['images'][1])?$products_with_anime['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products_with_anime[0]->id)}}")'>--}}
                                                                                        {{--                                                                                    {{translate('Perform')}}--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                            @endswitch--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case("acceptedByRecipientOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                    @endswitch--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                            </div>--}}
                                                                                        {{--                                                        @endforeach--}}
                                                                                        {{--                                                        @foreach($order['products'] as $products)--}}
                                                                                        {{--                                                            @php--}}
                                                                                        {{--                                                                if(!empty($products[0]->warehouse) && $products[0]->warehouse->name){--}}
                                                                                        {{--                                                                    $product_name = $products[0]->warehouse->name??'';--}}
                                                                                        {{--                                                                }else if(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name){--}}
                                                                                        {{--                                                                    $product_name = $products[0]->warehouse->product->name??'';--}}
                                                                                        {{--                                                                }--}}
                                                                                        {{--                                                            @endphp--}}
                                                                                        {{--                                                            <hr class="hr_no_margin">--}}
                                                                                        {{--                                                            <div class="row" style="margin:20px 0px">--}}
                                                                                        {{--                                                                <div class="col-3 order_product_images">--}}
                                                                                        {{--                                                                    <img onclick='getImages("{{implode(" ", $products['images'])}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal" src="{{!empty($products['images'])?$products['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="col-1"></div>--}}
                                                                                        {{--                                                                <div class="col-4 order_content">--}}
                                                                                        {{--                                                                    <h4>{{translate('Order')}}</h4>--}}
                                                                                        {{--                                                                    <span><b>{{$product_name}}</b></span>--}}
                                                                                        {{--                                                                    @if($products[0]->price)--}}
                                                                                        {{--                                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! !empty($products[0]->quantity)?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if($products[1])--}}
                                                                                        {{--                                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if((int)$products[0]->discount_price > 0)--}}
                                                                                        {{--                                                                        @if($products['discount_withouth_expire'] > 0)--}}
                                                                                        {{--                                                                            <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['discount_withouth_expire']}} %</b></span>--}}
                                                                                        {{--                                                                        @elseif($products['product_discount_withouth_expire'] > 0)--}}
                                                                                        {{--                                                                            <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['product_discount_withouth_expire']}} %</b></span>--}}
                                                                                        {{--                                                                        @endif--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    @if(!empty($products[0]->size))--}}
                                                                                        {{--                                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>--}}
                                                                                        {{--                                                                    @endif--}}
                                                                                        {{--                                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="col-1 d-flex justify-content-around align-items-center">--}}
                                                                                        {{--                                                                    @switch($products[0]->status)--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-danger">{{translate('New')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-success">{{translate('Performed')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)--}}
                                                                                        {{--                                                                        <div>--}}
                                                                                        {{--                                                                            <span class="badge bg-info">{{translate('Finished')}}</span>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                    @endswitch--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                                <div class="function-column col-3">--}}
                                                                                        {{--                                                                    @switch($key_order)--}}
                                                                                        {{--                                                                        @case("orderedOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            @switch($products[0]->status)--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_ORDERED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""--}}
                                                                                        {{--                                                                                        onclick='accepting_order(--}}
                                                                                        {{--                                                                                            "{{(int)$products[0]->quantity}}",--}}
                                                                                        {{--                                                                                            "{{(int)$products[0]->warehouse->quantity + (int)$products[0]->quantity  }}",--}}
                                                                                        {{--                                                                                            "{{!empty($products[0]->color)?$products[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products[0]->size)?$products[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{$product_name}}",--}}
                                                                                        {{--                                                                                            "{{isset($products['images'][0])?$products['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products['images'][1])?$products['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products[0]->id)}}"--}}
                                                                                        {{--                                                                                            )'>--}}
                                                                                        {{--                                                                                    <i class="fa fa-check"></i>--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)--}}
                                                                                        {{--                                                                                                                                    <button type="button" class="btn btn-warning delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#waiting-to-perform-alert-modal" title="{{translate('Waiting for superadmin performing')}}"><i class="fa fa-question"></i></button>--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""--}}
                                                                                        {{--                                                                                        onclick='accepting_order(--}}
                                                                                        {{--                                                                                            "{{(int)$products[0]->quantity}}",--}}
                                                                                        {{--                                                                                            "{{(int)$products[0]->warehouse->quantity}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products[0]->color)?$products[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products[0]->size)?$products[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{$product_name}}",--}}
                                                                                        {{--                                                                                            "{{isset($products['images'][0])?$products['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products['images'][1])?$products['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products[0]->id)}}"--}}
                                                                                        {{--                                                                                            )'>--}}
                                                                                        {{--                                                                                    <i class="fa fa-check"></i>--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recepient-success-modal" title="{{translate('Order accepted by recipient')}}"><i class="fa fa-ellipsis-h"></i></button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                            @endswitch--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case("performedOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            @switch($products[0]->status)--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-check"></i></button>--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)--}}
                                                                                        {{--                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""--}}
                                                                                        {{--                                                                                        onclick='accepting_order(--}}
                                                                                        {{--                                                                                            "{{(int)$products[0]->quantity}}",--}}
                                                                                        {{--                                                                                            "{{(int)$products[0]->warehouse->quantity}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products[0]->color)?$products[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                            "{{!empty($products[0]->size)?$products[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                            "{{$product_name}}",--}}
                                                                                        {{--                                                                                            "{{isset($products['images'][0])?$products['images'][0]:''}}",--}}
                                                                                        {{--                                                                                            "{{isset($products['images'][1])?$products['images'][1]:''}}",--}}
                                                                                        {{--                                                                                            "{{route('perform_order_detail', $products[0]->id)}}"--}}
                                                                                        {{--                                                                                            )'>--}}
                                                                                        {{--                                                                                    {{translate('Perform')}}--}}
                                                                                        {{--                                                                                </button>--}}
                                                                                        {{--                                                                                @break--}}
                                                                                        {{--                                                                            @endswitch--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case("cancelledOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""--}}
                                                                                        {{--                                                                                    onclick='accepting_order(--}}
                                                                                        {{--                                                                                        "{{(int)$products[0]->quantity}}",--}}
                                                                                        {{--                                                                                        "{{(int)$products[0]->warehouse->quantity}}",--}}
                                                                                        {{--                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",--}}
                                                                                        {{--                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",--}}
                                                                                        {{--                                                                                        "{{$product_name}}",--}}
                                                                                        {{--                                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",--}}
                                                                                        {{--                                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",--}}
                                                                                        {{--                                                                                        "{{route('perform_order_detail', $products[0]->id)}}"--}}
                                                                                        {{--                                                                                        )'>--}}
                                                                                        {{--                                                                                {{translate('Perform')}}--}}
                                                                                        {{--                                                                            </button>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                        @case("acceptedByRecipientOrders")--}}
                                                                                        {{--                                                                        <div class="d-flex justify-content-around">--}}
                                                                                        {{--                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>--}}
                                                                                        {{--                                                                        </div>--}}
                                                                                        {{--                                                                        @break--}}
                                                                                        {{--                                                                    @endswitch--}}
                                                                                        {{--                                                                </div>--}}
                                                                                        {{--                                                            </div>--}}
                                                                                        {{--                                                        @endforeach--}}
                                                                                        {{--                                                    </div>--}}
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                            </div>
                                    </div>
                                    @else
                                        <span class="badge bg-warning">
            <h2>{{translate('No orders')}}</h2>
        </span>
                                    @endif
                                    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
                                    <script src="{{asset('assets/js/companyOrder.js')}}"></script>
                                    <script>
                                        let product_name_text = "{{translate('Product name')}}"
                                        let size_text = "{{translate('size')}}"
                                        let order_color_text = "{{translate('Order color')}}"
                                        let order_quantity_text = "{{translate('Order quantity')}}"
                                        let remaining_in_warehouse_text = "{{translate('remained in warehouse')}}"
                                        let out_of_stock_text = "{{translate('Out of stock')}}"

                                        let error = "{{session('error')}}"
                                        if(error != "" && error != null && error != undefined){
                                            $(document).ready(function(){
                                                toastr.warning(error)
                                            });
                                        }
                                    </script>
@endsection
