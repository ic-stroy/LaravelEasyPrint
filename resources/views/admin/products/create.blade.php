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
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Category')}}</label>
                    <select name="category_id" class="form-control" id="category_id" required>
                        <option value="" selected disabled>{{__('Select category')}}</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 display-none" id="subcategory_exists">
                    <label class="form-label">{{__('Sub category')}}</label>
                    <select name="subcategory_id" class="form-control" id="subcategory_id" required>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Sum')}}</label>
                    <input type="number" name="sum" class="form-control" required value="{{old('sum')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Company')}}</label>
                    <input type="text" name="company" class="form-control" value="{{old('code')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Images')}}</label>
                    <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
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

        let size_type = document.getElementById('size_type')
        let sizes_leg = document.getElementById('sizes_leg')

        let subcategory_exists = document.getElementById('subcategory_exists')

        let sub_category = {}

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')

        function addOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            subcategory_id.add(option)
            <option value="" selected disabled>{{__('Select sub category')}}</option>

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
                        data.data.forEach(addOption)
                    }
                })
            })
        })
    </script>
@endsection
