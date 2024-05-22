@extends('layout.app')

@section('content')
    <style>
        .white_color{
            color: white;
        }
        .box_shadow{
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25), 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }
        .login_page{
            height:100vh;
        }
        .correct_img img {
            height: 38px;
            width: 47%;
        }
        .login_page_image{
            background-image: url("{{asset('/assets/images/login_background.png')}}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100%;
            width: 100%;
        }
        .login_page input{
            border-radius: 8px !important;
            background-color: #F4F4F6;
            border: 0px;
        }
        .login_page input:focus{
            background-color: #F4F4F6;
        }
        .login_button{
            background-color: #3C7CFB;
            padding: 10px 0px;
            width: 50%;
            text-align: center;
            border-radius: 12px;
            border: 0px;
            color: white;
        }

        @media screen and (max-width: 1000px) {
            .login_modal_background {
                width: 480px;
            }
            .login_modal{
                width: 420px;
            }
        }
        @media screen and (min-width: 1001px) {

            .login_modal_background {
                width: 690px;
            }
            .login_modal{
                width: 503px;
            }
        }
        .login_modal_background{
            border-radius: 20px;
            display: flex;
            justify-content: center;
            backdrop-filter: blur(7px);
            padding: 34px 0px;
            border: solid 3px rgb(0, 0, 0, 0.04);
        }
        .login_modal{
            color: white;
        }
        .color_white{
            color: white !important;
        }
        .sign_in_button{
            background-color: #003465;
            border: 0px;
            border-radius: 2px;
            width: 100%;
            color: white;
            height: 38px;
        }
        .form-check-input:checked{
            background-color: #71B6F9 !important;
        }
    </style>
    <div class="login_page">
        <div class="login_page_image d-flex flex-column justify-content-center align-items-center">
            @if (session('status'))
                <div class="alert alert-danger">
                    {{session('status')}}
                </div>
            @endif
                <div class="login_modal_background">
                    <div class="login_modal">
                        <img src="{{asset('assets/images/easyprint.svg')}}" alt="">
                        <div class="text-start m-3">
                            <h3 class="mt-0 color_white">Войти</h3>
                        </div>

                        <form action="{{route('login')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Адрес электронной почты</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" id="emailaddress" name="email" required="" placeholder="Введите адрес электронной почты">
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" required="" name="password" id="password" placeholder="Введите ваш пароль">
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                            <div class="mb-3">
                                <div class="form-check d-flex correct_img justify-content-between" style="padding-left:0px;">
                                    {{-- captcha_img('flat'); --}}
                                    {!! captcha_img('flat') !!}
                                    {{-- {!!  !!} --}}
                                    <input style="width:47%; margin-left:20px;" class="form-control" type="text" name="captcha" id="captcha" required>

                                </div>
                            </div>
                            @error('captcha')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                            <div class="mb-3 d-flex justify-content-between">
                                <a href="{{ route('password.request') }}" class="text-muted ms-1 color_white"><i class="fa fa-lock me-1"></i>Забыли пароль?</a>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                    <label class="form-check-label" for="checkbox-signin">Запомнить меня</label>
                                </div>
                            </div>
                            <button class="sign_in_button">Sign in</button>
                        </form>
                    </div>


                </div> <!-- end card-body -->
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
