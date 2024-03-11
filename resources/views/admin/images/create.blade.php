@extends('layout.layout')
@section('title')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-between">
                <div class="col-6">
                        <h4 class="mt-0 header-title">{{translate('Create images')}}</h4>
                </div>
            </div>
            <br>
             <form action="{{route('images.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf @method("POST")
                <input type="file" name="image" class="form-control"/>
                <br>
                <button type="submit" class="btn btn-success">{{ translate('Save') }}</button>
            </form>
        </div>
    </div>
@endsection
