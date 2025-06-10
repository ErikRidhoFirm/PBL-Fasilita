<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register FASILITA</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- SweetAlert --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/fasilita-icon.png') }}" />

    <style>
        /* ================ Reset & Box-sizing ================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }

        /* ================ LEFT SECTION (Form) ================ */
        .left-section {
            width: 50%;
            height: 100vh;
            position: relative;
            background: #f5f5f5; /* Bisa diganti sesuai kebutuhan */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .main-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 0 20px 20px 0;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideInLeft 0.8s ease-out;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: start;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Logo & Welcome Text */
        .logo {
            display: flex;
            justify-content: start;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .logo img {
            max-width: 120px;
        }

        .welcome-text {
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            color: #333;
            font-size: 35px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .welcome-text p {
            color: #666;
            font-size: 18px;
            opacity: 0.5;
            line-height: 1.5;
        }

        /* === PENYESUAIAN LEBAR FORM DI SINI === */
        .form-fields-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            text-align: left;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 16px;
            z-index: 10;
            padding: 5px;
            border-radius: 3px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            padding-right: 45px !important;
        }

        .form-control::placeholder {
            font-size: 16px;
            color: #999;
        }

        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: #667eea;
            transform: translateY(-1px);
        }

        /* Tombol Submit */
        .btn-primary {
            width: 100%;
            max-width: 600px;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Link Login di Bawah */
        .login-link {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
            flex-shrink: 0;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Validasi Input */
        .is-invalid {
            border-color: #dc3545 !important;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 5px;
            font-size: 12px;
            color: #dc3545;
            text-align: left;
        }

        /* ================ RIGHT SECTION (Animasi & Kartu) ================ */
        .right-section {
            width: 50%;
            position: relative;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .right-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(75, 73, 172, 0.7), rgba(118, 75, 162, 0.5));
            z-index: 1;
        }

        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 2;
        }

        .floating-shapes {
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 { top: 10%; left: 10%; animation-delay: 0s; }
        .shape-2 { top: 20%; right: 20%; animation-delay: 2s; }
        .shape-3 { bottom: 20%; left: 20%; animation-delay: 4s; }
        .shape-4 { bottom: 10%; right: 10%; animation-delay: 6s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .purple-card {
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 180px;
            background: linear-gradient(135deg, #4B49AC, #6B46C1);
            border-radius: 0 0 0 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(75, 73, 172, 0.3);
            z-index: 3;
            animation: slideInRight 1s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .purple-card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .tooltip-card {
            position: absolute;
            /* Posisikan di atas notification-card */
            /* Kalkulasi: 50px (jarak notif dari bawah) + ~100px (tinggi notif) + 15px (gap) */
            bottom: 165px;
            /* Samakan posisi kiri dengan notification-card */
            right: 50px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 14px;
            animation: fadeInOut 4s infinite;
            z-index: 3;
            }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0; transform: translateY(-10px); }
            50% { opacity: 1; transform: translateY(0); }
        }

        .tooltip-card::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid rgba(0, 0, 0, 0.8);
        }

        .notification-card {
            position: absolute;
            bottom: 50px;
            right: 50px;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 350px;
            animation: slideInUp 1.2s ease-out;
            z-index: 3;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-card .icon-circle {
            width: 60px;
            height: 60px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .notification-card .text h4 {
            color: #333;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .notification-card .text p {
            color: #666;
            font-size: 13px;
            margin: 0;
        }

        .purple-card:hover,
        .notification-card:hover {
            transform: translateY(-5px) scale(1.02);
            transition: transform 0.3s ease;
        }

        .floating-shapes:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.2);
        }

        /* ================ Responsive (≤ 768px) ================ */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .left-section,
            .right-section {
                width: 100%;
                height: auto;
            }
            .register-card {
                height: auto;
                border-radius: 20px;
                margin: 20px;
                padding: 30px 20px;
            }
            .purple-card {
                display: none; /* Sembunyikan kartu di mobile untuk fokus ke form */
            }
            .tooltip-card {
                display: none; /* Sembunyikan kartu di mobile */
            }
            .notification-card {
               display: none; /* Sembunyikan kartu di mobile */
            }
        }
    </style>
</head>
<body>

    <div class="left-section">
        <div class="main-container">
            <div class="register-card">
                <div style="flex: 1 1 auto; overflow-y: auto; width: 100%; display: flex; align-items: center;">
                    <div class="form-fields-container">

                        {{-- Formulir Registrasi --}}
                        <form id="form-register" action="{{ route('register.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="logo">
                                <a href="{{ route('landing.index') }}"><img src="{{ asset('assets/images/fasilita-logo.png') }}" alt="FASILITA Logo"></a>
                            </div>
                            <div class="welcome-text">
                                <h2><b>Buat Akun Baru</b></h2>
                                <p>Bergabunglah dengan FASILITA untuk melaporkan dan menindak lanjuti kerusakan dengan cepat.</p>
                            </div>

                            <div class="form-group">
                                <label for="no_induk">Nomor Induk</label>
                                <input type="text" id="no_induk" name="no_induk" class="form-control" placeholder="NIM/NIP Anda">
                                <div class="invalid-feedback"></div>
                            </div>


                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: John Doe">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Contoh: johndoe">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="password-container">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 5 karakter">
                                    <i class="fas fa-eye password-toggle" id="toggle-password" data-target="password"></i>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="password-container">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password di atas">
                                    <i class="fas fa-eye password-toggle" id="toggle-password-confirmation" data-target="password_confirmation"></i>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div style="flex-shrink: 0; width: 100%; max-width: 600px;">
                    <button type="submit" form="form-register" class="btn-primary" id="submit-btn">
                        <span class="btn-text">Sign Up</span>
                    </button>
                    <div class="login-link">
                        Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-section">
        <div class="bg-animation">
            <div class="floating-shapes shape-1"></div>
            <div class="floating-shapes shape-2"></div>
            <div class="floating-shapes shape-3"></div>
            <div class="floating-shapes shape-4"></div>
        </div>
        <div class="purple-card">
            <h3>Laporan Ditindaklanjuti Dengan Cepat & Tepat</h3>
        </div>
        <div class="tooltip-card">
            <i class="fas fa-question-circle"></i> Jangan abaikan kerusakan
        </div>
        <div class="notification-card">
            <div class="icon-circle">?</div>
            <div class="text">
                <h4>Temukan Masalah di Sekitarmu?</h4>
                <p>Segera Laporkan Jika Ada Kerusakan!</p>
            </div>
        </div>
    </div>

        {{-- Sweetalert --}}
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- JavaScript Libraries -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>

    <script>
        $('.toggle-password').click(function() {
            $(this).toggleClass("mdi-eye mdi-eye-off");

            const target = $($(this).data("target"));
            const type = target.attr("type") === "password" ? "text" : "password";
            target.attr("type", type);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('.form-control').on('focus', function() {
                $(this).css({
                    'transform': 'translateY(-2px)',
                    'box-shadow': '0 8px 25px rgba(102, 126, 234, 0.15)'
                });
            }).on('blur', function() {
                $(this).css({
                    'transform': 'translateY(0)',
                    'box-shadow': 'none'
                });
            });

            // Password toggle functionality
            $('.password-toggle').on('click', function() {
                const targetId = $(this).data('target');
                const targetInput = $('#' + targetId);
                const currentType = targetInput.attr('type');

                if (currentType === 'password') {
                    targetInput.attr('type', 'text');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    targetInput.attr('type', 'password');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            $("#form-register").validate({
                rules: {
                    no_induk: {
                        required: true,
                        minlength: 10,
                        maxlength: 18
                    },
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
                        // minlength: 5
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    }
                },
                messages: {
                    no_induk: {
                        required: "Nomor induk tidak boleh kosong",
                        minlength: "Nomor induk minimal harus 5 karakter"
                    },
                    nama: {
                        required: "Nama tidak boleh kosong",
                        minlength: "Nama minimal harus 3 karakter"
                    },
                    username: {
                        required: "Username tidak boleh kosong",
                        minlength: "Username minimal harus 4 karakter"
                    },
                    password: {
                        required: "Password tidak boleh kosong",
                        minlength: "Password minimal harus 5 karakter"
                    },
                    password_confirmation: {
                        required: "Konfirmasi password tidak boleh kosong",
                        minlength: "Konfirmasi password minimal harus 5 karakter",
                        equalTo: "Konfirmasi password tidak cocok dengan password"
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    const submitBtn = $('#submit-btn');
                    const originalBtnText = submitBtn.find('.btn-text').text();

                    submitBtn.attr('disabled', true).addClass('loading').html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            submitBtn.removeClass('loading').attr('disabled', false).html(`<span class="btn-text">${originalBtnText}</span>`);

                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = response.redirect || "{{ route('login') }}";
                                    }
                                });
                            } else {
                                if (response.errors) {
                                    $.each(response.errors, function(field, messages) {
                                        const errorMsg = messages[0];
                                        const input = $('[name="' + field + '"]');
                                        input.addClass('is-invalid');
                                        input.closest('.form-group').find('.invalid-feedback').text(errorMsg);
                                    });
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Terjadi kesalahan, silakan periksa kembali data Anda.'
                                });
                            }
                        },
                        error: function(xhr) {
                            submitBtn.removeClass('loading').attr('disabled', false).html(`<span class="btn-text">${originalBtnText}</span>`);

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(field, messages) {
                                    const errorMsg = messages[0];
                                    const input = $('[name="' + field + '"]');
                                    input.addClass('is-invalid');
                                    let feedbackDiv = input.closest('.form-group').find('.invalid-feedback');
                                    if(feedbackDiv.length === 0) {
                                        input.closest('.form-group').append('<div class="invalid-feedback"></div>');
                                        feedbackDiv = input.closest('.form-group').find('.invalid-feedback');
                                    }
                                    feedbackDiv.text(errorMsg);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Registrasi',
                                    text: 'Data yang Anda masukkan tidak valid.'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan Server',
                                    text: 'Tidak dapat memproses permintaan Anda. Silakan coba lagi nanti.'
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });

$(function() {
    var images = [
        'bg-register1.png',
        'bg-register5.png',
        'bg-register3.png',
        'bg-register4.png'
    ];
    var baseUrl = "{{ asset('assets/images') }}";
    var $container = $('.right-section');
    var idx = 0;

    $container.css({
        'position': 'relative',
        'overflow': 'hidden'
    });

    var $overlay = $('<div>').css({
        'position': 'absolute',
        'top': 0,
        'left': 0,
        'width': '100%',
        'height': '100%',
        'background-size': 'cover',
        'background-position': 'center',
        'background-repeat': 'no-repeat',
        'opacity': 0,
        'z-index': 0,
        'pointer-events': 'none'
    });

    $container.prepend($overlay);

    $container.css({
        'background-image': 'url(' + baseUrl + '/' + images[idx] + ')',
        'background-size': 'cover',
        'background-position': 'center',
        'background-repeat': 'no-repeat'
    });


    function preloadImages() {
        images.forEach(function(imageName) {
            var img = new Image();
            img.src = baseUrl + '/' + imageName;
        });
    }

    function rotateBackground() {
        idx = (idx + 1) % images.length;
        var nextImageUrl = 'url(' + baseUrl + '/' + images[idx] + ')';

        $overlay.css('background-image', nextImageUrl);

        $overlay.stop(true, true).animate({
            opacity: 1
        }, {
            duration: 2000,
            easing: 'swing',
            complete: function() {
                $container.css('background-image', nextImageUrl);
                $overlay.css('opacity', 0);
            }
        });
    }

    preloadImages();

    setInterval(rotateBackground, 6000);
});
    </script>
</body>
</html>
