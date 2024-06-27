@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('User informations')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('company_user.create')}}">{{translate('Create')}}</a>
            </div>
            <div class="account">
                <div class="profile_box">
                    <div class="d-flex align-items-start">
                        <div>
                            @if($model->personalInfo)
                                @php
                                    $model->personalInfo->avatar = $model->personalInfo->avatar??'no';
                                    $sms_avatar = storage_path('app/public/user/'.$model->personalInfo->avatar);
                                @endphp
                                @if(file_exists($sms_avatar))
                                    <img class="user_photo_2" src="{{asset('storage/user/'.$model->personalInfo->avatar)}}" alt="">
                                @else
                                    <img class="user_photo_2" src="{{asset('assets/images/man.jpg')}}" alt="">
                                @endif
                            @endif
                        </div>
                        <div id="color_black" style="margin-left: 30px;">
                            <h3 >@if($model->personalInfo){{$model->personalInfo->first_name.' '.$model->personalInfo->last_name.' '.$model->personalInfo->middle_name}}@endif</h3>
                            <p>{{translate('Role').': '}}
                                <b>
                                    @if($model->role)
                                        @if($model->role->name)
                                            {{$model->role->name}}
                                        @else <div class="no_text"></div> @endif
                                    @else <div class="no_text"></div> @endif
                                </b>
                            </p>
                            <p>{{translate('Phone').': '}}<b>{{$model->phone_number??''}}</b></p>
                            <p>{{translate('Age').': '}}<b>{{$year_old??''}}</b></p>
                        </div>
                    </div>

                    <div class="profile_box_content">
                        <div style="width: auto;">
                            <div class="d-flex justify-content-between" style="align-items: center">
                                <h3 class="text_name">{{translate('Email')}}:</h3>
                                <div class="text_value">
                                    {{$model->email??''}}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Role')}}:</h3>
                                <div class="text_value">
                                    @if($model->role)
                                        @if($model->role->name)
                                            {{$model->role->name}}
                                        @else <div class="no_text"></div> @endif
                                    @else <div class="no_text"></div> @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Gender')}}:</h3>
                                <div class="text_value">
                                    @if($model->personalInfo)
                                        @if($model->personalInfo->gender)
                                            {{$model->personalInfo->gender==2?translate('female'):translate('male')}}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="width: auto;">
                            <div class="d-flex justify-content-between" style="align-items: center">
                                <h3 class="text_name">{{translate('Full Name')}}:</h3>
                                <div class="text_value">
                                    @if($model->personalInfo)
                                        {{$model->personalInfo->first_name.' '.$model->personalInfo->last_name.' '.$model->personalInfo->middle_name}}
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Birth date')}}:</h3>
                                @php
                                    if($model->personalInfo){
                                        if($model->personalInfo->birth_date){
                                                $birth_date_arr = explode(' ', $model->personalInfo->birth_date);
                                        }else{
                                            $birth_date_arr = [];
                                        }
                                    }else{
                                        $birth_date_arr = [];
                                    }
                                @endphp
                                <div class="text_value">
                                    {{$birth_date_arr[0]??''}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Update at')}}:</h3>
                                <div class="text_value">
                                    {{$model->updated_at??''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
