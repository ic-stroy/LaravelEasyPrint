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
                {{__('Sub category list create')}}
            </p>
            <form action="{{route('subcategory.store')}}" class="parsley-examples" method="POST">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('category')}}</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
