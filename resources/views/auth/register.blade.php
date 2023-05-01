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

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">{{__("global.create_an_account")}}</h1>
                        </div>

                        <form id="form_auth" method="POST" class="user" action="{{ route('register') }}" accept-charset="UTF-8" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input tabindex="1" id="first_name" type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror" placeholder="{{__("global.first_name")}}" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <input tabindex="2" id="last_name" type="text" class="form-control form-control-user @error('last_name') is-invalid @enderror" placeholder="{{__("global.last_name")}}" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" autofocus>
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <input tabindex="3" id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{__("global.enter_email_address")}}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <div class="form-group">
                                        <input tabindex="4" id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="{{__("global.password")}}" autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ \App\functions\globalFunctions::fixTranslation($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input tabindex="5" id="password-confirm" type="password" class="form-control form-control-user" name="password_confirmation" placeholder="{{__("global.confirm_your_password")}}" autocomplete="new-password">
                                    </div>

                                    <div class="form-group">
                                        <input tabindex="6" id="file" type="file" class=" form-control-file" name="file" placeholder="profile image">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{__("messages.max_size_is_3_MB")}}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group text-center" >
                                        <img id="profile_image" src="{{asset("images/systemImages/default_user_img.png")}}" style="width:100%;max-width:170px;margin:10px auto ;border-radius:50%">
                                    </div>
                                </div>
                            </div>

                            <input tabindex="7" id="btn_register" type="submit" class="btn btn-primary btn-user btn-block" value="{{ __('global.register') }}">
                            <hr>
{{--                            <a href="#" class="btn btn-google btn-user btn-block">--}}
{{--                                <i class="fab fa-google fa-fw"></i> Login with Google--}}
{{--                            </a>--}}
{{--                            <a href="#" class="btn btn-facebook btn-user btn-block">--}}
{{--                                <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook--}}
{{--                            </a>--}}
{{--                            <hr>--}}
                            <div class="text-center">
                                <a class="small" href="{{route("login") ."?lang=$_GET[lang]"}}">{{__("global.already_have_an_account")}}</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection







