<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html;" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="{{asset('rs-plugin/css/settings.css')}}" media="screen" />

    <!-- StyleSheets -->
    <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom-style.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

    <!-- Fonts Online -->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">


    <!-- JavaScripts -->
    <script src="{{asset('js/vendors/modernizr.js')}}"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{asset('js/vendors/jquery/jquery.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <!-- Page Wrapper -->
    <div id="wrap" class="layout-1">
        <!-- Top bar -->
        @component('component.top_bar')

        @endcomponent

        <!-- Header -->
        @component('component.header')

        @endcomponent

        <!-- Content -->
        @yield('content')
        <!-- End Content -->

        <!-- Footer -->
        @component('component.footer')

        @endcomponent
        <!-- End Footer -->
    </div>

    <!-- JavaScripts -->
    <script src="{{asset('js/vendors/wow.min.js')}}"></script>
    <script src="{{asset('js/vendors/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/vendors/own-menu.js')}}"></script>
    <script src="{{asset('js/vendors/jquery.sticky.js')}}"></script>
    <script src="{{asset('js/vendors/owl.carousel.min.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

    <!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
    <script type="text/javascript" src="{{asset('rs-plugin/js/jquery.tp.t.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('rs-plugin/js/jquery.tp.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
</body>
</html>
