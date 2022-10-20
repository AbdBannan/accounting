@extends('layouts.app')

@section('content')
    {{--<div class="container">--}}
    {{--    <div class="row justify-content-center">--}}
    {{--        <div class="col-md-8">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-header">{{ __('Login') }}</div>--}}

    {{--                <div class="card-body">--}}
    {{--                    ///////--}}
    {{--                    <form method="POST" action="{{ route('login') }}">--}}
    {{--                        @csrf--}}

    {{--                        <div class="row mb-3">--}}
    {{--                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>--}}

    {{--                            <div class="col-md-6">--}}
    {{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

    {{--                                @error('email')--}}
    {{--                                    <span class="invalid-feedback" role="alert">--}}
    {{--                                        <strong>{{ $message }}</strong>--}}
    {{--                                    </span>--}}
    {{--                                @enderror--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                        <div class="row mb-3">--}}
    {{--                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>--}}

    {{--                            <div class="col-md-6">--}}
    {{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">--}}

    {{--                                @error('password')--}}
    {{--                                    <span class="invalid-feedback" role="alert">--}}
    {{--                                        <strong>{{ $message }}</strong>--}}
    {{--                                    </span>--}}
    {{--                                @enderror--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                        <div class="row mb-3">--}}
    {{--                            <div class="col-md-6 offset-md-4">--}}
    {{--                                <div class="form-check">--}}
    {{--                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

    {{--                                    <label class="form-check-label" for="remember">--}}
    {{--                                        {{ __('Remember Me') }}--}}
    {{--                                    </label>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                        <div class="row mb-0">--}}
    {{--                            <div class="col-md-8 offset-md-4">--}}
    {{--                                <button type="submit" class="btn btn-primary">--}}
    {{--                                    {{ __('Login') }}--}}
    {{--                                </button>--}}

    {{--                                @if (Route::has('password.request'))--}}
    {{--                                    <a class="btn btn-link" href="{{ route('password.request') }}">--}}
    {{--                                        {{ __('Forgot Your Password?') }}--}}
    {{--                                    </a>--}}
    {{--                                @endif--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </form>--}}
    {{--                    //////--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            --}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}

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
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>

                                    <form method="POST" class="user" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group">
                                            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter Email Address..." autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="password" autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="row mb-3">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                        <input id="btn_login" type="submit" class="btn btn-primary btn-user btn-block" {{ __('global.login',[],session("lang")) }}>
                                        <hr>
                                        <a href="#" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="#" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                        <hr>
                                        @if (Route::has('password.request'))
                                            <div class="text-center">
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
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







{{--<div class="container">--}}

{{--    <!-- Outer Row -->--}}
{{--    <div class="row justify-content-center">--}}

{{--        <div class="col-xl-10 col-lg-12 col-md-9">--}}

{{--            <div class="card o-hidden border-0 shadow-lg my-5">--}}
{{--                <div class="card-body p-0">--}}
{{--                    <!-- Nested Row within Card Body -->--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>--}}
{{--                        <div class="col-lg-6">--}}
{{--                            <div class="p-5">--}}
{{--                                <div class="text-center">--}}
{{--                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>--}}
{{--                                </div>--}}

{{--                                {!! Form::open(["metod"=>"POST","action"=>"Auth\LoginController@showLoginForm","class"=>"user"]) !!}--}}
{{--                                    <div class="form-group">--}}
{{--                                        {!! Form::email("email","",["class"=>"form-control form-control-user","placeholder"=>"Enter Email Address..."]) !!}--}}
{{--                                        @error('email')--}}
{{--                                            <span class="invalid-feedback" role="alert">--}}
{{--                                                <strong>{{ $message }}</strong>--}}
{{--                                            </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        {!! Form::password("password",["class"=>"form-control form-control-user","placeholder"=>"Password"]) !!}--}}
{{--                                        @error('password')--}}
{{--                                            <span class="invalid-feedback" role="alert">--}}
{{--                                                <strong>{{ $message }}</strong>--}}
{{--                                            </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}


{{--                                    <div class="form-check">--}}
{{--                                        {!! Form::checkbox("remember_me", old('remember') ? 'checked' : '',["class"=>"form-check-input"]) !!}--}}
{{--                                        {!! Form::label("remember_me", __('Remember Me') ,["class"=>"form-check-label"]) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="custom-control custom-checkbox small">--}}
{{--                                            {!! Form::checkbox("remember_me",{{ old('remember') ? 'checked' : ''}},["class"=>"form-check-input"]) !!}--}}
{{--                                            {!! Form::label("remember_me",{{ __('Remember Me') }},["class"=>"form-check-label"]) !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    {!! Form::submit( __('Login') ,["class"=>"btn btn-primary btn-user btn-block"]) !!}--}}
{{--                                    <hr>--}}
{{--                                    <a href="index.html" class="btn btn-google btn-user btn-block">--}}
{{--                                        <i class="fab fa-google fa-fw"></i> Login with Google--}}
{{--                                    </a>--}}
{{--                                    <a href="index.html" class="btn btn-facebook btn-user btn-block">--}}
{{--                                        <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook--}}
{{--                                    </a>--}}
{{--                                   <br>--}}
{{--                                    @if (Route::has('password.request'))--}}
{{--                                        <div class="text-center">--}}
{{--                                            <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                                {{ __('Forgot Your Password?') }}--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                {!! Form::close() !!}--}}
{{--                                <form class="user">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <input type="email" class="form-control form-control-user"--}}
{{--                                               id="exampleInputEmail" aria-describedby="emailHelp"--}}
{{--                                               placeholder="Enter Email Address...">--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <input type="password" class="form-control form-control-user"--}}
{{--                                               id="exampleInputPassword" placeholder="Password">--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="custom-control custom-checkbox small">--}}
{{--                                            <input type="checkbox" class="custom-control-input" id="customCheck">--}}
{{--                                            <label class="custom-control-label" for="customCheck">Remember--}}
{{--                                                Me</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <a href="index.html" class="btn btn-primary btn-user btn-block">--}}
{{--                                        Login--}}
{{--                                    </a>--}}
{{--                                    <hr>--}}
{{--                                    <a href="index.html" class="btn btn-google btn-user btn-block">--}}
{{--                                        <i class="fab fa-google fa-fw"></i> Login with Google--}}
{{--                                    </a>--}}
{{--                                    <a href="index.html" class="btn btn-facebook btn-user btn-block">--}}
{{--                                        <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook--}}
{{--                                    </a>--}}
{{--                                </form>--}}
{{--                                <hr>--}}
{{--                                <div class="text-center">--}}
{{--                                    <a class="small" href="forgot-password.html">Forgot Password?</a>--}}
{{--                                </div>--}}
{{--                                <div class="text-center">--}}
{{--                                    <a class="small" href="register.html">Create an Account!</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}

{{--    </div>--}}

{{--</div>--}}

