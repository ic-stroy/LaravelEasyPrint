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
                {{__('Banner list create')}}
            </p>
            <form action="{{route('banner.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">{{__('Title')}}</label>
                            <input type="text" name="title" class="form-control" required value="{{old('title')}}"/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{__('Text')}}</label>
                        <textarea class="form-control" name="text" id="text" required cols="20" rows="10">
                        </textarea>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">{{__('Image')}}</label>
                            <input type="file" name="image" class="form-control" required value="{{old('image')}}"/>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">{{__('Is active')}}</label>
                            <select id="is_active" class="form-select" name="is_active">
                                <option value="1">{{translate('Active')}}</option>
                                <option value="0">{{translate('No active')}}</option>
                            </select>
                        </div>
                    </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
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
