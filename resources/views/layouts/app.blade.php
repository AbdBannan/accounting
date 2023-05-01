<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (isset($_GET["lang"]))
        @php
            app()->setLocale($_GET["lang"]);
        @endphp
    @else
        @php
            $_GET["lang"] = app()->getLocale();
        @endphp
    @endif

    <title>{{ __("global.".config('app.name', 'accounting')) }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset("css/plugins/fontawesome-free/css/all.min.css")}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset("css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css")}}">

    @if(app()->getLocale() == "ar")
    <!-- Theme style RTL -->
        <link rel="stylesheet" href="{{asset("css/dist/css/ar/adminlte.css")."?var=".rand()}}">
        <!-- Custom style for RTL -->
        <link rel="stylesheet" href="{{asset("css/dist/css/ar/custom.css")."?var=".rand()}}">
    @else
        <!-- Theme style LTR -->
        <link rel="stylesheet" href="{{asset("css/dist/css/en/adminlte.css")."?var=".rand()}}">
    @endif
</head>
<body>
    <input form="form_auth" type="hidden" name="language" value="{{app()->getLocale()}}">

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container d-flex justify-content-between">
                <a class="navbar-brand" href="">
                    {{ __("global.".config('app.name', 'accounting')) }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('global.toggle_navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login')."?lang=$_GET[lang]" }}">{{ __('global.login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register')."?lang=$_GET[lang]" }}">{{ __('global.register') }}</a>
                                </li>
                            @endif

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout_form').submit();">
                                        {{ __('global.logout') }}
                                    </a>

                                    <form id="logout_form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <a href="{{\Illuminate\Support\Facades\URL::current()."?lang=ar"}}" id="lang">{{__("global.arabic")}}</a> /
                <a href="{{\Illuminate\Support\Facades\URL::current()."?lang=en"}}" id="lang">{{__("global.english")}}</a>
            </div>
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <script src="{{asset("js/plugins/jquery/jquery.min.js")}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset("js/plugins/jquery-ui/jquery-ui.min.js")}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- Bootstrap 4 -->
    <script src="{{asset("js/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset("js/js_common.js")}}"></script>


</body>
</html>
