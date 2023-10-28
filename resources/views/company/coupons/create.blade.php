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
                {{__('Products list create')}}
            </p>
            <form action="{{route('product.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{__('Coupon type')}}</label>
                            <select name="coupon_type" class="form-control" id="coupon_type">
                                    <option value="price" class="form-control">price</option>
                                    <option value="percent" class="form-control">percent</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{__('Sum')}}</label>
                            <input type="number" name="sum" class="form-control" required value="{{old('sum')}}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{__('Relation type')}}</label>
                            <select name="relation_type" class="form-control" id="relation_type">
                                    <option value="product" class="form-control" id="product">product</option>
                                    <option value="category" class="form-control" id="category">category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                    <label class="form-label">{{__('Relation')}}</label>
                    <select name="subcategory_id" class="form-control" id="subcategory_id">
                        @if(isset($firstcategory->subCategory))
                            @foreach($firstcategory->subCategory as $subcategory)
                                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                    </div>
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
            $(document).ready(function() {
                $("#relation_type").change(function(){
                Var value =  $("# relation_type option: selected");
                alert(value.text());
                })
            });


            // console.log($("#relation_type" ).val(););

        // let size_type = document.getElementById('size_type')
        // let sizes_leg = document.getElementById('sizes_leg')

        // let sub_category = {}

        let category_id = document.getElementById('relation_id')
        console.log(category_id.value);
        // let subcategory_id = document.getElementById('subcategory_id')

        function addOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            category_id.add(option)
        }
        // console.log(category_id);

        // category_id.addEventListener('change', function () {
        //     subcategory_id.innerHTML = ""
        //     $(document).ready(function () {
        //         $.ajax({
        //             url:`/../api/subcategory/${category_id.value}`,
        //             type:'GET',
        //             success: function (data) {
        //                 data.data.forEach(addOption)
        //             }
        //         })
        //     })
        // })
    </script>
@endsection
