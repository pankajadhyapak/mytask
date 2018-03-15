<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ url("/css/font-awesome.min.css") }}">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield("extra-css")

    <script>
        window.app = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'baseUrl' => url(""),
            'loggedInUser' => auth()->user() ? auth()->user() : null,
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        @include('layouts.nav')

        <main class="py-4" style="padding-top: 5rem !important;">
            @yield('content')
        </main>

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')
</body>
</html>
