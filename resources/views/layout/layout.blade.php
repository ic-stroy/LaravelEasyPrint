<!DOCTYPE html>
<html lang="en">
@php
    $current_user = \Illuminate\Support\Facades\Auth::user();
@endphp
<head>
    <meta charset="utf-8" />
    {{-- <title>{{ $title ?? translate('Pitac') }}</title> --}}
    <title>{{ $title ?? 'Admin EasyGo' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <link href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}" type="text/css" rel="stylesheet"/>
    <!-- third party css -->
    <link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style"/>
      {{-- Main css --}}
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<style>
#dropdownMenuButton{
    z-index: 1;
}

</style>
<!-- body start -->

<body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" id="body_layout"
    data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default'
    data-sidebar-user='true'>

    <!-- Begin page -->
    <div id="wrapper">


        <!-- Topbar Start -->
        <div class="navbar-custom">
                <ul class="list-unstyled topnav-menu float-end mb-0">

                    <li class="d-none d-lg-block">
                        <form class="app-search">
                            <div class="app-search-box">
                                <div class="input-group">
                                    {{-- <input type="text" class="form-control" placeholder="{{ translate('Search...') }}" id="top-search"> --}}
                                    <input type="text" class="form-control" placeholder="Search..." id="top-search">
                                    <button class="btn input-group-text" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                                <div class="dropdown-menu dropdown-lg" id="search-dropdown">
                                    <!-- item-->
                                    <div class="dropdown-header noti-title">
                                        <h5 class="text-overflow mb-2">{{translate('Found 22 results')}}</h5>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="fe-home me-1"></i>
                                        <span>{{translate('Analytics Report')}}</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="fe-aperture me-1"></i>
                                        <span>{{translate('How can I help you?')}}</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="fe-settings me-1"></i>
                                        <span>{{translate('User profile settings')}}</span>
                                    </a>

                                    <!-- item-->
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow mb-2 text-uppercase">{{translate('Users')}}</h6>
                                    </div>

                                    <div class="notification-list">
                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="d-flex align-items-start">
                                                <img class="d-flex me-2 rounded-circle" src="{{ asset('assets/images/users/user-2.jpg') }}"
                                                     alt="Generic placeholder image" height="32">
                                                <div class="w-100">
                                                    <h5 class="m-0 font-14">Erwin E. Brown</h5>
                                                    <span class="font-12 mb-0">{{translate('UI Designer')}}</span>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="d-flex align-items-start">
                                                <img class="d-flex me-2 rounded-circle" src="{{ asset('assets/images/users/user-5.jpg') }}"
                                                     alt="Generic placeholder image" height="32">
                                                <div class="w-100">
                                                    <h5 class="m-0 font-14">Jacob Deo</h5>
                                                    <span class="font-12 mb-0">{{translate('Developer')}}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="">
                        <div>
                            @php
                                if (session()->has('locale')) {
                                    $locale = session('locale');
                                } else {
                                    $locale = env('DEFAULT_LANGUAGE', 'ru');
                                }
                                // $locale=app()->getLocale()?? env('DEFAULT_LANGUAGE');
                            @endphp
                            <div class="align-items-stretch d-flex dropdown" id="lang-change">
                                <a class="buttonUzbDropDownHeader dropdown-toggle" type="button" id="dropdownMenuButton" role="button"
                                   data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="javascript:void(0);">
                                    @switch($locale)
                                        @case('uz')
                                        <img class="notifRegion2" id="selected_language"
                                             src="{{ asset('/assets/images/language/region.png') }}" alt="region">
                                        @break

                                        @case('en')
                                        <img class="notifRegion2" id="selected_language"
                                             src="{{ asset('/assets/images/language/GB.png') }}" alt="region">
                                        @break

                                        @case('ru')
                                        <img class="notifRegion2" id="selected_language"
                                             src="{{ asset('/assets/images/language/RU.png') }}" alt="region">
                                        @break
                                    @endswitch
                                </a>
                                <div id="language_flag" class="language_flag display-none"
                                     style="border: none; background-color: transparent;" aria-labelledby="dropdownMenuButton">
                                    <div class="up-arrow"></div>
                                    <div class="dropdownMenyApplyUzbFlag">
                                        @foreach (\App\Models\Language::all() as $key => $language)
                                            <a href="javascript:void(0)" data-flag="{{ $language->code??'' }}"
                                               class="dropdown-item dropdown-item dropdownLanguageItem @if ($locale == $language->code??'') active @endif" >
                                                @switch($language->code)
                                                    @case('uz')
                                                    <img class="dropdownRegionBayroq" id="lang_uz" style="margin-right: 8px;" src="{{asset('/assets/images/language/region.png')}}" alt="region">
                                                    {{ $language->name??'' }}
                                                    @break

                                                    @case('ru')
                                                    <img class="dropdownRegionBayroq" id="lang_ru" style="margin-right: 8px;"
                                                         src="{{ asset('/assets/images/language/RU.png') }}" alt="region">
                                                    {{ $language->name??'' }}
                                                    @break

                                                    @case('en')
                                                    <img class="dropdownRegionBayroq" id="lang_en" style="margin-right: 8px;"
                                                         src="{{ asset('/assets/images/language/GB.png') }}" alt="region">
                                                    {{ $language->name??'' }}
                                                    @break
                                                @endswitch
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown"
                           href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                            <form class="p-3">
                                <input type="text" class="form-control" placeholder="Search ..."
                                       aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown"
                           href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            <span class="badge bg-danger rounded-circle noti-icon-badge">9</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">
                                    <span class="float-end">
                                        <a href="" class="text-dark">
                                            <small>{{translate('Clear All')}}</small>
                                        </a>
                                    </span>{{translate('Notification')}}
                                </h5>
                            </div>

                            <div class="noti-scroll" data-simplebar>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                    <div class="notify-icon">
                                        <img src="{{ asset('assets/images/users/user-1.jpg') }}" class="img-fluid rounded-circle"
                                             alt="" />
                                    </div>
                                    <p class="notify-details">Cristina Pride</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>{{translate('Hi, How are you? What about our next meeting')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-primary">
                                        <i class="mdi mdi-comment-account-outline"></i>
                                    </div>
                                    <p class="notify-details">{{translate('Caleb Flakelar commented on Admin')}}
                                        <small class="text-muted">{{translate('1 min ago')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon">
                                        <img src="{{ asset('assets/images/users/user-4.jpg') }}" class="img-fluid rounded-circle"
                                             alt="" />
                                    </div>
                                    <p class="notify-details">Karen Robinson</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>{{ translate('Wow ! this admin looks good and awesome design')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-warning">
                                        <i class="mdi mdi-account-plus"></i>
                                    </div>
                                    <p class="notify-details">{{ translate('New user registered.')}}
                                        <small class="text-muted">{{ translate('5 hours ago')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-info">
                                        <i class="mdi mdi-comment-account-outline"></i>
                                    </div>
                                    <p class="notify-details">{{ translate('Caleb Flakelar commented on Admin')}}
                                        <small class="text-muted">{{ translate('4 days ago')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-secondary">
                                        <i class="mdi mdi-heart"></i>
                                    </div>
                                    <p class="notify-details">Carlos Crouch {{ translate('liked')}}
                                        <b>Admin</b>
                                        <small class="text-muted">{{ translate('13 days ago')}}</small>
                                    </p>
                                </a>
                            </div>

                            <!-- All-->
                            <a href="javascript:void(0);"
                               class="dropdown-item text-center text-primary notify-item notify-all">
                                {{ translate('View all')}}
                                <i class="fe-arrow-right"></i>
                            </a>

                        </div>
                    </li>

{{--                    <li class="dropdown notification-list topbar-dropdown">--}}
{{--                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"--}}
{{--                           data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"--}}
{{--                           aria-expanded="false">--}}
{{--                            @if(isset($current_user->personalInfo))--}}
{{--                                @php--}}
{{--                                    $sms_avatar = storage_path('app/public/user/'.$current_user->personalInfo->avatar??'no');--}}
{{--                                @endphp--}}
{{--                                @if(file_exists($sms_avatar))--}}
{{--                                    <img class="rounded-circle" src="{{asset('storage/user/'.$current_user->personalInfo->avatar)}}" alt="">--}}
{{--                                @else--}}
{{--                                    <img class="rounded-circle" src="{{asset('assets/images/man.jpg')}}" alt="">--}}
{{--                                @endif--}}
{{--                                <span class="pro-user-name ms-1">--}}
{{--                                {{$current_user->personalInfo->first_name??''}} <i class="mdi mdi-chevron-down"></i>--}}
{{--                            </span>--}}
{{--                            @endif--}}
{{--                        </a>--}}

{{--                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">--}}
{{--                            <!-- item-->--}}
{{--                            <div class="dropdown-header noti-title">--}}
{{--                                <h6 class="text-overflow m-0">{{ translate('Welcome !')}}</h6>--}}
{{--                            </div>--}}

{{--                            <!-- item-->--}}
{{--                            <a href="{{route('account')}}" class="dropdown-item notify-item">--}}
{{--                                <i class="fe-user"></i>--}}
{{--                                <span>{{translate('My Account')}}</span>--}}
{{--                            </a>--}}

{{--                            <!-- item-->--}}
{{--                            <a href="auth-lock-screen.html" class="dropdown-item notify-item">--}}
{{--                                <i class="fe-lock"></i>--}}
{{--                                <span>{{ translate('Lock Screen')}}</span>--}}
{{--                            </a>--}}

{{--                            <div class="dropdown-divider"></div>--}}

{{--                            <!-- item-->--}}
{{--                            <form action="{{route('logout')}}" method="POST">--}}
{{--                                @csrf--}}
{{--                                @method("POST")--}}
{{--                                <button class="dropdown-item notify-item" type="submit">--}}
{{--                                    <i class="fe-log-out"></i>--}}
{{--                                    <span>{{translate('Logout')}}</span>--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </li>--}}

                    <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="fe-settings noti-icon"></i>
                        </a>
                    </li>

                </ul>

                <ul class="list-unstyled topnav-menu topnav-menu-left mb-0" style="margin-top: -150px">
                    <li>
                        <button class="button-menu-mobile disable-btn waves-effect">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <h4 class="page-title-main">@yield('title')</h4>
                    </li>

                </ul>
                <div class="clearfix"></div>

        </div>
        <!-- end Topbar -->
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu" style="overflow: scroll; z-index: 1004; margin-top: -70px">

            <div class="h-100">

                <!-- User box -->
                <div class="user-box text-center">
                    @if(isset($current_user->personalInfo))
                        @php
                        if(!isset($current_user->personalInfo->avatar)){
                            $current_user->personalInfo->avatar = 'no';
                        }
                            $sms_avatar = storage_path('app/public/user/'.$current_user->personalInfo->avatar);
                        @endphp
                        @if(file_exists($sms_avatar))
                            <img class="rounded-circle img-thumbnail avatar-md" src="{{asset('storage/user/'.$current_user->personalInfo->avatar)}}" alt="">
                        @else
                            <img class="rounded-circle img-thumbnail avatar-md" src="{{asset('assets/images/man.jpg')}}" alt="">
                        @endif
                    @endif
                    <div class="dropdown">
                        <a href="#" class="user-name dropdown-toggle h5 mt-2 mb-1 d-block"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @if(isset($current_user) && isset($current_user->personalInfo))
                                {{$current_user?$current_user->personalInfo->first_name:''}} {{$current_user?$current_user->personalInfo->last_name:''}}
                            @endif
                        </a>
                        <div class="dropdown-menu user-pro-dropdown">

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user me-1"></i>
                                <span>{{ translate('My Account')}}</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings me-1"></i>
                                <span>{{translate('Settings')}}</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-lock me-1"></i>
                                <span>Lock Screen</span>
                            </a>

                            <!-- item-->
                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                @method("POST")
                                <button class="dropdown-item notify-item" type="submit">
                                    <i class="fe-log-out me-1"></i>
                                    <span>{{translate('Logout')}}</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- <p class="text-muted left-user-info">{{$current_user->role->name??''}}</p> --}}

                    <ul class="list-inline">
                        <li class="list-inline-item dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user d-flex me-0 waves-effect waves-light mt-8"
                               data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                               aria-expanded="false">
                                <i class="mdi mdi-cog"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-center profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">{{ translate('Welcome !')}}</h6>
                                </div>

                                <!-- item-->
                                <a href="{{route('account')}}" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>{{translate('My Account')}}</span>
                                </a>

                                <!-- item-->
                                <a href="auth-lock-screen.html" class="dropdown-item notify-item">
                                    <i class="fe-lock"></i>
                                    <span>{{ translate('Lock Screen')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <button class="dropdown-item notify-item" type="submit">
                                        <i class="fe-log-out"></i>
                                        <span>{{translate('Logout')}}</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                        <li class="list-inline-item">
                            <button  type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#logout-alert-modal" style="border: 0px; background-color: transparent; color: #98a6ad">
                                <i class="mdi mdi-power" style="color: red"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <ul id="side-menu">
                        <li>
                            <a href="{{route('dashboard')}}">
                            <i class="mdi mdi-home-outline"></i>
                                 <span class="badge bg-success rounded-pill float-end">9+</span>
                                <span> {{translate('Home')}} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order.index') }}">
                                <i class="mdi mdi-order-bool-ascending"></i>
                                <!-- <span class="badge bg-success rounded-pill float-end">9+</span> -->
                                {{-- <span> Order </span> --}}
                                <span> {{ translate('Order') }} </span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('offer.index') }}">
                                <i class="mdi mdi-offer"></i>
                                <span> {{ translate('Offer') }} </span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('users.index') }}">
                                <i class="mdi mdi-account-star-outline"></i>
                                <span> {{ translate('Users') }} </span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('client.index') }}">
                                <i class="mdi mdi-account-star-outline"></i>
                                <span> {{ translate('Client') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('driver.index') }}">
                                <i class="mdi mdi-taxi"></i>
                                <span> {{ translate('Driver') }} </span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{route('user.index')}}">
                                <i class="mdi mdi-account-multiple-plus-outline"></i>
                                {{-- <span class="badge bg-success rounded-pill float-end">9+</span> --}}
                                <span> {{ translate('Employees') }} </span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{route('role.index')}}">
                                <i class="mdi mdi-account-multiple-plus-outline"></i>
                                <span> Role </span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="#cars" data-bs-toggle="collapse">
                                <i class="mdi mdi-car-outline"></i>
                                <span> {{translate('Cars')}} </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="cars">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{route('cars.index')}}">{{translate('Cars')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('carList.index')}}">{{translate('Cars list')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('carTypes.index')}}">{{translate('Car Types')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('colorList.index')}}">{{translate('Cars\' Color')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('classList.index')}}">{{translate('Class list')}}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="{{route('mediaHistory.index')}}">
                                <i class="mdi mdi-video-outline"></i>
                                {{-- <span class="badge bg-success rounded-pill float-end">9+</span> --}}
                                <span> {{translate('Media history') }} </span>
                            </a>
                        </li>

{{--                        <li>--}}
{{--                            <a href="{{route('option.index')}}">--}}
{{--                                <i class="mdi mdi-file-outline"></i>--}}
{{--                                --}}{{-- <span class="badge bg-success rounded-pill float-end">9+</span> --}}
{{--                                <span> {{translate('Option') }} </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('complain.index')}}">--}}
{{--                                <i class="mdi mdi-alert-circle-outline"></i>--}}
{{--                                <span> {{translate('Complain reason') }} </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="{{route('users.complain')}}">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                <span> {{translate("Users' complain") }} </span>
                            </a>
                        </li>
                        {{-- <li class="menu-title mt-2">Apps</li>

                        <li>

                        {{-- <li>
                            <a href="apps-calendar.html">
                                <i class="mdi mdi-calendar-blank-outline"></i>
                                <span> Calendar </span>
                            </a>
                        </li> --}}

                        {{-- <li>
                            <a href="{{ route('settings.index') }}">
                                <i class="mdi mdi-calendar-blank-outline"></i>
                                <span> Settings </span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="#settings" data-bs-toggle="collapse">
                                <i class="fe-settings noti-icon"></i>

                                <span> {{translate('Settings')}} </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="settings">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{route('language.index')}}"> {{translate('Language')}}  </a>
                                    </li>
                                    <li>
                                        <a href="{{route('table.index')}}"> {{translate('Table translations')}}  </a>
                                    </li>
                                    <li>
                                        <a href="{{route('coupon.index')}}">{{translate('Coupon')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('role.index')}}">{{translate('Role')}}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        {{-- <li class="menu-title mt-2">Apps</li>


                        <li>
                            <a href="apps-chat.html">
                                <i class="mdi mdi-forum-outline"></i>
                                <span> Chat </span>
                            </a>
                        </li>

                        <li>
                            <a href="#email" data-bs-toggle="collapse">
                                <i class="mdi mdi-email-outline"></i>
                                <span> Email </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="email">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="email-inbox.html">Inbox</a>
                                    </li>
                                    <li>
                                        <a href="email-templates.html">Email Templates</a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <br>
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    {{-- <div class="card">
                        <div class="card-body"> --}}

                            @yield('content')

                        {{-- </div>
                    </div> --}}
                </div>
                <!-- container-fluid -->
            </div>

        </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            {{-- &copy; Adminto theme by <a href="">Coderthemes</a> --}}
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-sm-block">
                                <a href="javascript:void(0);">About Us</a>
                                <a href="javascript:void(0);">Help</a>
                                <a href="javascript:void(0);">Contact Us</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
        <div id="logout-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-warning"></i>
                            <h4 class="mt-2">{{translate('Logout')}}</h4>
                            <p class="mt-3">{{translate('Confirm to logout')}}</p>
                            <div class="d-flex justify-content-around">
                                <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal">{{translate('No')}}</button>
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <button type="submit" class="btn btn-warning my-2" data-bs-dismiss="modal">
                                        {{translate('Yes')}}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-end">
                    <i class="mdi mdi-close"></i>
                </a>
                <h4 class="font-16 m-0 text-white">{{ translate('Theme Customizer')}}</h4>
            </div>
            <!-- Tab panes -->
            <div class="tab-content pt-0">

                <div class="tab-pane active" id="settings-tab" role="tabpanel">

                    <div class="p-3">
{{--                        <div class="alert alert-warning" role="alert">--}}
{{--                            <strong>{{ translate('Customize')}}</strong>{{ translate('the overall color scheme, Layout, etc.')}}--}}
{{--                        </div>--}}
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Color Scheme')}}</h6>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-color" value="light"
                                id="light-mode-check" />
                            <label class="form-check-label" for="light-mode-check">{{ translate('Light Mode')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-color" value="dark"
                                id="dark-mode-check" checked/>
                            <label class="form-check-label" for="dark-mode-check">{{ translate('Dark Mode')}}</label>
                        </div>

                        <!-- Width -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Width')}}</h6>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-size" value="fluid"
                                id="fluid" checked />
                            <label class="form-check-label" for="fluid-check">{{ translate('Fluid')}}</label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-size" value="boxed"
                                id="boxed" />
                            <label class="form-check-label" for="boxed-check">{{ translate('Boxed')}}</label>
                        </div>

                        <!-- Menu positions -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Menus (Leftsidebar and Topbar) Positon')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-position" value="fixed"
                                id="fixed-check" checked />
                            <label class="form-check-label" for="fixed-check">{{ translate('Fixed')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-position"
                                value="scrollable" id="scrollable-check" />
                            <label class="form-check-label" for="scrollable-check">{{ translate('Scrollable')}}</label>
                        </div>

                        <!-- Left Sidebar-->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Left Sidebar Color')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="light"
                                id="light" />
                            <label class="form-check-label" for="light-check">{{ translate('Light')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="dark"
                                id="dark" checked />
                            <label class="form-check-label" for="dark-check">{{ translate('Dark')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="brand"
                                id="brand" />
                            <label class="form-check-label" for="brand-check">{{ translate('Brand')}}</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="gradient"
                                id="gradient" />
                            <label class="form-check-label" for="gradient-check">{{ translate('Gradient')}}</label>
                        </div>

                        <!-- size -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Left Sidebar Size')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="default"
                                id="default-size-check" checked />
                            <label class="form-check-label" for="default-size-check">{{ translate('Default')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="condensed"
                                id="condensed-check" />
                            <label class="form-check-label" for="condensed-check">{{ translate('Condensed')}} <small>{{ translate('(Extra Small size)')}}</small></label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="compact"
                                id="compact-check" />
                            <label class="form-check-label" for="compact-check">{{ translate('Compact')}} <small>{{ translate('(Small size)')}}</small></label>
                        </div>

                        <!-- User info -->
{{--                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Sidebar User Info')}}</h6>--}}

{{--                        <div class="form-check form-switch mb-1">--}}
{{--                            <input type="checkbox" class="form-check-input" name="sidebar-user" value="true"--}}
{{--                                id="sidebaruser-check" />--}}
{{--                            <label class="form-check-label" for="sidebaruser-check">{{ translate('Enable')}}</label>--}}
{{--                        </div>--}}


                        <!-- Topbar -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ translate('Topbar')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="topbar-color" value="dark"
                                id="darktopbar-check" checked />
                            <label class="form-check-label" for="darktopbar-check">{{ translate('Dark')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="topbar-color" value="light"
                                id="lighttopbar-check" />
                            <label class="form-check-label" for="lighttopbar-check">{{ translate('Light')}}</label>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-primary" id="resetBtn">{{ translate('Reset to Default')}}</button>
                            {{-- <a href="https://1.envato.market/admintoadmin" class="btn btn-danger mt-3" target="_blank"><i class="mdi mdi-basket me-1"></i> Purchase Now</a> --}}
                        </div>

                    </div>

                </div>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->


    <!-- Warning Alert Modal FOR DELETE -->
    <div id="warning-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="dripicons-warning h1 text-warning"></i>
                        <h4 class="mt-2">{{ translate('Are you sure delete this data')}}</h4>
                        {{-- <h4 class="mt-2">{{ translate('Are you sure delete this data') }}</h4> --}}
                        {{-- <p class="mt-3">Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p> --}}
                        <form style="display: inline-block;" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
                            {{-- <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal">{{ translate('No') }}</button> --}}
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                            {{-- <button type="submit" class="btn btn-warning my-2">{{ translate('Yes') }}</button> --}}
                        </form>
                        {{-- <button type="button" class="btn btn-warning my-2" data-bs-dismiss="modal">Continue</button> --}}
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Standard modal content -->
    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ translate('Save changes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Standard modal content -->
    <div id="standard-modal-admin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ translate('Save changes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>

    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>

    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>

    <script src="{{asset('assets/libs/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('assets/libs/dropify/js/dropify.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
    <!-- knob plugin -->
    <script src="{{ asset('assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('assets/libs/morris.js06/morris.min.js') }}"></script>
    <script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Dashboar init js-->
{{--    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>--}}
    <script>

//light mode or dark mode
        let light_mode = document.getElementById('light-mode-check')
        let dark_mode = document.getElementById('dark-mode-check')
        let body_layout = document.getElementById('body_layout')
        light_mode.addEventListener('click', function (){
            localStorage.setItem('layout_local', 'light')
        })
        dark_mode.addEventListener('click', function (){
            localStorage.setItem('layout_local', 'dark')
        })
        if(localStorage.getItem('layout_local') == undefined || localStorage.getItem('layout_local') == null){
            body_layout.setAttribute('data-layout-color', 'default')
        }else{
            body_layout.setAttribute('data-layout-color', localStorage.getItem('layout_local'))
        }

//fluid or boxed
        let fluid = document.getElementById('fluid')
        let boxed = document.getElementById('boxed')
        fluid.addEventListener('click', function (){
            localStorage.setItem('fluid_or_boxed', 'fluid')
        })
        boxed.addEventListener('click', function (){
            localStorage.setItem('fluid_or_boxed', 'boxed')
        })
        if(localStorage.getItem('fluid_or_boxed') == undefined || localStorage.getItem('fluid_or_boxed') == null){
            body_layout.setAttribute('data-layout-size', 'fluid')
        }else{
            body_layout.setAttribute('data-layout-size', localStorage.getItem('fluid_or_boxed'))
        }

//fixed or scrollable
        let fixed_check = document.getElementById('fixed-check')
        let scrollable_check = document.getElementById('scrollable-check')
        fixed_check.addEventListener('click', function (){
            localStorage.setItem('fixed_or_scrollable', 'fixed')
        })
        scrollable_check.addEventListener('click', function (){
            localStorage.setItem('fixed_or_scrollable', 'scrollable')
        })
        if(localStorage.getItem('fixed_or_scrollable') == undefined || localStorage.getItem('fixed_or_scrollable') == null){
            body_layout.setAttribute('data-leftbar-positione', 'fixed')
        }else{
            body_layout.setAttribute('data-leftbar-position', localStorage.getItem('fixed_or_scrollable'))
        }

//fixed or scrollable
        let light = document.getElementById('light')
        let dark = document.getElementById('dark')
        let brand = document.getElementById('brand')
        let gradient = document.getElementById('gradient')
        light.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'light')
        })
        dark.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'dark')
        })
        brand.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'brand')
        })
        gradient.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'gradient')
        })
        if(localStorage.getItem('leftbar_color') == undefined || localStorage.getItem('leftbar_color') == null){
            body_layout.setAttribute('data-leftbar-color', 'light')
        }else{
            body_layout.setAttribute('data-leftbar-color', localStorage.getItem('leftbar_color'))
        }

//fixed or scrollable
        let default_size_check = document.getElementById('default-size-check')
        let condensed_check = document.getElementById('condensed-check')
        let compact_check = document.getElementById('compact-check')
        default_size_check.addEventListener('click', function (){
            localStorage.setItem('leftbar_size', 'default')
        })
        condensed_check.addEventListener('click', function (){
            localStorage.setItem('leftbar_size', 'condensed')
        })
        compact_check.addEventListener('click', function (){
            localStorage.setItem('leftbar_size', 'compact')
        })
        if(localStorage.getItem('leftbar_size') == undefined || localStorage.getItem('leftbar_size') == null){
            body_layout.setAttribute('data-leftbar-size', 'default')
        }else{
            body_layout.setAttribute('data-leftbar-size', localStorage.getItem('leftbar_size'))
        }

//Sidebar User Info
//         let sidebaruser_check = document.getElementById('sidebaruser-check')
//         sidebaruser_check.addEventListener('click', function (){
//             if(sidebaruser_check.checked == true){
//                 localStorage.setItem('sidebar_user', true)
//             }else{
//                 localStorage.removeItem('sidebar_user')
//             }
//         })
//         if(localStorage.getItem('sidebar_user') == undefined || localStorage.getItem('sidebar_user') == null){
//             if(body_layout.hasAttribute('data-sidebar-user')){
//                 body_layout.removeAttribute('data-sidebar-user')
//             }
//         }else{
//             body_layout.setAttribute('data-sidebar-user', true)
//         }

//Topbar color
        let darktopbar_check = document.getElementById('darktopbar-check')
        let lighttopbar_check = document.getElementById('lighttopbar-check')
        darktopbar_check.addEventListener('click', function (){
            localStorage.setItem('topbar_color', 'dark')
        })
        lighttopbar_check.addEventListener('click', function (){
            localStorage.setItem('topbar_color', 'light')
        })
        if(localStorage.getItem('topbar_color') == undefined || localStorage.getItem('topbar_color') == null){
            body_layout.setAttribute('data-topbar-color', 'light')
        }else{
            body_layout.setAttribute('data-topbar-color', localStorage.getItem('topbar_color'))
        }

// Reset to default
        let resetBtn = document.getElementById('resetBtn')
        resetBtn.addEventListener('click', function (){
            if(localStorage.getItem('topbar_color') != undefined || localStorage.getItem('topbar_color') != null){
                localStorage.removeItem('topbar_color')
            }
            if(localStorage.getItem('leftbar_size') != undefined || localStorage.getItem('leftbar_size') != null){
                localStorage.removeItem('leftbar_size')
            }
            if(localStorage.getItem('leftbar_color') != undefined || localStorage.getItem('leftbar_color') != null){
                localStorage.removeItem('leftbar_color')
            }
            if(localStorage.getItem('fixed_or_scrollable') != undefined || localStorage.getItem('fixed_or_scrollable') != null){
                localStorage.removeItem('fixed_or_scrollable')
            }
            if(localStorage.getItem('fluid_or_boxed') != undefined || localStorage.getItem('fluid_or_boxed') != null){
                localStorage.removeItem('fluid_or_boxed')
            }
            if(localStorage.getItem('layout_local') != undefined || localStorage.getItem('layout_local') != null){
                localStorage.removeItem('layout_local')
            }
            location.reload();
        })

    </script>
    <script>
        "use strict";
        let offers_on_hold = {name:"{{translate('Offers on hold')}}", count:"{{$offers_active_count??0}}"}
        let orders_active = {name:"{{translate('Orders active')}}", count:"{{$order_active??0}}"}
        let completed_orders = {name:"{{translate('Completed orders')}}", count:"{{$order_completed??0}}"}
        let order_cancelled = {name:"{{translate('Cancelled orders')}}", count:"{{$order_cancelled??0}}"}
        let monthly_orders_count = {!! $monthly_orders_count??0 !!}
        let monthly_offers_count = {!! $monthly_offers_count??0 !!}
        let order_created = "{{translate('Order created')}}"
        let offer_created = "{{translate('Offer created')}}"
        const month_names = ["","January","February","March","April","May","June","July",
            "August","September","October","November","December"];
        !function(e){
            function a(){
                this.$realData=[]
            }
            a.prototype.createBarChart=function(e,a,r,t,o,i){
                Morris.Bar({
                    element:e,data:a,xkey:r,ykeys:t,labels:o,hideHover:"auto",resize:!0,gridLineColor:"rgba(173, 181, 189, 0.1)",barSizeRatio:.2,dataLabels:!1,barColors:i
                })
            },
                a.prototype.createLineChart=function(e,a,r,xs,t,o,i,n,s,l){
                    Morris.Line(
                        {
                            element:e,
                            data:a,
                            xkey:r,
                            parseTime: false,
                            ykeys:t,
                            xLabelFormat: xs,
                            xLabels: "month",
                            labels:o,
                            fillOpacity:i,
                            pointFillColors:n,
                            pointStrokeColors:s,
                            behaveLikeLine:!0,
                            gridLineColor:"rgba(173, 181, 189, 0.1)",
                            hideHover:"auto",
                            resize:!0,
                            pointSize:0,
                            dataLabels:!1,
                            lineColors:l})
                },
                a.prototype.createDonutChart=function(e,a,r)
                {
                    Morris.Donut({element:e,data:a,resize:!0,colors:r,backgroundColor:"transparent"})
                },
                a.prototype.init=function(){
                e("#morris-bar-example").empty(),
                        e("#morris-line-example").empty(),
                        e("#morris-donut-example").empty();
                    // this.createBarChart(
                    //     "morris-bar-example",
                    //     [
                    //         {y:"2010",a:75},
                    //         {y:"2011",a:42},
                    //         {y:"2012",a:75},
                    //         {y:"2013",a:38},
                    //         {y:"2014",a:19},
                    //         {y:"2015",a:93}
                    //     ],
                    //     "y",
                    //     ["a"],
                    //     ["Statistics"],
                    //     ["#188ae2"]
                    // );
                    this.createLineChart(
                        "morris-line-example",
                        [
                            {y:monthly_orders_count.seven_month.month,a:parseInt(monthly_orders_count.seven_month.counts),b:parseInt(monthly_offers_count.seven_month.counts)},
                            {y:monthly_orders_count.six_month.month,a:parseInt(monthly_orders_count.six_month.counts),b:parseInt(monthly_offers_count.six_month.counts)},
                            {y:monthly_orders_count.five_month.month,a:parseInt(monthly_orders_count.five_month.counts),b:parseInt(monthly_offers_count.five_month.counts)},
                            {y:monthly_orders_count.four_month.month,a:parseInt(monthly_orders_count.four_month.counts),b:parseInt(monthly_offers_count.four_month.counts)},
                            {y:monthly_orders_count.three_month.month,a:parseInt(monthly_orders_count.three_month.counts),b:parseInt(monthly_offers_count.three_month.counts)},
                            {y:monthly_orders_count.two_month.month,a:parseInt(monthly_orders_count.two_month.counts),b:parseInt(monthly_offers_count.two_month.counts)},
                            {y:monthly_orders_count.one_month.month,a:parseInt(monthly_orders_count.one_month.counts),b:parseInt(monthly_offers_count.one_month.counts)},
                            {y:monthly_orders_count.this_month.month,a:parseInt(monthly_orders_count.this_month.counts),b:parseInt(monthly_offers_count.this_month.counts)}],
                            "y",
                        function (x) {
                            var index = parseInt(x.src.y);
                            return month_names[index];
                        },
                        ["a","b"],
                        [order_created, offer_created],
                        ["0.9"],
                        ["#ffffff"],
                        ["#999999"],
                        ["#10c469","#188ae2"]
                    );
                    this.createDonutChart(
                        "morris-donut-example",
                        [
                            {label:offers_on_hold.name, value:offers_on_hold.count},
                            {label:orders_active.name, value:orders_active.count},
                            {label:completed_orders.name, value:completed_orders.count},
                            {label:order_cancelled.name, value:order_cancelled.count}
                        ],
                        ["#FF6C37","#10C469","#00ADD7","#FF0000"]
                    )
                },
                e.Dashboard1=new a,
                e.Dashboard1.Constructor=a
        }(window.jQuery),function(a){a.Dashboard1.init(),window.addEventListener("adminto.setBoxed",function(e){a.Dashboard1.init()}),window.addEventListener("adminto.setFluid",function(e){a.Dashboard1.init()})}(window.jQuery);

    </script>

    <!-- Datatables init -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script>
        $(document).on('click', '.delete-datas', function(e) {
            var url = $(this).attr('data-url')
            $('#warning-alert-modal').find('form').attr('action', url)
        })

        $(document).ready(function() {
            $('.basic-datepicker').flatpickr();

            // $('#passport_expired_date').datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true
            // });
        })

        $(document).ready(function() {
            let sessionSuccess ="{{session('status')}}";
            if(sessionSuccess){
                toastr.success(sessionSuccess)
            }
            let language = '{{ $locale }}'
            let uz = `{{ asset('/assets/images/language/region.png') }}`
            let ru = `{{ asset('/assets/images/language/RU.png') }}`
            let en = `{{ asset('/assets/images/language/GB.png') }}`

            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdownMenyApplyUzbFlag a').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        switch (locale) {
                            case 'uz':
                                $('#selected_language').attr('src', uz)
                                break;
                            case 'en':
                                $('#selected_language').attr('src', en)
                                break;
                            case 'ru':
                                $('#selected_language').attr('src', ru)
                                break;
                        }
                        $.post('{{ route('language.change') }}', {
                            _token: '{{ csrf_token() }}',
                            locale: locale
                        }, function(data) {
                            location.reload();
                        });

                    });
                });
            }
        })

        $(document).on('click', '.delete-order', function(e) {
            var url = $(this).attr('data-url')
            $('#warning-alert-modal').find('form').attr('action', url)
        })

        $(document).on('click', '.confirm-driver', function(e) {
            $('#standard-modal .modal-body').html(' ')
            $('#standard-modal .modal-footer').remove()
            @if(isset($personalInfo))
                @if(!is_array($personalInfo->passport_images))
                    @php
                        $passport_images = json_decode($personalInfo->passport_images);
                    @endphp
                @endif
            @endif
            var id = $(this).attr('data-id')

            $('#standard-modal .modal-body').html(`
                <form action="{{ route('users.confirm-driver') }}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" class="form-control" name="user_id" value="` + id + `"/>
                    <h4>{{ translate('Passport information') }}</h4>
                    <div class="row">
                        @if(isset($passport_images))
                            @foreach($passport_images as $passport_image)
                                @php
                                  $passport_img_exist = storage_path('app/public/passport-images/'.$passport_image);
                                @endphp
                                <div class="mb-3 col-md-6">
                                    @if(isset($passport_image) && file_exists($passport_img_exist))
                                        <img src="{{'http://api.easygo.uz/storage/passport-images/'.$car_->reg_certificate_image}}" alt="" height="94">
                                    @else
                                        <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Serial number') }}</label>
                            <input type="text" class="form-control" name="passport_serial_number" value="{{$personalInfo->passport_serial_number??''}}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Images') }}</label>
                            <input type="file" class="form-control" name="passport_images[]" multiple/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Issued by') }}</label>
                            <input type="date" class="form-control" name="passport_issued_by" value="{{$personalInfo->passport_issued_by??''}}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Expired date') }}</label>
                            <input type="date" class="form-control" name="passport_expired_date" value="{{$personalInfo->passport_expired_date??''}}"/>
                        </div>
                    </div>
                    @if(isset($cars))
                        @foreach($cars as $car)
                        <h4>{{ translate("Technical certificate") }}</h4>
                        <div class="row">
                            @php
                                $reg_certificate_image = storage_path('app/public/cars/'.$car->reg_certificate_image??'no');
                                $reg_certificate_image_back = storage_path('app/public/cars/'.$car->reg_certificate_image_back??'no');
                            @endphp
                            <div class="mb-3 col-md-6 text-center">
                                @if(isset($car) && isset($car->reg_certificate_image))
                                    @if(file_exists($reg_certificate_image))
                                        <img src="{{asset('storage/cars/'.$car->reg_certificate_image)}}" alt="" height="94">
                                    @else
                                        @if(get_headers('http://api.easygo.uz/storage/cars/'.$car->reg_certificate_image)[0] == "HTTP/1.1 200 OK")
                                            <img src="{{'http://api.easygo.uz/storage/cars/'.$car->reg_certificate_image}}" alt="" height="94">
                                        @else
                                            <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                        @endif
                                    @endif
                                @else
                                    <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                @endif
                            </div>
                            <div class="mb-3 col-md-6 text-center">
                                @if(isset($car) && isset($car->reg_certificate_image_back))
                                    @if(file_exists($reg_certificate_image_back))
                                        <img src="{{asset('storage/cars/'.$car->reg_certificate_image_back)}}" alt="" height="94">
                                    @else
                                        @if(get_headers('http://api.easygo.uz/storage/cars/'.$car->reg_certificate_image_back)[0] == "HTTP/1.1 200 OK")
                                            <img src="{{'http://api.easygo.uz/storage/cars/'.$car->reg_certificate_image_back}}" alt="" height="94">
                                        @else
                                            <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                        @endif
                                    @endif
                                @else
                                    <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ translate('Image') }}</label>
                                    <input type="file" class="form-control" name="reg_certificate_image"/>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">{{ translate('The back of the image') }}</label>
                                    <input type="file" class="form-control" name="reg_certificate_image_back"/>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">{{ translate('Certificate number') }}</label>
                                <input type="text" class="form-control" value="{{$car->reg_certificate??''}}" name="reg_certificate"/>
                            </div>
                        @endforeach
                    @endif
                    @if(isset($driver))
                        <h4>{{ translate("Driver's license information") }}</h4>
                        <div class="row">
                        @php
                            $license_image_ = storage_path('app/public/certificate/'.$driver->license_image??'no');
                            $license_image_back_ = storage_path('app/public/certificate/'.$driver->license_image_back??'no');
                        @endphp
                            <div class="mb-3 col-md-6 text-center">
                                @if(isset($driver) && isset($driver->license_image))
                                    @if(file_exists($license_image_))
                                        <img src="{{asset('storage/certificate/'.$driver->license_image)}}" alt="" height="94">
                                    @else
                                        @if(get_headers('http://api.easygo.uz/storage/certificate/'.$driver->license_image)[0] == "HTTP/1.1 200 OK")
                                            <img src="{{'http://api.easygo.uz/storage/certificate/'.$driver->license_image}}" alt="" height="94">
                                        @else
                                            <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                        @endif
                                    @endif
                                @else
                                    <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                @endif
                            </div>
                            <div class="mb-3 col-md-6 text-center">
                                @if(isset($driver) && isset($driver->license_image_back))
                                    @if(file_exists($license_image_back_))
                                        <img src="{{asset('storage/certificate/'.$driver->license_image_back)}}" alt="" height="94">
                                    @else
                                        @if(get_headers('http://api.easygo.uz/storage/certificate/'.$driver->license_image_back)[0] == "HTTP/1.1 200 OK")
                                            <img src="{{'http://api.easygo.uz/storage/certificate/'.$driver->license_image_back}}" alt="" height="94">
                                        @else
                                            <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                        @endif
                                    @endif
                                @else
                                <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                             @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ translate('License number') }}</label>
                                <input type="text" class="form-control" name="license_number" value="{{$driver->license_number}}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">{{ translate('License expired date') }}</label>
                                <input type="date" class="form-control" name="license_expired_date" value="{{explode(" ",$driver->license_expired_date)[0]}}"/>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ translate('Document status') }}</label>
                            <select name="doc_status" id="doc_status" class="form-control">
                              <option {{$driver->doc_status == 1?'selected':''}} value="1" >Not accepted</option>
                              <option {{$driver->doc_status == 2?'selected':''}} value="2">Accept</option>
                              <option {{$driver->doc_status == 3?'selected':''}} value="3">Expectations</option>
                              <option {{$driver->doc_status == 4?'selected':''}} value="4">Canceled</option>
                            </select>
                        </div>
                    @endif
                    <div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" style="float: right;">{{translate('Save')}}</button>
                    </div>
                </form>
            `)
        })
        $(document).on('click', '.confirm-driver-admin', function(e) {
            @if(isset($personalInfo))
                @if(!is_array($personalInfo->passport_images))
                    @php
                        $passport_images = json_decode($personalInfo->passport_images);
                    @endphp
                @endif
            @endif
            var id = $(this).attr('data-id')
            $('#standard-modal-admin .modal-body').html(' ')
            $('#standard-modal-admin .modal-footer').remove()
            $('#standard-modal-admin .modal-body').html(`
                <form action="{{ route('users.confirm-driver-by-admin') }}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" class="form-control" name="user_id" value="` + id + `"/>
                    <input type="hidden" class="form-control" name="is_admin" value="1"/>
                    <h4>{{ translate('Passport information') }}</h4>
                    <div class="row">
                        @if(isset($passport_images))
                            @foreach($passport_images as $passport_image)
                                @php
                                  $passport_img_exist = storage_path('app/public/passport-images/'.$passport_image);
                                @endphp
                                <div class="mb-3 col-md-6">
                                    @if(isset($passport_image) && file_exists($passport_img_exist))
                                        <img src="{{'http://api.easygo.uz/storage/passport-images/'.$car_->reg_certificate_image}}" alt="" height="94">
                                    @else
                                        <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Serial number') }}</label>
                            <input type="text" class="form-control" name="passport_serial_number" value="{{$personalInfo->passport_serial_number??''}}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Images') }}</label>
                            <input type="file" class="form-control" name="passport_images[]" multiple/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Issued by') }}</label>
                            <input type="date" class="form-control" name="passport_issued_by" value="{{$personalInfo->passport_issued_by??''}}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Expired date') }}</label>
                            <input type="date" class="form-control" name="passport_expired_date" value="{{$personalInfo->passport_expired_date??''}}"/>
                        </div>
                    </div>
                @if(isset($cars))
                    @foreach($cars as $car_)
                    <h4>{{ translate("Technical certificate") }}</h4>
                    <div class="row">
                        @php
                            $reg_certificate_image_ = storage_path('app/public/cars/'.$car_->reg_certificate_image??'no');
                            $reg_certificate_image_back_ = storage_path('app/public/cars/'.$car_->reg_certificate_image_back??'no');
                        @endphp
                        <div class="mb-3 col-md-6 text-center">
                            @if(isset($car_) && isset($car_->reg_certificate_image))
                                @if(file_exists($reg_certificate_image_))
                                    <img src="{{asset('storage/cars/'.$car_->reg_certificate_image)}}" alt="" height="94">
                                @else
                                    @if(get_headers('http://api.easygo.uz/storage/cars/'.$car_->reg_certificate_image)[0] == "HTTP/1.1 200 OK")
                                        <img src="{{'http://api.easygo.uz/storage/cars/'.$car_->reg_certificate_image}}" alt="" height="94">
                                    @else
                                        <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                    @endif
                                @endif
                            @else
                                <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                            @endif
                        </div>
                        <div class="mb-3 col-md-6 text-center">
                            @if(isset($car_) && isset($car_->reg_certificate_image_back))
                                @if(file_exists($reg_certificate_image_back_))
                                    <img src="{{asset('storage/cars/'.$car_->reg_certificate_image_back)}}" alt="" height="94">
                                @else
                                    @if(get_headers('http://api.easygo.uz/storage/cars/'.$car_->reg_certificate_image_back)[0] == "HTTP/1.1 200 OK")
                                        <img src="{{'http://api.easygo.uz/storage/cars/'.$car_->reg_certificate_image_back}}" alt="" height="94">
                                    @else
                                        <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                    @endif
                                @endif
                            @else
                                <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('Image') }}</label>
                            <input type="file" class="form-control" name="reg_certificate_image[]"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('The back of the image') }}</label>
                            <input type="file" class="form-control" name="reg_certificate_image_back[]"/>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ translate('Certificate number') }}</label>
                        <input type="text" class="form-control" value="{{$car_->reg_certificate??''}}" name="reg_certificate[]"/>
                    </div>
                    @endforeach
                @endif
                <h4>{{ translate("Driver's license information") }}</h4>
                @if(isset($driver))
                    <div class="row">
                        @php
                            $license_image_ = storage_path('app/public/certificate/'.$driver->license_image??'no');
                            $license_image_back_ = storage_path('app/public/certificate/'.$driver->license_image_back??'no');
                        @endphp
                        <div class="mb-3 col-md-6 text-center">
                            @if(isset($driver) && isset($driver->license_image))
                                @if(file_exists($license_image_))
                                    <img src="{{asset('storage/certificate/'.$driver->license_image)}}" alt="" height="94">
                                @else
                                    @if(get_headers('http://api.easygo.uz/storage/certificate/'.$driver->license_image)[0] == "HTTP/1.1 200 OK")
                                        <img src="{{'http://api.easygo.uz/storage/certificate/'.$driver->license_image}}" alt="" height="94">
                                    @else
                                        <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                    @endif
                                @endif
                            @else
                                <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                            @endif
                        </div>
                        <div class="mb-3 col-md-6 text-center">
                            @if(isset($driver) && isset($driver->license_image_back))
                                @if(file_exists($license_image_back_))
                                    <img src="{{asset('storage/certificate/'.$driver->license_image_back)}}" alt="" height="94">
                                @else
                                    @if(get_headers('http://api.easygo.uz/storage/certificate/'.$driver->license_image_back)[0] == "HTTP/1.1 200 OK")
                                        <img src="{{'http://api.easygo.uz/storage/certificate/'.$driver->license_image_back}}" alt="" height="94">
                                    @else
                                        <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                                    @endif
                                @endif
                            @else
                                <img src="http://admin.easygo.uz/icon/no_photo.jpg" alt="" height="94">
                            @endif
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ translate('Image') }}</label>
                        <input type="file" class="form-control" name="license_image" multiple/>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ translate('The back of the image') }}</label>
                        <input type="file" class="form-control" name="license_image_back" multiple/>
                    </div>
                </div>
                @if(isset($driver))
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('License number') }}</label>
                            <input type="text" class="form-control" name="license_number" value="{{$driver->license_number??''}}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('License expired date') }}</label>
                            <input type="date" class="form-control" name="license_expired_date" value="{{explode(" ",$driver->license_expired_date)[0]??''}}"/>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('License number') }}</label>
                            <input type="text" class="form-control" name="license_number" value=""/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ translate('License expired date') }}</label>
                            <input type="date" class="form-control" name="license_expired_date" value=""/>
                        </div>
                    </div>
                @endif
                @if(isset($driver))
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ translate('Document status') }}</label>
                        <select name="doc_status" id="doc_status" class="form-control">
                          <option {{$driver->doc_status == 1?'selected':''}} value="1">Not accepted</option>
                          <option {{$driver->doc_status == 2?'selected':''}} value="2">Accept</option>
                          <option {{$driver->doc_status == 3?'selected':''}} value="3">Expectations</option>
                          <option {{$driver->doc_status == 4?'selected':''}} value="4">Canceled</option>
                        </select>
                    </div>
                @else
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ translate('Document status') }}</label>
                        <select name="doc_status" id="doc_status" class="form-control">
                          <option value="1">Not accepted</option>
                          <option value="2">Accept</option>
                          <option value="3">Expectations</option>
                          <option value="4">Canceled</option>
                        </select>
                    </div>
                @endif
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" style="float: right;">{{translate('Save')}}</button>
                </div>
            </form>
            `)
        });
        let dropdownMenuButton = document.getElementById('dropdownMenuButton')
        let language_flag = document.getElementById('language_flag')
        // let wrapper = document.getElementById('wrapper')
        // wrapper.addEventListener('click', function() {
        //     if (language_flag.classList.contains('display-none')) {
        //         language_flag.classList.add('display-none')
        //     }
        // });
        dropdownMenuButton.addEventListener('click', function() {
            if (language_flag.classList.contains('display-none')) {
                language_flag.classList.remove('display-none')
            } else {
                language_flag.classList.add('display-none')
            }
        });
    </script>
</body>

</html>
