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
            {{-- {{route('company_product.update', $warehouse->id)}} --}}
            <form action="{{route('company_coupon.update', $coupon->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{__('Coupon type')}}</label>
                            <select name="coupon_type" class="form-control" id="coupon_type">
                                    <option value="1"></option>
                                    <option value="price" class="form-control" <?php echo ($coupon->price != null ? ' selected="selected"' : '');?>>price</option>
                                    <option value="percent" class="form-control" <?php echo ($coupon->percent != null ? ' selected="selected"' : '');?> >percent</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{__('Sum')}}</label>
                            @if ($coupon->price != null)

                            <input type="number" name="sum" class="form-control" required value="{{$coupon->price}}"/>

                            @else
                            <input type="number" name="sum" class="form-control" required value="{{$coupon->percent}}"/>

                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{__('Relation type')}}</label>
                            <select name="relation_type" class="form-control" id="relation_type">
                                <option value=""></option>
                                    <option value="product" class="form-control" id="product"  <?php echo ($coupon->warehouse_product_id != null ? ' selected="selected"' : '');?>>product</option>
                                    <option value="category" class="form-control" id="category" <?php echo ($coupon->category_id != null ? ' selected="selected"' : '');?>>category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            @if ($coupon->warehouse_product_id != null)
                                @php
                                    $warehouse_product=App\Models\Warehouse::where('id',$coupon->warehouse_product_id)->first();
                                    $relations=App\Models\Warehouse::where('company_id',$coupon->company_id)->pluck('name','id');
                                    $id=$warehouse_product->id;
                                    // dd($relations)
                                    // dd($name);
                                    // echo $name;

                                @endphp
                            @elseif ($coupon->category_id != null)
                                @php
                                    $cetagory=App\Models\Category::where('id',$coupon->category_id)->first();
                                    $relations=App\Models\Category::pluck('name','id');
                                    $id=$cetagory->id;
                                    // dd($aaa);
                                    // echo $categories;

                                @endphp
                            @else
                                {{'name not found'}}
                            @endif
                            <label class="form-label">{{__('Relation')}}</label>
                            <select name="relation_id" class="form-control" id="relation_id">
                                @foreach($relations as $key => $relation)
                                {{-- @dd($relations) --}}
                                <option value="{{$key}}" @if($key) {{$key == $id?'selected':'' }} @endif>
                                    {{$relation}}
                                </option>
                                @endforeach
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


        let category_id = document.getElementById('relation_id')


        $(document).on('change', '#relation_type', function(e) {
            let val = $(this).val()
            console.log(val);
            $(document).ready(function () {
                $.ajax({
                    url:`./relation`,
                    data: {
                        'relation': val
                    },
                    type:'GET',
                    success: function (data) {
                        console.log(data);


                        // array.forEach(element => {

                        // });
                        // for (var i = 0; i<=data; i++){
                        //     var opt = document.createElement('option');
                        //     // opt.value = i;
                        //     console.log(i);
                        //     // opt.innerHTML = i;
                        //     // select.appendChild(opt);
                        //     $('#relation_id').appendChild(opt);
                        // }
                        document.querySelector('#relation_id').innerHTML = ''
                        data.map(i => {
                            var opt = document.createElement('option');
                            opt.value = i.id;
                            console.log(i);
                            opt.innerHTML = i.name;
                            // select.appendChild(opt);
                            // $('#relation_id').appendChild(opt);
                            // document.querySelector('#relation_id').innerHTML = ''
                            document.querySelector('#relation_id').appendChild(opt);
                        })
                        // $('#relation_id').html(data)
                        // $('#relation_id').innerHTMl
                    }
                })
            })
        })

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
