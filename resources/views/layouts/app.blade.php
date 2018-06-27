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

    @yield('page-specific-css')
    
    <script>
        window.App = {!! json_encode([
            'signedIn' => Auth::check(),
            'user' => Auth::user(),
        ]) !!};
    </script>
    <style>
        body {
            padding-bottom: 6em;
        }

        .level {
            display: flex;
            align-items: center
        }

        .flex {
            flex: 1;
        }

        .mr-1{
            margin-right: 1em;
        }

        .ml-auto {
            margin-left: auto;
        }
        [v-vcloak] { display: none}
        [v-cloak] .v-cloak--block {
            display: block;
        }
        [v-cloak] .v-cloak--inline {
            display: inline;
        }
        [v-cloak] .v-cloak--inlineBlock {
            display: inline-block;
        }
        [v-cloak] .v-cloak--hidden {
            display: none;
        }
        [v-cloak] .v-cloak--invisible {
            visibility: hidden;
        }
        .v-cloak--block,
        .v-cloak--inline,
        .v-cloak--inlineBlock {
            display: none;
        }

    </style>'

    @yield('head')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            @include('layouts.nav')
        </div>
    </nav>

    @yield('content')
    <flash message="{{ session('flash') }}"></flash>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
