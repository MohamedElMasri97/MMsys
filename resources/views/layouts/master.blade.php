<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    {{-- style --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <style>
        .body{
            margin:0px;
            padding:0px;
        }
    </style>
    @yield('style')
</head>
<body class="body bg-light">
    @include('partials.header')
    @yield('content')

    <script src="{{asset('js/solid.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/vue.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/all.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
    @yield('script')
</body>
</html>
