<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('app/img/logo_ial.png') }}">



    <meta name="title" content="Essentium Group"/>
    <meta name="description" content=""/>
    <meta name="keywords" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="Essentium Group">
    <meta property="og:type" content="website">
    <meta name="author" content="MAJML" />
    <meta name="Resource-type" content="Document" />

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('app/plugins/bootstrap4/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/font-awesome/css/font-awesome.css') }}">

    <link rel="shortcut icon" href="{{ asset('app/img/logo2.png') }}" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=5; IE=6; IE=7; IE=8; IE=9; IE=10">
    <title>Essentium Group</title>
    <link rel="stylesheet" href="{{ asset('app/plugins/bootstrap4/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/owl-carousel/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app/plugins/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('app/css/app.min.css') }}">
    @yield('styles')
</head>
<body>

    <div id="loading">
        <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
    </div>


    @yield('content')


    <script type="text/javascript" src="{{ asset('app/plugins/jquery/3.5.1/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/plugins/bootstrap4/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/plugins/toastr/js/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('app/js/_Layout.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
