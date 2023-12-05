@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Sub sub category lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('subsubcategory.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Sub category')}}</th>
                        <th>{{__('Updated_at')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($subsubcategories as $subsubcategory)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('subsubcategory.show', $subsubcategory->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('subsubcategory.show', $subsubcategory->id)}}">
                                    @if(isset($subsubcategory->name)){{ $subsubcategory->name }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('subsubcategory.show', $subsubcategory->id)}}">
                                    @if(isset($subsubcategory->sub_category->name)){{ $subsubcategory->sub_category->name }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('subsubcategory.show', $subsubcategory->id)}}">
                                    @if(isset($subsubcategory->updated_at)){{ $subsubcategory->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('subsubcategory.edit', $subsubcategory->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('subsubcategory.destroy', $subsubcategory->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
