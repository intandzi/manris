<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | {{ $title ?? '' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Expert Risk Management Systems by CIT STUDIO - Enhance your business's safety and efficiency with our advanced risk assessment tools and solutions.">
    <meta name="author" content="CIT STUDIO's">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">

    <!-- Vector Map css -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    @stack('style-alt')
    @livewireStyles
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        {{-- @include('livewire.layout.topbar') --}}
        <livewire:layout.topbar />
        <!-- ========== Topbar End ========== -->

        <!-- Left Sidebar Start -->
        <livewire:layout.navigation />
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                {{ $slot }}
                <!-- end container -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <script>
                                    // document.write(new Date().getFullYear())
                                </script> © <CITe>CITS STUDIO's</CITe> 2024</b>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>
            <!-- content -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings -->
    <livewire:layout.theme-setting />

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/lucide/umd/lucide.min.js') }}"></script>

    <!-- Apex Charts js -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector Map js -->
    <script src="{{ asset('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}">
    </script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    @stack('script-alt')
    @livewireScripts

</body>

</html>
