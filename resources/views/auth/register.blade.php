<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register – FASILITA</title>
    <!-- CSS Skydash -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }

        .text-danger {
            color: #dc3545 !important;
        }
    </style>
</head>

<body>
    <main class="container" style="height: 700px">
        <div class="row py-5 h-100">
            <div class="col-md-5 d-flex flex-column justify-content-center">
                <img src="{{ asset('assets/images/fasilita-logo.png') }}" class="img-fluid mb-4"
                    style="max-width: 100px" alt="">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="form-register" method="POST" action="{{ route('register.store') }}" class="forms-sample pr-5"
                    novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama"
                            class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                            placeholder="Nama Lengkap">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username"
                            class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                            placeholder="Username">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Konfirmasi Password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                </form>

                <p class="mt-3">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Login di sini</a>
                </p>
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
                        <div class="bg-white w-50 pl-3 " style="border-radius: 0 0 0 30px; margin-top: -1px; ">
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
    </main>

    {{-- Sweetalert --}}
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- JavaScript Libraries -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi validasi
            $('#form-register').validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 3
                    },
                    username: {
                        required: true,
                        minlength: 4
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    nama: {
                        required: "Harap isi nama lengkap",
                        minlength: "Nama minimal 3 karakter"
                    },
                    username: {
                        required: "Harap isi username",
                        minlength: "Username minimal 4 karakter"
                    },
                    password: {
                        required: "Harap isi password",
                        minlength: "Password minimal 5 karakter"
                    },
                    password_confirmation: {
                        required: "Harap konfirmasi password",
                        equalTo: "Password tidak cocok"
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
                submitHandler: function(form) {
                    // Reset error state
                    $('.is-invalid').removeClass('is-invalid');
                    $('.text-danger').removeClass('text-danger');
                    $('.invalid-feedback').remove();
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
                                    window.location = '/';
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