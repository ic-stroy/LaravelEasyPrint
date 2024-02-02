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

            @php
                $i=0
            @endphp
            @foreach($order_data as $order)
                @php
                    $i++;
                    if(!empty($order['order']->user->personalInfo)){
                        $first_name = isset($order['order']->user->personalInfo->first_name)?$order['order']->user->personalInfo->first_name.' ':'';
                        $last_name = isset($order['order']->user->personalInfo->last_name)?$order['order']->user->personalInfo->last_name.' ':'';
                        $middle_name = isset($order['order']->user->personalInfo->middle_name)?$order['order']->user->personalInfo->middle_name:'';
                        $user_name = $first_name.''.$last_name.''.$middle_name;
                    }
                @endphp
                <div class="accordion custom-accordion" id="custom-accordion-one">
                    <div class="card mb-0">
                        <div class="card-header" id="headingNine">
                            <span class="m-0 position-relative" style="width: 100%">
                                <a class="custom-accordion-title text-reset d-block"
                                   data-bs-toggle="collapse" href="#collapseNine"
                                   aria-expanded="true" aria-controls="collapseNine">
                                    <div class="d-flex justify-content-between align-items-center p-4">
                                        <span class="white_text" style="line-height: 2; font-size: 16px">
                                            @if($user_name){{$user_name}}@endif {{translate('ordered')}}
                                            @if(isset($order['product_types'])) <b>{{ $order['product_types'] }}</b>  {{translate('products of yours in')}} {{count($order['order']->orderDetail)}} {{translate('products')}} @endif
                                            <b>{{$order['company_product_price']}}</b> {{translate('sum of')}} <b>{{$order['order']->all_price}}</b> {{translate('sum is yours in order')}}
                                            @if($order['order']->status)
                                                @switch($order['order']->status)
                                                    @case(2)
                                                        <b style="color: #00FFFF">{{translate('ORDERED')}}</b>
                                                    @break
                                                    @case(3)
                                                        <b style="color: #00FF00">{{translate('PERFORMED')}}</b>
                                                    @break
                                                    @case(3)
                                                        <b style="color: #FF0000">{{translate('CANCELLED')}}</b>
                                                    @break
                                                    @case(3)
                                                        <b style="color: #009900">{{translate('ACCEPTED_BY_RECIPIENT')}}</b>
                                                    @break
                                                @endswitch
                                            @endif
                                        </span>
                                        <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                    </div>
                                </a>
                            </span>
                        </div>
                        <div id="collapseNine" class="collapse fade"
                             aria-labelledby="headingFour"
                             data-bs-parent="#custom-accordion-one">
                            @foreach($order['products_with_anime'] as $products_with_anime)
                                @php
                                    $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$products_with_anime->image_front);
                                    if(file_exists($order_detail_image_front_exists)){
                                        $order_detail_image_front = asset('storage/warehouse/'.$products_with_anime->image_front);
                                    }else{
                                        $order_detail_image_front = null;
                                    }
                                    $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$products_with_anime->image_back);
                                    if(file_exists($order_detail_image_back_exists)){
                                        $order_detail_image_back = asset('storage/warehouse/'.$products_with_anime->image_back);
                                    }else{
                                        $order_detail_image_back = null;
                                    }
                                    if(!$order_detail_image_front && !$order_detail_image_back){
                                        if($products_with_anime->product->images){
                                            $images_ = json_decode($products_with_anime->product->images);
                                            $images = [];
                                            foreach ($images_ as $key => $image_){
                                                if($key < 2){
                                                    $images[] = asset('storage/products/'.$image_);
                                                }
                                            }
                                        }else{
                                            $images = [];
                                        }

                                    }else{
                                        $images = [$order_detail_image_front??'no', $order_detail_image_back??'no'];
                                    }
                                @endphp
                                <hr>
                                <div class="row">
                                    <div class="col-4">
                                        @foreach($images as $image)
                                            <img src="{{$image}}" alt="" height="144px">
                                        @endforeach
                                    </div>
                                    <div class="col-3 order_content">
                                        <h4>{{translate('Animated order')}}</h4>
                                        <span class="white_text">{{!empty($products_with_anime->product)?$products_with_anime->product->name:''}}</span>
                                        @if(!empty($products_with_anime->size))
                                            <span>{{translate('Size')}}: <span class="white_text">{{$products_with_anime->size->name}}</span></span>
                                        @endif
                                        @if(!empty($products_with_anime->color))
                                            <span>{{translate('Color')}}: <span class="white_text">{{$products_with_anime->color->name}}</span></span>
                                        @endif
                                        @if(!empty($products_with_anime->quantity))
                                            <span>{{translate('Quantity')}}: <span class="white_text">{{$products_with_anime->quantity}}</span></span>
                                        @endif
                                        @if(!empty($products_with_anime->updated_at))
                                            <span>{{translate('Ordered')}}: <span class="white_text">{{$products_with_anime->updated_at}}</span></span>
                                        @endif
                                    </div>
                                    <div class="col-3 order_content">
                                        <h4>{{translate('Product withouth anime')}}</h4>
                                        @if(!empty($products_with_anime->product))
                                            <span>{{translate('Name')}}: <span class="white_text">{{$products_with_anime->product->name}}</span></span>

                                            <span>{{translate('Color')}}: <span class="white_text">{{$products_with_anime->product->name}}</span></span>

                                            <span>{{translate('Status')}}: <span class="white_text">{{$products_with_anime->product->status}}</span></span>

                                            <span>{{translate('Ordered')}}: <span class="white_text">{{$products_with_anime->product->updated_at}}</span></span>
                                        @endif
                                    </div>
                                    <div class="function-column col-2">
                                        <div class="d-flex justify-content-around">
                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""><i class="fa fa-check"></i></button>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url=""><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @foreach($order['products'] as $products)
                                @php
                                    if(!empty($products->warehouse) && $products->warehouse->images){
                                        $images_ = json_decode($products->warehouse->images);
                                        $images = [];
                                        foreach ($images_ as $key => $image_){
                                            if($key < 2){
                                                $images[] = asset('storage/warehouses/'.$image_);
                                            }
                                        }
                                    }elseif(!empty($products->warehouse->product) && $products->warehouse->product->images){
                                        $images_ = json_decode($products->warehouse->product->images);
                                        $images = [];
                                        foreach ($images_ as $key => $image_){
                                            if($key < 2){
                                                $images[] = asset('storage/products/'.$image_);
                                            }
                                        }
                                    }else{
                                        $images = [];
                                    }
                                @endphp
                                <hr>
                                <div class="row">
                                    <div class="col-4">
                                        @foreach($images as $image)
                                            <img src="{{$image}}" alt="" height="144px">
                                        @endforeach
                                    </div>
                                    <div class="col-6 order_content">
                                        @if(!empty($products->warehouse) && $products->warehouse->name)
                                            <span class="white_text">{{$products->warehouse->name}}</span>
                                        @elseif(!empty($products->warehouse->product) && $products->warehouse->product->name)
                                            <span>{{$products->warehouse->product->name}}</span>
                                        @endif
                                        @if(!empty($products->size))
                                            <span>{{translate('Size')}}: <span class="white_text">{{$products->size->name}}</span></span>
                                        @endif
                                        @if(!empty($products->color))
                                            <span>{{translate('Color')}}: <span class="white_text">{{$products->color->name}}</span></span>
                                        @endif
                                        @if(!empty($products_with_anime->quantity))
                                            <span>{{translate('Quantity')}}: <span class="white_text">{{$products_with_anime->quantity}}</span></span>
                                        @endif
                                    </div>
                                    <div class="function-column col-2">
                                        <div class="d-flex justify-content-around">
                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""><i class="fa fa-check"></i></button>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url=""><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <style>
        #headingNine{
            height: 74px;
            display: flex;
            align-items: center;
        }
        #headingNine a{
            width: 100%;
            font-size: 15px;
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
        }
        .white_text{
            color:white
        }

    </style>
@endsection
