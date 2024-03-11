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
             <form action="{{route('images.update', $image->id)}}" method="POST" class="parsley-examples" enctype="multipart/form-data">
                 @csrf
                 @method("PUT")
                 @php
                     if($image) {
                        if($image->name){
                           $image->name = $image->name;
                        }else{
                            $image->name = 'no';
                        }
                     $model_image = storage_path('app/public/images/'.$image->name);
                     } else {
                     $model_image = 'no';
                     }
                 @endphp
                 <div class="d-flex justify-content-center mb-2">
                     @if(file_exists($model_image))
                         <img src="{{ asset('storage/images/'.$image->name) }}" alt="" height="400px">
                     @else
                         <img src="{{ asset('icon/no_photo.jpg') }}" alt="" height="400px">
                     @endif
                 </div>
                <br>
                <input type="file" name="image" class="form-control"/>
                <br>
                <button type="submit" class="btn btn-success">{{ translate('Update') }}</button>
            </form>
        </div>
    </div>
@endsection
