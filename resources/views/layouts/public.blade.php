<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="data"
    :class="{'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Docs App') }}</title>

    <link href="{{ url('assets/fontawesome-6.4.0/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ url('assets/fontawesome-6.4.0/css/solid.css') }}" rel="stylesheet">

    <link href="{{ url('css/trongate.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Scripts -->
    @vite(['resources/sass/main.sass', 'resources/js/app.js'])
</head>
<body>

<x-header></x-header>

<div class="public wrapper">

    <div class="public-content container relative">

        @yield('content')

    </div>

    <x-footer></x-footer>

</div>

</body>
</html>
