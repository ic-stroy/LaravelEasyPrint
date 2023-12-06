@extends('layout.layout')

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
            <form action="{{route('discount.update', $discount->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Discount percent')}}</label>
                        <input type="number" name="percent" value="{{$discount->percent}}" class="form-control" min="0" max="100" required/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Companies')}}</label>
                        <select name="company_id" class="form-control" id="company_id">
                            <option value="" selected disabled>{{translate('All company')}}</option>
                            @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id" required>
                            <option value="" selected disabled>{{translate('Select category')}}</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{$category->id == $category_id? 'selected' : ''}}>{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="subcategory_exists">
                        <label class="form-label">{{translate('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Start date')}}</label>
                        <input type="date" name="start_date" class="form-control" required value="{{explode(' ', $discount->start_date)[0]}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('End date')}}</label>
                        <input type="date" name="end_date" class="form-control" required value="{{explode(' ', $discount->end_date)[0]}}"/>
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
        let coupon_category_id = "{{$category_id}}"
        let coupon_subcategory_id = "{{$subcategory_id}}"
        let coupon_product_id = "{{$discount->product_id}}"
        let coupon_percent_value = "{{$discount->percent??''}}"
        let text_select_sub_category = "{{translate('Select sub category')}}"
        let text_all_subcategory_products = "{{translate('All subcategories`s products')}}"
        let text_all_products = "{{translate('All products')}}"
        let text_select_product = "{{translate('Select product')}}"
    </script>
    <script src="{{asset('assets/js/discount.js')}}"></script>
@endsection
