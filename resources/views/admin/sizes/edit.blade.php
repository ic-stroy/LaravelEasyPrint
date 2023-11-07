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
                {{__('Size list edit')}}
            </p>
            <form action="{{route('size.update', $size->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="row size_store mb-3">
                    <div class="col-md-5 col-sm-6">
                        <label class="form-label">{{__('Name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{$size->name??''}}"/>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">{{__('Status')}}</label>
                        <select name="status" class="form-control">
                            <option value="1" {{$size->status == 1?'selected':''}}>{{__('Active')}}</option>
                            <option value="0" {{$size->status != 1?'selected':''}}>{{__('No active')}}</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label">{{__('Type')}}</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{$size->category_id == $category->id?'selected':''}}>{{$category->name}}</option>
                            @endforeach
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
@endsection
