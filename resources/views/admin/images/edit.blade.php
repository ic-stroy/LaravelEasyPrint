@extends('layout.layout')
@section('title')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-between">
                <div class="col-6">
                        <h4 class="mt-0 header-title">{{translate('Update images')}}</h4>
                </div>
            </div>
            <br>
             <form action="{{route('images.store',$id)}}" class="parsley-examples" enctype="multipart/form-data">
                @csrf @method("PUT")
                <input type="text" value="{{ $id }}">
                <input type="file" name="images[]" class="form-control"  multiple/>
                <br>
                <button type="submit" class="btn btn-success">{{ translate('Save') }}</button>
            </form>
        </div>
    </div>
@endsection
