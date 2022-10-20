<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>@yield('title',"master")</title>
</head>
<body>
    @include('/layout/navbar');
    @yield('content1');{{-- it you want to use a defoult for content use @section as follow/ --}}
    @include('inc.messages');
    @section('content2')
        <h1>Hhis is a header from master page</h1>
    @show
</body>
</html>
