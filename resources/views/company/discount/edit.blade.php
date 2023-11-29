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
                {{translate('Discount list edit')}}
            </p>
            <form action="{{route('company_discount.update', $coupon->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Coupon name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{$coupon->name}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Coupon type')}}</label>
                        <select name="coupon_type" class="form-control" id="coupon_type">
                            <option value="price" class="form-control" {{$coupon->price != NULL?'selected':''}}>{{translate('Price')}}</option>
                            <option value="percent" class="form-control" {{$coupon->percent != NULL?'selected':''}}>{{translate('Percent')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6" id="coupon_price">
                        <label class="form-label">{{translate('Coupon price')}}</label>
                        <input type="number" name="price" class="form-control" id="coupon_price_input"  min="0"  value="{{$coupon->price}}"/>
                    </div>
                    <div class="mb-3 col-6 display-none" id="coupon_percent">
                        <label class="form-label">{{translate('Coupon percent')}}</label>
                        <input type="number" name="percent" value="{{$coupon->percent}}" class="form-control" id="coupon_percent_input" min="0" max="100"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id" required>
                            <option value="" selected disabled>{{translate('Select category')}}</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{$category->id == $category_id? 'selected' : ''}}>{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 display-none" id="subcategory_exists">
                        <label class="form-label">{{translate('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id"></select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="product_exists">
                        <label class="form-label">{{translate('Products')}}</label>
                        <select name="product_id" class="form-control" id="product_id"></select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="warehouse_exists">
                        <label class="form-label">{{translate('Warehouses')}}</label>
                        <select name="warehouse_id" class="form-control" id="warehouse_id"></select>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let super_admin = true
        let coupon_category_id = "{{$category_id}}"
        let coupon_subcategory_id = "{{$subcategory_id}}"
        let coupon_product_id = "{{$coupon->product_id}}"
        let coupon_warehouse_id = "{{$coupon->warehouse_product_id}}"
        let coupon_price_value = "{{$coupon->price??''}}"
        let coupon_percent_value = "{{$coupon->percent??''}}"
        let text_select_sub_category = "{{translate('Select sub category')}}"
        let text_all_subcategory_products = "{{translate('All subcategories`s products')}}"
        let text_all_products = "{{translate('All products')}}"
        let text_all_warehouses = "{{translate('All warehouses')}}"
        let text_select_product = "{{translate('Select product')}}"
    </script>
    <script src="{{asset('assets/js/coupon.js')}}"></script>
@endsection
