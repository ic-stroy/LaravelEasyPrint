@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .display-none{
            display: none;
        }
    </style>
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
                {{__('Products list create')}}
            </p>
            <form action="{{route('product.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Company')}}</label>
                        <input type="text" name="company" class="form-control" value="{{old('company')}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id" required>
                            <option value="" selected disabled>{{__('Select category')}}</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Sum')}}</label>
                        <input type="number" class="form-control" name="sum">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 display-none" id="subcategory_exists">
                        <label class="form-label">{{__('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id">
                        </select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="subsubcategory_exists">
                        <label class="form-label">{{__('Sub Sub category')}}</label>
                        <select name="subsubcategory_id" class="form-control" id="subsubcategory_id">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Status')}}</label>
                        <select name="status" class="form-control" id="status_id">
                            <option value="0" >{{__('No active')}}</option>
                            <option value="1">{{__('Active')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Images')}}</label>
                        <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Description')}}</label>
                    <textarea class="form-control" name="description" id="description" cols="20" rows="10">
                    </textarea>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Manufacturer country')}}</label>
                        <input type="text" name="manufacturer_country" class="form-control" required value="{{old('manufacturer_country')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Material composition')}}</label>
                        <input type="text" name="material_composition" class="form-control" value="{{old('material_composition')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Material')}}</label>
                        <input type="text" name="material" class="form-control" value="{{old('material')}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/js/ckeditor/ckeditor.js')}}"></script>
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

        let sub_category = {}

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')
        let subsubcategory_id = document.getElementById('subsubcategory_id')

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
