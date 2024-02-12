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
            background-color: #E6F3FFBF;
            /*height:100vh;*/
        }
        .correct_img img {
            height: 54px;
            width: 47%;
        }
        .login_page_image{
            background-image: url("{{asset('/assets/images/logineasyprint.png')}}");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 94vh;
            width: 100%;
        }
        .login_page input{
            border-radius: 8px !important;
            line-height: 2.4;
            background-color: #F4F4F6;
            border: 0px;
        }
        .login_page input:focus{
            background-color: #F4F4F6;
        }
        .login_button{
            background-color: #3C7CFB;
            padding: 10px 70px 10px 70px;
            width: 50%;
            border-radius: 12px;
            border: 0px;
            color: white;
        }
    </style>
    <div class="container-fluid login_page">
        <div class="row">
            <div class="col-6" style="height: 100vh;">
                <div class="login_page_image">

                </div>
            </div>
            <div class="col-6 d-flex flex-column justify-content-center align-items-center" style="border-radius: 24px 0px 0px 24px; background-color: white; height: 100vh">

                @if (session('status'))
                    <div class="alert alert-danger">
                        {{session('status')}}
                    </div>
                @endif
{{--                <div class="card" style="border-radius: 20px; margin-top: 100px">--}}
                    <div class="p-4" style="width: 94%">
                        <div class="text-center mb-4">
                            <h4 class="text-uppercase mt-0">Войти</h4>
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
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                    <label class="form-check-label" for="checkbox-signin">Запомнить меня</label>
                                </div>
                            </div>

                            <div class="mb-3 d-grid d-flex justify-content-center">
                                <button class="login_button" type="submit"> Авторизоваться </button>
                            </div>
                        </form>

                    </div> <!-- end card-body -->
{{--                </div>--}}
                <!-- end card -->

                <div class="row">
                    <div class="col-12 text-center">
                        <p> <a href="{{ route('password.request') }}" class="text-muted ms-1 color_black"><i class="fa fa-lock me-1 color_black"></i>Забыли пароль?</a></p>
                        {{--                            <p class="text-muted color_black">Don't have an account? <a href="" class="text-dark ms-1"><b>Sign Up</b></a></p>--}}
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection
