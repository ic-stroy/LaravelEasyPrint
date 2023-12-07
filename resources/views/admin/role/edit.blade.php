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
                {{translate('Role list edit')}}
            </p>
            <form action="{{route('role.update', $role->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label class="form-label">{{translate('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{$role->name??''}}"/>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
