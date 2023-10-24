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
                            <option value="{{$category->id}}" @if($current_category != 'no') {{$current_category->id == $category->id?'selected':'' }} @endif>
                                {{$category->name}} {{$category->category?$category->category->name:''}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Sub category')}}</label>
                    <select name="subcategory_id" class="form-control" id="subcategory_id">
                        @if($current_category != 'no')
                            @foreach($current_category->subCategory as $subcategory)
                                <option value="{{$subcategory->id}}" {{$product->subcategory_id == $subcategory->id?'selected':''}}>{{$subcategory->name}} {{$subcategory->category?$subcategory->category->name:''}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Sum')}}</label>
                    <input type="number" name="sum" class="form-control" required value="{{$product->sum}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Company')}}</label>
                    <input type="text" name="company" class="form-control" value="{{$product->company}}"/>
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
                <div class="mb-3">
                    <label class="form-label">{{__('Images')}}</label>
                    <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
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
