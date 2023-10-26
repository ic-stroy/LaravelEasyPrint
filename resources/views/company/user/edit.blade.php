@extends('company.layout.layout')


@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-14">
                {{__('User edit')}}
            </p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('user.update', $user->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('First name')}}</label>
                        <input type="text" class="form-control" name="first_name" required value="{{$user->personalInfo?$user->personalInfo->first_name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Last name')}}</label>
                        <input type="text" class="form-control" name="last_name" value="{{$user->personalInfo?$user->personalInfo->last_name:''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Middle name')}}</label>
                        <input type="text" class="form-control" name="middle_name" value="{{$user->personalInfo?$user->personalInfo->middle_name:''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Phone number')}}</label>
                        <input type="text" class="form-control" name="phone_number" value="{{$user->phone_number??''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <div style="text-align: center">
                            @if(isset($user->personalInfo->avatar))
                                @php
                                    $avatar = storage_path('app/public/user/'.$user->personalInfo->avatar)
                                @endphp
                            @else
                                @php
                                    $avatar = 'no'
                                @endphp
                            @endif
                            @if(file_exists($avatar))
                                <img src="{{asset('storage/user/'.$user->personalInfo->avatar)}}" alt="" height="94px">
                            @endif
                        </div>
                        <label class="form-label">{{__('Avatar')}}</label>
                        <input type="file" class="form-control" name="avatar" value=""/>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="gender" class="form-label">{{__('Gender')}}</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="">{{__('Choose..')}}</option>
                            <option value="1" @if(isset($user->personalInfo)){{$user->personalInfo->gender==1?'selected':''}}@endif>{{__('Man')}}</option>
                            <option value="2" @if(isset($user->personalInfo)){{$user->personalInfo->gender==2?'selected':''}}@endif>{{__('Woman')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <div class="mb-3 col-6">
                            <label for="role" class="form-label">{{__("Users' role")}}</label><br>
                            <select id="role_id" class="form-select" name="role_id">
                                <option value="">{{__('Choose..')}}</option>
                                <option value="0" {{$user->role_id==0?'selected':''}}>{{__('Stuff')}}</option>
                                <option value="1" {{$user->role_id==1?'selected':''}}>{{__('Seller')}}</option>
                                <option value="2" {{$user->role_id==2?'selected':''}}>{{__('User')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Birth date')}}</label>
                        @php
                            $birth_date = explode(' ', $user->personalInfo->birth_date??'');
                        @endphp
                        <input type="date" class="form-control" name="birth_date" value="{{$birth_date[0]}}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Login')}}</label>
                    <input type="email" class="form-control" name="email" required value="{{$user->email??''}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Current password')}}</label>
                    <input type="password" class="form-control" name="password" value=""/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('New password')}}</label>
                    <input type="password" class="form-control" name="new_password" value=""/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Password confirmation')}}</label>
                    <input type="password" class="form-control" name="password_confirmation" value=""/>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
