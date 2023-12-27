<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Sistem Management Personalia')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}asset/images/favicon.png">
    <link rel="stylesheet" href="{{ asset('') }}asset/vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('') }}asset/vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="{{ asset('') }}asset/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="{{ asset('') }}asset/css/style.css" rel="stylesheet">

    <!-- Daterange picker -->
    <link href="{{ asset('') }}asset/vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <!-- <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div> -->
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="#" class="brand-logo">
                <img class="logo-abbr" src="{{ asset('') }}asset/images/logo.png" alt="">
                <img class="logo-compact" src="{{ asset('') }}asset/images/logo-text.png" alt="">
                <!-- <img class="brand-title" src="{{ asset('') }}asset/images/logo-text.png" alt=""> -->
                <span class="brand-title">PERSONALIA</span>
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!-- Header -->
        @include('frontend.layout.header')

        <!-- sidebar -->
        @include('frontend.layout.sidebar')

        <!-- content -->
        <div class="content-body">
            <!-- content -->
            @yield('content')
        </div>

        <!-- footer -->
        @include('frontend.layout.footer')

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('') }}asset/vendor/global/global.min.js"></script>
    <script src="{{ asset('') }}asset/js/quixnav-init.js"></script>
    <script src="{{ asset('') }}asset/js/custom.min.js"></script>

    <!-- Vectormap -->
    <script src="{{ asset('') }}asset/vendor/raphael/raphael.min.js"></script>
    <script src="{{ asset('') }}asset/vendor/morris/morris.min.js"></script>


    <script src="{{ asset('') }}asset/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="{{ asset('') }}asset/vendor/chart.js/Chart.bundle.min.js"></script>

    <script src="{{ asset('') }}asset/vendor/gaugeJS/dist/gauge.min.js"></script>

    <!--  flot-chart js -->
    <script src="{{ asset('') }}asset/vendor/flot/jquery.flot.js"></script>
    <script src="{{ asset('') }}asset/vendor/flot/jquery.flot.resize.js"></script>

    <!-- Owl Carousel -->
    <script src="{{ asset('') }}asset/vendor/owl-carousel/js/owl.carousel.min.js"></script>

    <!-- Counter Up -->
    <script src="{{ asset('') }}asset/vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="{{ asset('') }}asset/vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="{{ asset('') }}asset/vendor/jquery.counterup/jquery.counterup.min.js"></script>

    <script src="{{ asset('') }}asset/js/dashboard/dashboard-1.js"></script>

    <!-- Daterangepicker -->
    <!-- momment js is must -->
    <script src="{{ asset('') }}asset/vendor/moment/moment.min.js"></script>
    <script src="{{ asset('') }}asset/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Daterangepicker -->
    <script src="{{ asset('') }}asset/js/plugins-init/bs-daterange-picker-init.js"></script>

</body>

</html>