@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    @php
        switch ($is_category){
            case 1:
                $current_category = $category_producskyrim1004
                t;
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
                {{__('Products list edit')}}
            </p>
            <form action="{{route('product.update', $product->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{$product->name}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Category')}}</label>
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
                <div class="mb-3 display-none" id="subcategory_exists">
                    <label class="form-label">{{__('Sub category')}}</label>
                    <select name="subcategory_id" class="form-control" id="subcategory_id">
                        @if(isset($current_category->subcategory))
                            @foreach($current_category->subcategory as $subcategory)
                                <option value="{{$subcategory->id}}" {{$subcategory->id == $current_sub_category_id?'selected':''}}>{{$subcategory->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3 display-none" id="subsubcategory_exists">
                    <label class="form-label">{{__('Sub Sub category')}}</label>
                    <select name="subsubcategory_id" class="form-control" id="subsubcategory_id">
                        @if(isset($category_product->sub_category->subsubcategory))
                            @foreach($category_product->sub_category->subsubcategory as $subsubcategory)
                                <option value="{{$subsubcategory->id}}" {{$subsubcategory->id == $current_sub_sub_category_id?'selected':''}}>{{$subsubcategory->name}}</option>
                            @endforeach
                        @endif
                    </select>
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
                        <label class="form-label">{{__('Status')}}</label>
                        <select name="status" class="form-control" id="status_id">
                            <option value="0" {{$product->status == 0?'selected':''}}>{{__('No active')}}</option>
                            <option value="1" {{$product->status == 1?'selected':''}}>{{__('Active')}}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{__('Images')}}</label>
                        <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Description')}}</label>
                    <textarea class="form-control" name="description" id="description" cols="20" rows="10">
                        {{$product->description??''}}
                    </textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#description' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>

        let size_type = document.getElementById('size_type')
        let sizes_leg = document.getElementById('sizes_leg')

        let subcategory_exists = document.getElementById('subcategory_exists')
        let subsubcategory_exists = document.getElementById('subsubcategory_exists')

        let is_category = "{{$is_category}}"

        let sub_category = {}

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')
        let subsubcategory_id = document.getElementById('subsubcategory_id')

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
                if(subsubcategory_exists.classList.contains('display-none')){
                    subsubcategory_exists.classList.remove('display-none')
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
            subsubcategory_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${category_id.value}`,
                    type:'GET',
                    success: function (data) {
                        if(data.status == true){
                            if(subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.remove('display-none')
                            }
                            if(!subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.add('display-none')
                            }
                        }else{
                            if(!subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.add('display-none')
                            }
                            if(!subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.add('display-none')
                            }
                        }
                        let disabled_option = document.createElement('option')
                        disabled_option.text = "{{__('Select sub category')}}"
                        disabled_option.selected = true
                        disabled_option.disabled = true
                        subcategory_id.add(disabled_option)
                        data.data.forEach(addOption)
                    }
                })
            })
        })
        function addSubOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            subsubcategory_id.add(option)
        }
        subcategory_id.addEventListener('change', function () {
            subsubcategory_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${subcategory_id.value}`,
                    type:'GET',
                    success: function (data) {
                        if(data.status == true){
                            if(subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.remove('display-none')
                            }
                        }else{
                            if(!subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.add('display-none')
                            }
                        }
                        let disabled_sub_option = document.createElement('option')
                        disabled_sub_option.text = "{{__('Select sub sub category')}}"
                        disabled_sub_option.selected = true
                        disabled_sub_option.disabled = true
                        subsubcategory_id.add(disabled_sub_option)
                        data.data.forEach(addSubOption)
                    }
                })
            })
        })
    </script>
@endsection
