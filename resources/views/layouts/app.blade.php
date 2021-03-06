<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('style')

    <script>
        window.App = {!! json_encode([
            'signedIn'  => Auth::check(),
            'user'      => Auth::user(),
        ]) !!}
    </script>
    <style>
        body
        {
            padding-bottom: 50px;
        }
        .level
        {
            display: flex;
        }
        .level > *
        {
            margin-right: 10px;
        }
        [v-cloak]
        {
            display: none;
        }

    </style>
</head>
<body>
    <div id="app">

        @include('layouts._nav')
        @yield('content')

        <flash message="{{ session('flash') }}"></flash>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
