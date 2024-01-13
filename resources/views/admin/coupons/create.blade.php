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
                {{translate('Coupon list create')}}
            </p>
            <form action="{{route('coupons.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Coupon name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                    </div>
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Coupon type')}}</label>
                        <select name="coupon_type" class="form-control" id="coupon_type" required>
                            <option value="price" class="form-control">{{translate('Price')}}</option>
                            <option value="percent" class="form-control">{{translate('Percent')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-4" id="coupon_price">
                        <label class="form-label">{{translate('Coupon price')}}</label>
                        <input type="number" name="price" class="form-control" id="coupon_price_input" min="0" value="{{old('price')}}"/>
                    </div>
                    <div class="mb-3 col-4 display-none" id="coupon_percent">
                        <label class="form-label">{{translate('Coupon percent')}}</label>
                        <input type="number" name="percent" class="form-control" id="coupon_percent_input" step="0.01" min="0" max="100" value="{{old('percent')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-2">
                        <label class="form-label">{{translate('Companies')}}</label>
                        <select name="company_id" class="form-control" id="company_id">
                            <option value="" selected>{{translate('All company')}}</option>
                            @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-3">
                        <label class="form-label">{{translate("Order's min price")}}</label>
                        <input type="number" name="min_price" class="form-control" min="0" value="{{old('min_price')}}"/>
                    </div>
                    <div class="mb-3 col-3">
                        <label class="form-label">{{translate('Number of orders')}}</label>
                        <input type="number" name="order_count" class="form-control"/>
                    </div>
                    <div class="mb-3 col-2">
                        <label class="form-label">{{translate('Start date')}}</label>
                        <input type="date" name="start_date" class="form-control" required value="{{old('start_date')}}"/>
                    </div>
                    <div class="mb-3 col-2">
                        <label class="form-label">{{translate('End date')}}</label>
                        <input type="date" name="end_date" class="form-control" required value="{{old('end_date')}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let coupon_price_value = ""
        let coupon_percent_value = ""
    </script>
    <script src="{{asset('assets/js/coupon.js')}}"></script>
@endsection
