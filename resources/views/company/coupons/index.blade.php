@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Coupon lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('company_coupon.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Coupon type')}}</th>
                        <th>{{__('value')}}</th>
                        <th>{{__('Relation type')}}</th>
                        <th>{{__('Name')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($coupons as $coupon)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <td>
                                <a class="show_page" >
                                    {{$i}}
                                </a>
                            </td>
                            <td>
                                <a class="show_page" >
                                    @if ($coupon->price != null)
                                       price
                                    @else
                                       percent
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" >
                                    @if ($coupon->price != null)
                                       {{$coupon->price}}
                                    @else
                                       {{$coupon->percent}}
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" >
                                    @if ($coupon->warehouse_product_id != null)
                                       warehouse product
                                    @else
                                       Category
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" >
                                    @if ($coupon->warehouse_product_id != null)
                                        @php
                                            $name=App\Models\Warehouse::where('id',$coupon->warehouse_product_id)->first()->name;
                                            // dd($name);
                                            echo $name;

                                        @endphp
                                    @elseif ($coupon->category_id != null)
                                        @php
                                            $aaa=App\Models\Category::where('id',$coupon->category_id)->first()->name;
                                            // dd($aaa);
                                            echo $aaa;

                                        @endphp
                                    @else
                                        {{'name not found'}}
                                    @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    {{-- href="{{route('company_product.edit', $coupon->id)}}" --}}
                                    <a class="form_functions btn btn-info" href="{{route('company_coupon.edit', $coupon->id)}}"><i class="fe-edit-2"></i></a>
                                    {{-- {{route('product.destroy', $coupon->id)}} --}}
                                    <form action="" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="form_functions btn btn-danger" type="submit"><i class="fe-trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection