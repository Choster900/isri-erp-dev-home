<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('app.name', '') }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset("/img/LogoGobSVSmallSize.png") }}">
        <!-- <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}"> -->

        <!-- Fonts -->
        <!-- <link rel="stylesheet" href="{{ asset("css/font_nunito.css") }}">-->
        <link rel="stylesheet" type="text/css" href="{{ url('../css/font_nunito.css') }}" >
        <link rel="stylesheet" type="text/css" href="{{ url('../css/font_roboto.css') }}" >
        <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        {{-- comment code to remove snow--}}
        {{-- <script defer src="https://app.embed.im/snow.js"></script> --}}
        @inertia
    </body>
    <!--
                  _
              .__(.)< (MEOW)
               \___)
           ~~~~~~~~~~~~~~~~~~
       -->

</html>
