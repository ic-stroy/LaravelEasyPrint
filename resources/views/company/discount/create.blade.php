@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-muted font-14">
                {{__('Discount list create')}}
            </p>
            <form action="{{route('company_discount.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Coupon name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Coupon type')}}</label>
                        <select name="coupon_type" class="form-control" id="coupon_type">
                            <option value="price" class="form-control">{{translate('Price')}}</option>
                            <option value="percent" class="form-control">{{translate('Percent')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6" id="coupon_price">
                        <label class="form-label">{{__('Coupon price')}}</label>
                        <input type="number" name="price" class="form-control" id="coupon_price_input"  min="0"  value="{{old('price')}}"/>
                    </div>
                    <div class="mb-3 col-6 display-none" id="coupon_percent">
                        <label class="form-label">{{__('Coupon percent')}}</label>
                        <input type="number" name="percent" class="form-control" id="coupon_percent_input" min="0" max="100"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id" required>
                            <option value="" selected disabled>{{__('Select category')}}</option>
                            <option value="all">{{__('Select all subcategories` products')}}</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 display-none" id="subcategory_exists">
                        <label class="form-label">{{__('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id"></select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="product_exists">
                        <label class="form-label">{{__('Products')}}</label>
                        <select name="product_id" class="form-control" id="product_id"></select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="warehouse_exists">
                        <label class="form-label">{{__('Warehouses')}}</label>
                        <select name="warehouse_id" class="form-control" id="warehouse_id"></select>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let super_admin = false
        let coupon_category_id = ""
        let coupon_subcategory_id = ""
        let coupon_product_id = ""
        let coupon_warehouse_id = ""
        let coupon_price_value = ""
        let coupon_percent_value = ""
        let text_select_sub_category = "{{translate('Select sub category')}}"
        let text_all_subcategory_products = "{{translate('All subcategories`s products')}}"
        let text_all_products = "{{translate('All products')}}"
        let text_all_warehouses = "{{translate('All warehouses')}}"
        let text_select_product = "{{translate('Select product')}}"



        let subcategory_exists = document.getElementById('subcategory_exists')
        let product_exists = document.getElementById('product_exists')
        let warehouse_exists = document.getElementById('warehouse_exists')

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')
        let product_id = document.getElementById('product_id')
        let warehouse_id = document.getElementById('warehouse_id')


        if(coupon_subcategory_id != ''){
            subcategory_id.innerHTML = ""
            product_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${coupon_category_id}`,
                    type:'GET',
                    success: function (data) {
                        if(subcategory_exists.classList.contains('display-none')){
                            subcategory_exists.classList.remove('display-none')
                        }
                        data.data.forEach(couponAddOption)
                        let disabled_option = document.createElement('option')
                        disabled_option.text = text_select_product
                        disabled_option.disabled = true
                        subcategory_id.add(disabled_option)
                        let all_subcategories = document.createElement('option')
                        all_subcategories.text = text_all_subcategory_products
                        all_subcategories.value = "all"
                        subcategory_id.add(all_subcategories)
                    },
                    error: function (e) {
                        if(!subcategory_exists.classList.contains('display-none')){
                            subcategory_exists.classList.add('display-none')
                        }
                    }
                })
            })
        }else{
            let all_subcategories = document.createElement('option')
            all_subcategories.text = text_all_subcategory_products
            all_subcategories.value = "all"
            all_subcategories.selected = true
            subcategory_id.add(all_subcategories)
        }
        function couponAddOptionToProduct(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            if(item.id == coupon_product_id){
                option.selected = true
            }
            product_id.add(option)
        }
        if(coupon_product_id != undefined && coupon_product_id != '' && coupon_product_id != null){
            product_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/get-products-by-category?category_id=${coupon_subcategory_id}`,
                    type:'GET',
                    success: function (data) {
                        if(product_exists.classList.contains('display-none')){
                            product_exists.classList.remove('display-none')
                        }
                        let disabled_option = document.createElement('option')
                        disabled_option.text = text_select_product
                        disabled_option.disabled = true
                        product_id.add(disabled_option)
                        let all_products = document.createElement('option')
                        all_products.text = text_all_products
                        all_products.value = "all"
                        product_id.add(all_products)
                        data.data[0].products.forEach(couponAddOptionToProduct)
                    },
                    error: function (e) {
                        if(!product_exists.classList.contains('display-none')){
                            product_exists.classList.add('display-none')
                        }
                        if(!warehouse_exists.classList.contains('display-none')){
                            warehouse_exists.classList.add('display-none')
                        }
                    }
                })
            })
        }else{
            let all_products = document.createElement('option')
            all_products.text = text_all_products
            all_products.value = "all"
            all_products.selected = true
            product_id.add(all_products)
        }
        category_id.addEventListener('change', function () {
            subcategory_id.innerHTML = ""
            product_id.innerHTML = ""
            warehouse_id.innerHTML = ""
            if(!product_exists.classList.contains('display-none')){
                product_exists.classList.add('display-none')
            }
            if(!warehouse_exists.classList.contains('display-none')){
                warehouse_exists.classList.add('display-none')
            }
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${category_id.value}`,
                    type:'GET',
                    success: function (data) {
                        if(subcategory_exists.classList.contains('display-none')){
                            subcategory_exists.classList.remove('display-none')
                        }
                        let disabled_option = document.createElement('option')
                        disabled_option.text = text_select_sub_category
                        disabled_option.selected = true
                        disabled_option.disabled = true
                        subcategory_id.add(disabled_option)
                        let all_products = document.createElement('option')
                        all_products.text = text_all_products
                        all_products.value = "all"
                        subcategory_id.add(all_products)
                        data.data.forEach(addOption)
                    },
                    error: function (e) {
                        if(!subcategory_exists.classList.contains('display-none')){
                            subcategory_exists.classList.add('display-none')
                        }
                        if(!product_exists.classList.contains('display-none')){
                            product_exists.classList.add('display-none')
                        }
                        if(!warehouse_exists.classList.contains('display-none')){
                            warehouse_exists.classList.add('display-none')
                        }
                    }
                })
            })
        })
        function addOptionToProduct(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            product_id.add(option)
        }
        subcategory_id.addEventListener('change', function () {
            product_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/get-products-by-category?category_id=${subcategory_id.value}`,
                    type:'GET',
                    success: function (data) {
                        if(product_exists.classList.contains('display-none')){
                            product_exists.classList.remove('display-none')
                        }
                        let disabled_option = document.createElement('option')
                        disabled_option.text = text_select_product
                        disabled_option.selected = true
                        disabled_option.disabled = true
                        product_id.add(disabled_option)
                        let all_products = document.createElement('option')
                        all_products.text = text_all_products
                        all_products.value = "all"
                        product_id.add(all_products)
                        data.data[0].products.forEach(addOptionToProduct)
                    },
                    error: function (e) {
                        if(!product_exists.classList.contains('display-none')){
                            product_exists.classList.add('display-none')
                        }
                        if(!warehouse_exists.classList.contains('display-none')){
                            warehouse_exists.classList.add('display-none')
                        }
                    }
                })
            })
        })
    </script>
@endsection
