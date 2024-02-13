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
                {{translate('Pick up list edit')}}
            </p>
            <form action="{{route('pick_up.update', $address->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="row size_store mb-3">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Region')}}</label>
                        <select name="region_id" class="form-control" id="region_id" required>
                            <option disabled selected>{{translate('Select region')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('District')}}</label>
                        <select name="district_id" class="form-control" id="district_id" required>
                            <option disabled selected>{{translate('Select district')}}</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">{{translate('Name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{$address->name}}"/>
                    </div>
                    <div class="col-6">
                        <label class="form-label">{{translate('Postcode')}}</label>
                        <input type="text" name="postcode" class="form-control" value="{{$address->postcode}}">
                    </div>
                    <input type="hidden" name="region" id="region">
                    <input type="hidden" name="district" id="district">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let page = true
        @if(isset($address) && isset($address->cities))
            let current_region = "{{$address->cities->region->id??''}}"
            let current_district = "{{$address->cities->id??''}}"
        @else
            let current_region = ''
            let current_district = ''
        @endif
    </script>
    <script src="{{asset('assets/js/pickuppoint.js')}}"></script>
@endsection
