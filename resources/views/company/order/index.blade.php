@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
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
                </div>
                <div class="card-footer">
                    <form class="d-flex justify-content-between" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
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
    <div id="warning-order-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="dripicons-warning h1 text-warning"></i>
                        <h4 class="mt-2">{{ translate('Are you sure to cancel this order')}}</h4>
                        <form style="display: inline-block;" action="" method="POST" id="cancell_order_id">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-warning my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
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
                                        <span style="line-height: 2; font-size: 16px">
                                            @if($user_name){{$user_name}}@endif {{translate('ordered')}}
                                            @if(isset($order['product_types'])) <b>{{ $order['product_types'] }}</b>  {{translate('products of yours in')}} {{count($order['order']->orderDetail)}} {{translate('products')}} @endif
                                            <b>{{$order['company_product_price']}}</b> {{translate('sum of')}} <b>{{$order['order']->all_price}}</b> {{translate('sum is yours in order')}}
                                            @if($order['order']->status)
                                                @switch($order['order']->status)
                                                    @case(2)
                                                        <b>{{translate('ORDERED')}}</b>
                                                    @break
                                                    @case(3)
                                                        <b>{{translate('PERFORMED')}}</b>
                                                    @break
                                                    @case(3)
                                                        <b>{{translate('CANCELLED')}}</b>
                                                    @break
                                                    @case(3)
                                                        <b>{{translate('ACCEPTED_BY_RECIPIENT')}}</b>
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
                                    <div class="col-4 order_product_images" onclick='getImages("{{implode(" ", $images)}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                        @foreach($images as $image)
                                            <img src="{{$image}}" alt="" height="144px">
                                        @endforeach
                                    </div>
                                    <div class="col-3 order_content">
                                        <h4>{{translate('Animated order')}}</h4>
                                        <span>{{!empty($products_with_anime->product)?$products_with_anime->product->name:''}}</span>
                                        @if(!empty($products_with_anime->size))
                                            <span>{{translate('Size')}}: <b>{{$products_with_anime->size->name}}</b></span>
                                        @endif
                                        @if(!empty($products_with_anime->color))
                                            <span>{{translate('Color')}}: <b>{{$products_with_anime->color->name}}</b></span>
                                        @endif
                                        @if(!empty($products_with_anime->quantity))
                                            <span>{{translate('Quantity')}}: <b>{{$products_with_anime->quantity}}</b></span>
                                        @endif
                                        @if(!empty($products_with_anime->updated_at))
                                            <span>{{translate('Ordered')}}: <b>{{$products_with_anime->updated_at}}</b></span>
                                        @endif
                                    </div>
                                    <div class="col-3"></div>

                                    <div class="function-column col-2">
                                        <div class="d-flex justify-content-around">
                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"
                                                onclick='accepting_anime_order(
                                                "{{$products_with_anime->quantity??''}}",
                                                "{{!empty($products_with_anime->product)?$products_with_anime->product->name:''}}",
                                                "{{!empty($products_with_anime->size)?$products_with_anime->size->name:''}}",
                                                "{{!empty($products_with_anime->color)?$products_with_anime->color->name:''}}",
                                                "{{isset($images[0])?$images[0]:''}}",
                                                "{{isset($images[1])?$images[1]:''}}"
                                                )' data-url="">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{$products_with_anime->id}}")' data-url=""><i class="fa fa-times"></i></button>
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
                                    <div class="col-4 order_product_images" onclick='getImages("{{implode(" ", $images)}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                        @foreach($images as $image)
                                            <img src="{{$image}}" alt="" height="144px">
                                        @endforeach
                                    </div>
                                    <div class="col-3 order_content">
                                        <h4>{{translate('Order')}}</h4>
                                        @if(!empty($products->warehouse) && $products->warehouse->name)
                                            <span>{{$products->warehouse->name}}</span>
                                        @elseif(!empty($products->warehouse->product) && $products->warehouse->product->name)
                                            <span>{{$products->warehouse->product->name}}</span>
                                        @endif
                                        @if(!empty($products->size))
                                            <span>{{translate('Size')}}: <b>{{$products->size->name}}</b></span>
                                        @endif
                                        @if(!empty($products->color))
                                            <span>{{translate('Color')}}: <b>{{$products->color->name}}</b></span>
                                        @endif
                                        @if(!empty($products->quantity))
                                            <span>{{translate('Quantity')}}: <b>{{$products->quantity}}</b></span>
                                        @endif
                                    </div>
                                    <div class="col-3 order_content">
                                        <h4>{{translate('Product in warehouse')}}</h4>
                                        @if(!empty($products->warehouse))
                                            <span>{{translate('Name')}}: <b>{{$products->warehouse->name}}</b></span>

                                            <span>{{translate('Color')}}: <b>{{$products->warehouse->color->name}}</b></span>

                                            <span>{{translate('Quantity')}}: <b>{{$products->warehouse->quantity}}</b></span>

                                            <span>{{translate('Ordered')}}: <b>{{$products->warehouse->updated_at}}</b></span>
                                        @endif
                                    </div>
                                    <div class="function-column col-2">
                                        <div class="d-flex justify-content-around">
                                            @php
                                                if(!empty($products->warehouse) && $products->warehouse->name){
                                                    $product_name = $products->warehouse->name??'';
                                                }else if(!empty($products->warehouse->product) && $products->warehouse->product->name){
                                                    $product_name = $products->warehouse->product->name??'';
                                                }
                                            @endphp
                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                onclick='accepting_order(
                                                    "{{$products->quantity}}",
                                                    "{{$products->warehouse->quantity - $products->quantity }}",
                                                    "{{!empty($products->color)?$products->color->name:''}}",
                                                    "{{!empty($products->size)?$products->size->name:''}}",
                                                    "{{$product_name}}",
                                                    "{{isset($images[0])?$images[0]:''}}",
                                                    "{{isset($images[1])?$images[1]:''}}"
                                                )'>
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{$products->id}}")' data-url=""><i class="fa fa-times"></i></button>
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
        .order_product_images>img{
            transition: 0.4s;
        }
        .order_product_images>img:hover{
            transform: scale(1.14);
        }

    </style>
    <script>
        let product_name = document.getElementById('product_name')
        let order_size = document.getElementById('order_size')
        let order_color = document.getElementById('order_color')
        let remaining_quantity = document.getElementById('remaining_quantity')
        let order_quantity = document.getElementById('order_quantity')
        let products_images = document.getElementById('products_images')
        let carousel_product_images = document.getElementById('carousel_product_images')
        let product_image = document.getElementById('product_image')
        let cancell_order_id = document.getElementById('cancell_order_id')
        function accepting_order(quantity, remaining_quantity_, color_name, size_name, product_name_, image, image_1){
            product_name.innerHTML = ""
            order_size.innerHTML = ""
            order_color.innerHTML = ""
            remaining_quantity.innerHTML = ""
            order_quantity.innerHTML = ""
            product_image.innerHTML = ""
            if(product_name_ != ""){
                product_name.innerHTML = "<h5>{{translate('Product name')}} "+product_name_+"</h5>"
            }
            if(size_name != ""){
                order_size.innerHTML = "<span>{{translate('size')}} <b>"+size_name+"</b></span>"
            }
            if(color_name != ""){
                order_color.innerHTML = "<span>{{translate('Order color')}} <b>"+color_name+"</b></span>"
            }
            if(remaining_quantity_ != ""){
                remaining_quantity.innerHTML = "<span>{{translate('Remaining in warehouse')}} <b>"+remaining_quantity_+"</b></span>"
            }
            if(quantity != ""){
                order_quantity.innerHTML = "<span>{{translate('Order quantity')}} <b>"+quantity+"</b></span>"
            }
            if(image != "" && image_1 != ""){
                product_image.innerHTML = "<img height='64px' src='"+image+"'>" +
                    "<img height='64px' src='"+image_1+"'>"
            }
        }
        function accepting_anime_order(quantity, product_name_, size_name, color_name, images_0, images_1){
            product_name.innerHTML = ""
            order_size.innerHTML = ""
            order_color.innerHTML = ""
            remaining_quantity.innerHTML = ""
            order_quantity.innerHTML = ""
            product_image.innerHTML = ""

            if(product_name_ != ""){
                product_name.innerHTML = "<h5>{{translate('Product name')}} "+product_name_+"</h5>"
            }
            if(size_name != ""){
                order_size.innerHTML = "<span>{{translate('size')}} <b>"+size_name+"</b></span>"
            }
            if(color_name != ""){
                order_color.innerHTML = "<span>{{translate('Order color')}} <b>"+color_name+"</b></span>"
            }
            if(quantity != ""){
                order_quantity.innerHTML = "<span>{{translate('Order quantity')}} <b>"+quantity+"</b></span>"
            }
            if(images_0 != "" && images_1 != ""){
                product_image.innerHTML = "<img height='64px' src='"+images_0+"'>" +
                    "<img height='64px' src='"+images_1+"'>"
            }
        }
        function cancelling_order(order_detail_id){
            cancell_order_id.setAttribute("actions", "{{route()}}")
            console.log(order_detail_id)
        }
        function getImages(images) {
            let all_images = images.split(' ')
            let images_content = ''
            for(let i=0; i<all_images.length; i++){
                if(i == 0){
                    images_content = images_content +
                        `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
                }else{
                    images_content = images_content +
                        `<div class="carousel-item">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
                }
            }
            carousel_product_images.innerHTML = images_content
        }
    </script>
@endsection
