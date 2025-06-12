<!DOCTYPE html>
<html lang="en">

<head>
    @stack('css')
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fasilita</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome-free/css/all.min.css') }}">

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/custom-responsive.css') }}">

    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/fasilita-icon.png') }}" />

    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
</head>

<body>
    <div class="container-scroller">
        @include('partials.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('partials.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @includeWhen(View::exists('partials.breadcrumb'), 'partials.breadcrumb')
                    @yield('content')
                </div>
                @include('partials.footer')
            </div>
        </div>
    </div>

    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Plugin js for this page -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
    {{-- Sweetalert --}}
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>

    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->

    <!-- Custom js for this page-->
    {{-- <script src="{{ asset('assets/js/chart.js') }}"></script> --}}
    <!-- endinject -->

    <!-- Custom js for this page-->
    {{-- <script src="{{ asset('assets/js/dashboard.js') }}"></script> --}}
    <script src="{{ asset('assets/js/Chart.roundedBarCharts.js') }}"></script>
    <!-- End custom js for this page-->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            const $offcanvasToggler = $('[data-toggle="offcanvas"]');
            const $sidebar = $('.sidebar-offcanvas');
            const $wrapper = $('.page-body-wrapper');

            function toggleSidebar() {
                $sidebar.toggleClass('active');
                $wrapper.toggleClass('sidebar-open');
            }

            $offcanvasToggler.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });

            // Event untuk menutup saat overlay/area luar diklik
            $(document).on('click', function(e) {
                // Cek jika sidebar terbuka DAN target klik BUKAN bagian dari sidebar DAN BUKAN tombol toggler
                if ($wrapper.hasClass('sidebar-open') &&
                    $(e.target).closest('.sidebar-offcanvas').length === 0 &&
                    $(e.target).closest('[data-toggle="offcanvas"]').length === 0)
                {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('js')

</body>

</html>
