@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .delete-datas{
            border-radius: 8px;
        }
        #datatable-buttons{
            width: 100% !important;
         }
        .buttons-pdf, .buttons-copy{
            display: none;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" onclick="createCompany()">{{translate('Create')}}</a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills navtab-bg nav-justified">
                <li class="nav-item">
                    <a href="#superadmins" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                        {{translate('Superadmins')}}
                        @if(count($superadmins) > 0)
                            <span class="badge bg-danger">{{count($superadmins)}}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#admins" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        {{translate('Admins')}}
                        @if(count($admins) > 0)
                            <span class="badge bg-danger">{{count($admins)}}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#managers" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        {{translate('Managers')}}
                        @if(count($managers) > 0)
                            <span class="badge bg-danger">{{count($managers)}}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#users" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        {{translate('Users')}}
                        @if(count($users) > 0)
                            <span class="badge bg-danger">{{count($users)}}</span>
                        @endif
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="superadmins">
                    <table class="table table-striped table-bordered dt-responsive nowrap text-center">
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
                        @foreach($superadmins as $superadmin)
                            @php
                                $i++
                            @endphp
                            <tr>
                                <th scope="row">
                                    <a class="show_page" href="{{ route('user.show', $superadmin->id) }}">
                                        {{$i}}
                                    </a>
                                </th>
                                <td class="text-center">
                                    <a class="show_page_color" href="{{ route('user.show', $superadmin->id) }}">
                                        @if(isset($superadmin->id))
                                            @php
                                                if(isset($superadmin->personalInfo)){
                                                    $sms_avatar = storage_path('app/public/user/'.$superadmin->personalInfo->avatar);
                                                }else{
                                                    $sms_avatar = 'no';
                                                }
                                            @endphp
                                            @if(file_exists($sms_avatar))
                                                <img class="user_photo" src="{{asset('storage/user/'.$superadmin->personalInfo->avatar??'no')}}" alt="">
                                            @else
                                                <img class="user_photo" src="{{asset('assets/images/man.jpg')}}" alt="">
                                            @endif
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $superadmin->id) }}">
                                        @if(isset($superadmin->personalInfo->first_name)){{ $superadmin->personalInfo->first_name }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $superadmin->id) }}">
                                        @if(isset($superadmin->personalInfo->last_name)){{ $superadmin->personalInfo->last_name }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $superadmin->id) }}">
                                        @if(isset($superadmin->role_id)) {{$superadmin->role->name}} @else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                @if(isset($superadmin->company->name))
                                    <td>
                                        <a class="show_page" href="{{ route('user.show', $superadmin->id) }}">
                                            {{ $superadmin->company->name }}
                                        </a>
                                    </td>
                                @endif
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $superadmin->id) }}">
                                        @if($superadmin->phone_number){{ $superadmin->phone_number }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td class="function_column mt-2">
                                    <div class="d-flex justify-content-center">
                                        <a class="form_functions btn btn-info" onclick="editCompany({{$superadmin->id}})"><i class="fe-edit-2"></i></a>
                                        <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{ route('user.destroy', $superadmin->id) }}"><i class="fe-trash-2"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="admins">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Avatar')}}</th>
                            <th>{{translate('Firstname')}}</th>
                            <th>{{translate('Lastname')}}</th>
                            <th>{{translate('Role')}}</th>
                            <th>{{translate('Company')}}</th>
                            <th>{{translate('Phone number')}}</th>
                            <th class="text-center">{{translate('Functions')}}</th>
                        </tr>
                        </thead>
                        <tbody class="table_body">
                        @php
                            $i = 0
                        @endphp
                        @foreach($admins as $admin)
                            @php
                                $i++
                            @endphp
                            <tr>
                                <th scope="row">
                                    <a class="show_page" href="{{ route('user.show', $admin->id) }}">
                                        {{$i}}
                                    </a>
                                </th>
                                <td class="text-center">
                                    <a class="show_page_color" href="{{ route('user.show', $admin->id) }}">
                                        @if(isset($admin->id))
                                            @php
                                                if(isset($admin->personalInfo)){
                                                    $sms_avatar = storage_path('app/public/user/'.$admin->personalInfo->avatar);
                                                }else{
                                                    $sms_avatar = 'no';
                                                }
                                            @endphp
                                            @if(file_exists($sms_avatar))
                                                <img class="user_photo" src="{{asset('storage/user/'.$admin->personalInfo->avatar??'no')}}" alt="">
                                            @else
                                                <img class="user_photo" src="{{asset('assets/images/man.jpg')}}" alt="">
                                            @endif
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $admin->id) }}">
                                        @if(isset($admin->personalInfo->first_name)){{ $admin->personalInfo->first_name }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $admin->id) }}">
                                        @if(isset($admin->personalInfo->last_name)){{ $admin->personalInfo->last_name }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $admin->id) }}">
                                        @if(isset($admin->role_id)) {{$admin->role->name}} @else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                @if(isset($admin->company->name))
                                    <td>
                                        <a class="show_page" href="{{ route('user.show', $admin->id) }}">
                                            {{ $admin->company->name }}
                                        </a>
                                    </td>
                                @endif
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $admin->id) }}">
                                        @if(isset($admin->phone_number)){{ $admin->phone_number }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td class="function_column mt-2">
                                    <div class="d-flex justify-content-center">
                                        <a class="form_functions btn btn-info" onclick="editCompany({{$admin->id}})"><i class="fe-edit-2"></i></a>
                                        <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{ route('user.destroy', $admin->id) }}"><i class="fe-trash-2"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="managers">
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Avatar')}}</th>
                            <th>{{translate('Firstname')}}</th>
                            <th>{{translate('Lastname')}}</th>
                            <th>{{translate('Role')}}</th>
                            <th>{{translate('Company')}}</th>
                            <th>{{translate('Phone number')}}</th>
                            <th class="text-center">{{translate('Functions')}}</th>
                        </tr>
                        </thead>
                        <tbody class="table_body">
                        @php
                            $i = 0
                        @endphp
                        @foreach($managers as $manager)
                            @php
                                $i++
                            @endphp
                            <tr>
                                <th scope="row">
                                    <a class="show_page" href="{{ route('user.show', $manager->id) }}">
                                        {{$i}}
                                    </a>
                                </th>
                                <td class="text-center">
                                    <a class="show_page_color" href="{{ route('user.show', $manager->id) }}">
                                        @if(isset($manager->id))
                                            @php
                                                if(isset($manager->personalInfo)){
                                                    $sms_avatar = storage_path('app/public/user/'.$manager->personalInfo->avatar);
                                                }else{
                                                    $sms_avatar = 'no';
                                                }
                                            @endphp
                                            @if(file_exists($sms_avatar))
                                                <img class="user_photo" src="{{asset('storage/user/'.$manager->personalInfo->avatar??'no')}}" alt="">
                                            @else
                                                <img class="user_photo" src="{{asset('assets/images/man.jpg')}}" alt="">
                                            @endif
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $manager->id) }}">
                                        @if(isset($manager->personalInfo->first_name)){{ $manager->personalInfo->first_name }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $manager->id) }}">
                                        @if(isset($manager->personalInfo->last_name)){{ $manager->personalInfo->last_name }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $manager->id) }}">
                                        @if(isset($manager->role_id)) {{$manager->role->name}} @else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                @if(isset($manager->company->name))
                                    <td>
                                        <a class="show_page" href="{{ route('user.show', $manager->id) }}">
                                            {{ $manager->company->name }}
                                        </a>
                                    </td>
                                @endif
                                <td>
                                    <a class="show_page" href="{{ route('user.show', $manager->id) }}">
                                        @if(isset($manager->phone_number)){{ $manager->phone_number }}@else <div class="no_text"></div> @endif
                                    </a>
                                </td>
                                <td class="function_column mt-2">
                                    <div class="d-flex justify-content-center">
                                        <a class="form_functions btn btn-info" onclick="editCompany({{$manager->id}})"><i class="fe-edit-2"></i></a>
                                        <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{ route('user.destroy', $manager->id) }}"><i class="fe-trash-2"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="users">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
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
                                        @if(isset($user->email)){{ $user->email }}@else <div class="no_text"></div> @endif
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
