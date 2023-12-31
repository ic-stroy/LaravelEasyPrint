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
                {{translate('Products list create')}}
            </p>
            <form action="{{route('company_product.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">{{translate('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                </div>

                {{-- <div class="mb-3">
                    <label class="form-label">{{translate('Category')}}</label>
                    <select name="category_id" class="form-control" id="category_id">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="mb-3">
                    <label class="form-label">{{translate('Product')}}</label>
                    <select name="product_id" class="form-control" id="product_id">
                        @if(isset($products))
                            @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Color')}}</label>
                    <select name="color_id" class="form-control" id="color_id">
                        @foreach($colors as $color)
                            <option value="{{$color->id}}"> {{$color->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Size')}}</label>
                    <select name="size_id" class="form-control" id="size_id">
                        @foreach($sizes as $size)
                            <option value="{{$size->id}}"> {{$size->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Price')}}</label>
                    <input type="number" name="price" class="form-control" required value="{{old('price')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Quantity')}}</label>
                    <input type="number" name="quantity" class="form-control" required value="{{old('quantity')}}"/>
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">{{translate('Company')}}</label>
                    <input type="text" name="company" class="form-control" value="{{old('code')}}"/>
                </div>

{{--                <div class="mb-3">--}}
{{--                    <label class="form-label">{{translate('Images')}}</label>--}}
{{--                    <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>--}}
{{--                </div> --}}
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>

        let size_type = document.getElementById('size_type')
        let sizes_leg = document.getElementById('sizes_leg')

        let sub_category = {}

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')

        function addOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            subcategory_id.add(option)
        }
        category_id.addEventListener('change', function () {
            subcategory_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${category_id.value}`,
                    type:'GET',
                    success: function (data) {
                        data.data.forEach(addOption)
                    }
                })
            })
        })
    </script>
@endsection
