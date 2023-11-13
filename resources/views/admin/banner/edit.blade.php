@extends('layout.layout')

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
                {{__('Banner list edit')}}
            </p>
            <form action="{{route('banner.update', $banner->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">{{__('Title')}}</label>
                        <input type="text" name="title" class="form-control" required value="{{$banner->title}}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Text')}}</label>
                    <textarea class="form-control" name="text" id="text" required cols="20" rows="10">
                        {!! $banner->text !!}
                    </textarea>
                </div>
                <div class="mb-3">
                    <div class="row">
                            @php
                                if(!isset($banner->image)){
                                     $banner_image = 'no';
                                }else{
                                    $banner_image = $banner->image;
                                }
                                $avatar_main = storage_path('app/public/banner/'.$banner_image);
                            @endphp
                            @if(file_exists($avatar_main))
                                <div class="col-2 mb-3">
                                    <img src="{{asset('storage/banner/'.$banner_image)}}" alt="" height="200px">
                                </div>
                            @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Image')}}</label>
                        <input type="file" name="image" class="form-control" required value="{{old('image')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Is active')}}</label>
                        <select id="is_active" class="form-select" name="is_active">
                            <option value="1" {{$banner->is_active == 1?'selected':''}}>{{translate('Active')}}</option>
                            <option value="0" {{$banner->is_active == 0?'selected':''}}>{{translate('No active')}}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#text' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
@endsection
