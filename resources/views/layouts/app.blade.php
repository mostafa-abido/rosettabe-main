<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}" crossorigin="use-credentials">

</head>
<body>
    <div id="app">
        <main class="">
            @yield('content')
        </main>
    </div>

    <script src="{{ config('app.env') == 'local' ? mix('js/app.js') : asset('js/app.js')}}"></script>
</body>
</html>
