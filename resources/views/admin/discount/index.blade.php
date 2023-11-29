@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Coupon lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('coupons.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Coupon quantity')}}</th>
                        <th>{{__('Category')}}</th>
                        <th>{{__('Product')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($coupons as $coupon)
                        @php
                            $i++;
                            if(isset($coupon->category->id)){
                                $category = $coupon->category->name;
                                $subcategory = '';
                            }elseif(isset($coupon->subCategory->id)){
                                $category = isset($coupon->subCategory->category)?$coupon->subCategory->category->name:'';
                                $subcategory = $coupon->subCategory->name;
                            }else{
                                $category = '';
                                $subcategory = '';
                            }
                        @endphp
                        <tr>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    {{$i}}
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if(isset($coupon->name))
                                        {{$coupon->name}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if ($coupon->price != null)
                                       {{$coupon->price}} {{translate(' sum')}}
                                    @elseif($coupon->percent != null)
                                       {{$coupon->percent}} {{translate(' %')}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if($category != '' || $subcategory != '')
                                        {{$category}} {{' '.$subcategory}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if(isset($coupon->product->id))
                                        {{$coupon->product->name}}
                                    @else
                                        {{translate('All products')}}
                                    @endif
                                </a>
                            </td>

                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('coupons.edit', $coupon->id)}}"><i class="fe-edit-2"></i></a>
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
