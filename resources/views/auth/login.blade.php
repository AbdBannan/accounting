@extends('layouts.app')

@section('content')
    @if (isset($_GET["lang"]))
        @php
            app()->setLocale($_GET["lang"]);
        @endphp
    @else
        @php
            $_GET["lang"] = app()->getLocale();
        @endphp
    @endif

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">{{__("global.welcome_back")}}</h1>
                                    </div>

                                    <form id="form_auth" method="POST" class="user" action="{{ route('login') }}" autocomplete="off">
                                        @csrf

                                        <div class="form-group">
                                            <input tabindex="1" id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{__("global.enter_email_address")}}" autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input tabindex="2" id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="{{__("global.password")}}" autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="row mb-3">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input tabindex="3" class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('global.remember_me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                        <input tabindex="4" id="btn_login" type="submit" class="btn btn-primary btn-user btn-block" value="{{ __('global.login') }}">
                                        <hr>
{{--                                        <a href="#" class="btn btn-google btn-user btn-block">--}}
{{--                                            <i class="fab fa-google fa-fw"></i> Login with Google--}}
{{--                                        </a>--}}
{{--                                        <a href="#" class="btn btn-facebook btn-user btn-block">--}}
{{--                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook--}}
{{--                                        </a>--}}
{{--                                        <hr>--}}
                                        @if (Route::has('password.request'))
                                            <div class="text-center">
                                                <a class="btn btn-link" href="{{ route('password.request')."?lang=$_GET[lang]" }}">
                                                    {{ __('global.forgot_your_password') }}
                                                </a>
                                            </div>
                                        @endif

                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
