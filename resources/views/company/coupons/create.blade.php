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
            <form action="{{route('company_coupon.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
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
                    <select name="realtion_id" class="form-control" id="realtion_id">

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
                        for (var i = 0; i<=data; i++){
                            var opt = document.createElement('option');
                            // opt.value = i;
                            console.log(i);
                            // opt.innerHTML = i;
                            // select.appendChild(opt);
                            $('#realtion_id').appendChild(opt);
                        }

                        data.map(i => {
                            var opt = document.createElement('option');
                            opt.value = i.id;
                            console.log(i);
                            opt.innerHTML = i.name;
                            // select.appendChild(opt);
                            // $('#realtion_id').appendChild(opt);
                            document.querySelector('#realtion_id').appendChild(opt);
                        })
                        // $('#realtion_id').html(data)
                        // $('#realtion_id').innerHTMl
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
