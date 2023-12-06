@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Discount lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('discount.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Discount percent')}}</th>
                        <th>{{__('Category')}}</th>
                        <th>{{__('Product')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($discounts as $discount)
                        @php
                            $i++;
                            $category = '';
                            $subcategory = '';
                            if(isset($discount->product->id)){
                                if(isset($discount->product->category->id)){
                                    $category = $discount->product->category->name;
                                    $subcategory = '';
                                }elseif(isset($discount->product->subCategory->id)){
                                    $category = isset($discount->product->subCategory->category)?$discount->product->subCategory->category->name:'';
                                    $subcategory = $discount->product->subCategory->name;
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount->id)}}">
                                    {{$i}}
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount->id)}}">
                                    @if($discount->percent != null)
                                       {{$discount->percent}} {{translate(' %')}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount->id)}}">
                                    @if($category != '' || $subcategory != '')
                                        {{$category}} {{' '.$subcategory}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('discount.show', $discount->id)}}">
                                    @if(isset($discount->product->id))
                                        {{$discount->product->name}}
                                    @else
                                        {{translate('All products')}}
                                    @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('discount.edit', $discount->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('discount.destroy', $discount->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
