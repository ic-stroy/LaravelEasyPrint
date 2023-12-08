@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    @php
        switch ($is_category){
            case 1:
                $current_category = $category_product;
                $current_sub_category_id = 'no';
                $current_sub_sub_category_id = 'no';
                break;
            case 2:
                $current_category = isset($category_product->category)?$category_product->category:'no';
                $current_sub_category_id = isset($category_product->id)?$category_product->id:'no';
                $current_sub_sub_category_id = 'no';
                break;
            case 3:
                $current_category = isset($category_product->sub_category->category)?$category_product->sub_category->category:'no';
                $current_sub_category_id = isset($category_product->sub_category->id)?$category_product->sub_category->id:'no';
                $current_sub_sub_category_id = isset($category_product->id)?$category_product->id:'no';
                break;
                default:
                    $current_category = 'no';
        }
    @endphp
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
                {{translate('Products list edit')}}
            </p>
            <form action="{{route('product.update', $product->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{$product->name}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}"
                                @if($category != 'no')
                                    {{$current_category->id == $category->id?'selected':'' }}
                                    @endif
                                >
                                    {{$category->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Price')}}</label>
                        <input type="number" class="form-control" name="price" value="{{$product->price??''}}">
                    </div>
                    <div class="mb-3 col-4 display-none" id="subcategory_exists">
                        <label class="form-label">{{translate('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id">
                            @if(isset($current_category->subcategory))
                                @foreach($current_category->subcategory as $subcategory)
                                    <option value="{{$subcategory->id}}" {{$subcategory->id == $current_sub_category_id?'selected':''}}>{{$subcategory->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Status')}}</label>
                        <select name="status" class="form-control" id="status_id">
                            <option value="0" {{$product->status == 0?'selected':''}}>{{translate('No active')}}</option>
                            <option value="1" {{$product->status == 1?'selected':''}}>{{translate('Active')}}</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    @php
                        $images = json_decode($product->images)??[];
                    @endphp
                    <div class="row">
                        @foreach($images as $image)
                            @php
                                $avatar_main = storage_path('app/public/products/'.$image);
                            @endphp
                            @if(file_exists($avatar_main))
                                <div class="col-2 mb-3">
                                    <img src="{{asset('storage/products/'.$image)}}" alt="" height="200px">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Images')}}</label>
                        <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Manufacturer country')}}</label>
                        <input type="text" name="manufacturer_country" class="form-control" value="{{old('manufacturer_country')}}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Description')}}</label>
                    <textarea class="form-control" name="description" id="description" cols="20" rows="2">{{$product->description??''}}</textarea>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Material composition')}}</label>
                        <input type="text" name="material_composition" class="form-control" value="{{old('material_composition')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Material')}}</label>
                        <input type="text" name="material" class="form-control" value="{{old('material')}}"/>
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

        let size_type = document.getElementById('size_type')
        let sizes_leg = document.getElementById('sizes_leg')

        let subcategory_exists = document.getElementById('subcategory_exists')

        let is_category = "{{$is_category}}"

        let sub_category = {}

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')

        switch (is_category) {
            case "2":
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                break;
            case "3":
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                break;
        }
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
                        if(data.status == true){
                            if(subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.remove('display-none')
                            }
                        }else{
                            if(!subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.add('display-none')
                            }
                        }
                        let disabled_option = document.createElement('option')
                        disabled_option.text = "{{translate('Select sub category')}}"
                        disabled_option.selected = true
                        disabled_option.disabled = true
                        subcategory_id.add(disabled_option)
                        data.data.forEach(addOption)
                    }
                })
            })
        })
    </script>
@endsection
