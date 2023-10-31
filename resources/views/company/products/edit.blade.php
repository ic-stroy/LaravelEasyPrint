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
                {{__('Products list edit')}}
            </p>
            <form action="{{route('company_product.update', $warehouse->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("post")
                {{-- <input type="hidden" name="id" value="{{$warehouse->id}}"> --}}
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{$warehouse->name}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Product')}}</label>
                    <select name="product_id" class="form-control" id="product_id">
                        @if(isset($products))
                            @foreach($products as $product)
                            <option value="{{$product->id}}" @if($warehouse->product_id) {{$warehouse->product_id == $product->id?'selected':'' }} @endif>
                                {{$product->name}}
                            </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Color')}}</label>
                    <select name="color_id" class="form-control" id="color_id">
                        @foreach($colors as $color)
                            <option value="{{$color->id}}" @if($warehouse->color_id) {{$warehouse->color_id == $color->id?'selected':'' }} @endif>
                                {{$color->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Size')}}</label>
                    <select name="size_id" class="form-control" id="size_id">
                        @foreach($sizes as $size)
                            <option value="{{$size->id}}" @if($warehouse->size_id) {{$warehouse->size_id == $size->id?'selected':'' }} @endif>
                                {{$size->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Sum')}}</label>
                    <input type="number" name="sum" class="form-control" required value="{{$warehouse->price}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Quantity')}}</label>
                    <input type="number" name="quantity" class="form-control" required required value="{{$warehouse->quantity}}"/>
                </div>





                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
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
