@extends('layout.layout')
@section('title')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-between">
                <div class="col-6">
                    <h4 class="mt-0 header-title">{{translate('Images')}}</h4>
                </div>
                <div class="col-6 text-end mb-2">
                    <a class="form_functions btn btn-success" href="{{route('images.create')}}">{{translate('Create')}}</a>
                </div>
            </div>

            <table class="table table-striped table-bordered  mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Image') }}</th>
                         <th>{{ translate('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @if(count($images) > 0) @php $i = 1; @endphp
                        @foreach($images as $key => $value)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    <img src="{{ asset('storage/images/'.$value->name) }}" alt="" height="40px">
                                </td>
                                <td class="py-1">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a class="form_functions btn btn-info" href="{{route('images.edit', $value->id)}}"><i class="fe-edit-2"></i></a>
                                        <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('images.destroy', $value->id)}}"><i class="fe-trash-2"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    @else
                        <tr>
                            <th colspan="3">{{ translate('No data!') }}</th>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
