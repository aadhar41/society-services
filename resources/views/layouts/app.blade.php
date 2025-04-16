<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name'))</title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->
    <!-- jQuery -->
    <script src="{{ URL::asset('assets/jquery/jquery.min.js') }}"></script>

    <script src="{{ mix('js/app.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ URL::asset('js/common.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <style>
        .nav-item {
            text-transform: capitalize;
        }

        .maintenance-img {
            object-fit: cover;
        }
    </style>
    @yield('third_party_stylesheets')

    @stack('page_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('images/ag-logo-big.png') }}" alt="AdminLTELogo" height="100" width="100">
        </div>

        <!-- Main Header -->
        @include('layouts.partials._admin-mainnavbar')

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.partials._admin-sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">
                @if (session('success'))
                <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('warning'))
                <x-alert type="warning" :message="session('warning')" />
                @endif
                @if (session('error'))
                <x-alert type="error" :message="session('error')" />
                @endif

                @yield('content')
            </section>
        </div>

        <!-- Main Footer -->
        @include('layouts.partials._admin-footer')
    </div>



    @yield('third_party_scripts')

    @stack('page_scripts')
</body>


</html>