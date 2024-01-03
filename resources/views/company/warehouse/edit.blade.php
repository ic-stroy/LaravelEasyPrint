@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .delete_warehouse_func{
            height: 20px;
            background-color: transparent;
            border: 0px;
            color: silver;
        }
        .product_image img{

        }
        .warehouse_image{
            margin-right: 4px;
            transition: 0.4s;
            padding:10px;
            border-radius: 4px;
        }
        .warehouse_image:hover{
            border: lightgrey;
            transform: scale(1.02);
            background-color: rgba(0, 0, 0, 0.1);
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
                {{translate('Warehouse products list edit')}}
            </p>
            <form action="{{route('warehouse.update', $warehouse->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label" for="product_id">{{translate('Product')}}</label>
                        <input name="product_id" type="hidden" class="form-control" required value="{{$product->id}}">
                        <input class="form-control" readonly id="product_id" value="{{$product->name!='no'?$product->name:''}}">
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Price')}}</label>
                        @if(isset($warehouse->price))
                            <input name="price" class="form-control" required id="price" value="{{$warehouse->price}}">
                        @elseif(isset($warehouse->product->price))
                            <input name="price" class="form-control" required id="price" value="{{$warehouse->product->price}}">
                        @else
                            <input name="price" class="form-control" required id="price" value="">
                        @endif
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Name')}}</label>
                        <input name="name" value="{{$warehouse->name}}" type="text" class="form-control" id="name">
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Category')}}</label>
                        <input name="category_id" type="hidden" class="form-control" required value="{{$current_category->id}}">
                        <input class="form-control" readonly id="category_id" value="{{$current_category!='no'?$current_category->name:''}}">
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Sizes')}}</label>
                        <select name="size_id" class="form-control" required id="size_types">
                            @foreach($sizes as $size_)
                                <option {{$warehouse->size_id ==$size_->id?'selected':'' }} value="{{$size_->id}}">{{$size_->name}} {{translate('size')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Color')}}</label>
                        <select name="color_id" required class="form-control" id="colors_id">
                            <option value="">{{translate('Choose product color')}}</option>
                            @foreach($colors as $color)
                                <option @if($color->id == $warehouse->color_id) selected @endif value="{{$color->id}}" style="background-color: {{$color->code}};">{{$color->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    @php
                        if(isset($warehouse->images) && !is_array($warehouse->images)){
                            $images = json_decode($warehouse->images);
                        }else{
                            $images = [];
                        }
                    @endphp
                    <div class="row">
                        @foreach($images as $image)
                            @php
                                $avatar_main = storage_path('app/public/warehouses/'.$image);
                            @endphp
                            @if(file_exists(storage_path('app/public/warehouses/'.$image)))
                                <div class="col-2 mb-3 warehouse_image">
                                    <div class="d-flex justify-content-between">
                                        <img src="{{asset('storage/warehouses/'.$image)}}" alt="" height="200px">
                                        <button class="delete_warehouse_func">X</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Quantity')}}</label>
                        <input type="number" name="quantity" required class="form-control" value="{{$warehouse->quantity}}"/>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Images')}}</label>
                        <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Manufacturer country')}}</label>
                        <input type="text" name="manufacturer_country" class="form-control" value="{{$warehouse->manufacturer_country}}"/>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Material composition')}}</label>
                        <input type="text" name="material_composition" class="form-control" value="{{$warehouse->material_composition}}"/>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Description')}}</label>
                        <textarea class="form-control" name="description" id="description" cols="20" rows="2">{{$warehouse->description}}</textarea>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let warehouse_image = document.getElementsByClassName('warehouse_image')
        let delete_warehouse_func = document.getElementsByClassName('delete_warehouse_func')
        let deleted_text = "{{translate('Warehouse image was deleted')}}"
        let warehouse_images = []
        @if(is_array($images))
        @php
            if(!isset($images) || count($images)<0){
                 $warehouse_images = [];
            }else{
                $warehouse_images = $images;
            }
        @endphp
        @foreach($warehouse_images as $warehouse_image)
            warehouse_images.push("{{$warehouse_image}}")
        @endforeach
        @endif
        function deleteWarehouseFunc(item, val) {
            delete_warehouse_func[item].addEventListener('click', function (e) {
                e.preventDefault()
                $.ajax({
                    url: '/api/delete-warehouse',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id:"{{$warehouse->id}}",
                        warehouse_name: warehouse_images[item]
                    },
                    success: function(data){
                        if(data.status == true){
                            toastr.success(deleted_text)
                        }
                    }
                });
                if(!warehouse_image[item].classList.contains('display-none')){
                    warehouse_image[item].classList.add('display-none')
                }
            })
        }
        Object.keys(delete_warehouse_func).forEach(deleteWarehouseFunc)
    </script>
@endsection
