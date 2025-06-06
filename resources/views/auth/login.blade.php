<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login – FASILITA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/fasilita-icon.png') }}" />
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
</head>

<body>
    <main class="container" style="height: 700px">
        <div class="row py-5 h-100">
            <div class="col-md-5 d-flex flex-column justify-content-between">
                <img src="{{ asset('assets/images/fasilita-logo.png') }}" class="img-fluid" style="max-width: 100px"
                    alt="">
                <form id="form-login" class="forms-sample pr-5" action="{{ route('login.attempt') }}" method="POST">
                    @csrf
                    <div class="my-auto">
                        <div class="text-center my-5">
                            <h3>Welcome Back</h3>
                            <h6>Sistem pelaporan dan perbaikan fasilitas kampus yang cepat dan terpercaya.</h6>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                name="username" id="username" placeholder="Username">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="password" placeholder="Masukkan Password">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary rounded-right" type="button">
                                        <i class="mdi mdi-eye" id="togglePassword"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <p class="mt-3">Belum punya akun? <a href="{{ url('/register') }}">register</a></p>
                    </div>
                </form>
                <div>

                </div>
            </div>
            <div class="col-md-7 px-0"
                style="background-image: url('{{ asset('assets/images/bg-register2.png') }}'); border-radius: 30px">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div class="d-flex flex-row justify-content-between h-25">
                        <div class="w-50 px-4">
                            <div class="d-flex justify-content-around align-items-center border border-white text-white py-1 px-2 mt-5 w-100"
                                style="border-radius: 30px 30px">
                                <button type="button" class="btn btn-light btn-rounded btn-icon font-weight-bold"
                                    style="color: #4B49AC">
                                    <h3 class="m-0 font-weight-bold">?</h3>
                                </button>
                                <h5 class="m-0">
                                    Jangan abaikan kerusakan.
                                </h5>
                            </div>
                        </div>
                        <div class="bg-white w-50 pl-3" style="border-radius: 0 0 0 30px; margin-top: -1px;">
                            <div class="stretch-card transparent h-100 pb-3">
                        <div class="card card-dark-blue d-flex" style="border-radius: 0 30px 0 30px; height: 100%;">
                            <div class="card-body d-flex justify-content-center align-items-center text-center w-100" style="height: 100%;">
                                <h5 class="m-0" style="line-height: 1.6;">
                                    Laporan Ditindaklanjuti Dengan<br>
                                    Cepat & Tepat
                                </h5>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>

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
    {{-- Sweetalert --}}
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <!-- endinject -->

    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.roundedBarCharts.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
    <!-- End custom js for this page-->



    <script>
        $('#togglePassword').click(function() {
            $(this).toggleClass("mdi-eye mdi-eye-off");
            var input = $('#password');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $("#form-login").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    }
                },
                messages: {
                    username: {
                        required: "Harap isi username",
                        minlength: "Username minimal 4 karakter"
                    },
                    password: {
                        required: "Harap isi password",
                        minlength: "Password minimal 5 karakter"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                    $(element).closest('.form-group').find('label').addClass('text-danger');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).closest('.form-group').find('label').removeClass('text-danger');
                },
                submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) { // jika sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else { // jika error
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>
