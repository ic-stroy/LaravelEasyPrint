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
                {{__('Address edit')}}
            </p>
            <form action="{{route('address.update', $address->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{$address->name??''}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Longitude')}}</label>
                    <input type="text" name="longitude" class="form-control" required value="{{$address->longitude??''}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Latitude')}}</label>
                    <input type="text" name="latitude" class="form-control" required value="{{$address->latitude??''}}"/>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
