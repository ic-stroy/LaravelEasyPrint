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
                {{translate('Banner list create')}}
            </p>
            <form action="{{route('banner.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">{{translate('Title')}}</label>
                            <input type="text" name="title" class="form-control" required value="{{old('title')}}"/>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">{{translate('Is active')}}</label>
                            <select id="is_active" class="form-select" name="is_active">
                                <option value="1">{{translate('Active')}}</option>
                                <option value="0">{{translate('No active')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{translate('Text')}}</label>
                        <textarea class="form-control" name="text" id="text" required cols="20" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">{{translate('Banner image')}}</label>
                            <input type="file" name="image" class="form-control" required value="{{old('image')}}"/>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">{{translate('Carousel Image')}}</label>
                            <input type="file" name="carusel_images[]" class="form-control" multiple required value="{{old('image')}}"/>
                        </div>
                    </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
