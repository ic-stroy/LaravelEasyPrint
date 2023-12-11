@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .delete-datas{
            border-radius: 8px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" onclick="createCompany()">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Avatar')}}</th>
                        <th>{{translate('Firstname')}}</th>
                        <th>{{translate('Lastname')}}</th>
                        <th>{{translate('Role')}}</th>
                        <th>{{translate('Phone number')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($users as $user)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                {{$i}}
                            </a>
                        </th>
                        <td class="text-center">
                            <a class="show_page_color" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->id))
                                    @php
                                        if(isset($user->personalInfo)){
                                            $sms_avatar = storage_path('app/public/user/'.$user->personalInfo->avatar);
                                        }else{
                                            $sms_avatar = 'no';
                                        }
                                    @endphp
                                    @if(file_exists($sms_avatar))
                                        <img class="user_photo" src="{{asset('storage/user/'.$user->personalInfo->avatar??'no')}}" alt="">
                                    @else
                                        <img class="user_photo" src="{{asset('assets/images/man.jpg')}}" alt="">
                                    @endif
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->personalInfo->first_name)){{ $user->personalInfo->first_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->personalInfo->last_name)){{ $user->personalInfo->last_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->role_id)) {{$user->role->name}} @else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->phone_number)){{ $user->phone_number }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column mt-2">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" onclick="editCompany({{$user->id}})"><i class="fe-edit-2"></i></a>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{ route('user.destroy', $user->id) }}"><i class="fe-trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let edit_company = document.getElementById('edit_company')
        function createCompany(){
            refreshData('')
            window.location.href = "{{route('user.create')}}"
        }
        function editCompany(id){
            refreshData(id)
            window.location.href = `/user/${id}/edit`
        }
        function refreshData() {
            if(localStorage.getItem('district') != undefined && localStorage.getItem('district') != null){
                localStorage.removeItem('district')
            }
            if(localStorage.getItem('region') != undefined && localStorage.getItem('region') != null){
                localStorage.removeItem('region')
            }
            if(localStorage.getItem('address_lat') != undefined && localStorage.getItem('address_lat') != null){
                localStorage.removeItem('address_lat')
            }
            if(localStorage.getItem('address_long') != undefined && localStorage.getItem('address_long') != null){
                localStorage.removeItem('address_long')
            }
            if(localStorage.getItem('company_delivery_price') != undefined && localStorage.getItem('company_delivery_price') != null){
                localStorage.removeItem('company_delivery_price')
            }
            if(localStorage.getItem('company_name') != undefined && localStorage.getItem('company_name') != null){
                localStorage.removeItem('company_name')
            }
            if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null){
                localStorage.removeItem('region_id')
            }
            if(localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null) {
                localStorage.removeItem('district_id')
            }
        }

    </script>
@endsection
