<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="data"
    :class="{'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Docs App</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ url('css/trongate.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/prism.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Scripts -->
    @vite(['resources/sass/main.sass', 'resources/js/app.js'])
</head>
<body @scroll="setScrollToTop()">

<div class="admin wrapper">

    <x-header></x-header>

    <div class="container">

        <div class="admin-content relative">

            <x-sidebar></x-sidebar>

            @yield('content')

        </div>
    </div>

    <span class="light-gray pointer scroll-to-top-button padding-0-5 round"
          role="button"
          title="Toggle table of content"
          x-show="scrollTop > 800"
          @click="scrollToTop"
          x-transition
    >
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </span>

    <x-footer></x-footer>

</div>

<script src="{{ url('/js/prism.js') }}" type="text/javascript"></script>
</body>
</html>
