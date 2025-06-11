<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login – FASILITA</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- FontAwesome (Ikon) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}" />
  <link rel="shortcut icon" href="{{ asset('assets/images/fasilita-icon.png') }}" />

  <style>
    /* ================ Reset & Box-sizing ================ */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
    }

    body {
      display: flex;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      min-height: 100vh;
    }

    /* ================ LEFT SECTION (Animasi & Kartu) ================ */
    .left-section {
      width: 50%;
      height: 100vh;
      position: relative;
      overflow: hidden;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    /* Overlay gradient di .left-section */
    .left-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(75, 73, 172, 0.7), rgba(118, 75, 162, 0.5));
      z-index: 1;
    }

    /* Animasi bubble (floating shapes) */
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

    .shape-1 {
      top: 10%;
      left: 10%;
      animation-delay: 0s;
    }

    .shape-2 {
      top: 20%;
      right: 20%;
      animation-delay: 2s;
    }

    .shape-3 {
      bottom: 20%;
      left: 20%;
      animation-delay: 4s;
    }

    .shape-4 {
      bottom: 10%;
      right: 10%;
      animation-delay: 6s;
    }

    @keyframes float {
      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
      }
      50% {
        transform: translateY(-20px) rotate(180deg);
      }
    }

    .purple-card {
       position: absolute;
        top: 0;
        left: 0;
        width: 300px;
        height: 180px;
      background: linear-gradient(135deg, #4B49AC, #6B46C1);
      /* Ubah sudut rounded ke kanan atas */
      border-radius: 0 0 50px 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(75, 73, 172, 0.3);
      z-index: 3;
      /* Ganti animasi agar muncul dari kanan */
      animation: slideInRightCard 1s ease-out;
    }


     @keyframes slideInRightCard {
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
      left: 50px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 12px 20px;
      border-radius: 25px;
      font-size: 14px;
      animation: fadeInOut 4s infinite;
      z-index: 3;
    }

    @keyframes fadeInOut {
      0%,
      100% {
        opacity: 0;
        transform: translateY(-10px);
      }
      50% {
        opacity: 1;
        transform: translateY(0);
      }
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

    /* Notification Card (pojok kiri-bawah) */
    .notification-card {
      position: absolute;
      bottom: 50px;
      left: 50px;
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
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
      100% {
        transform: scale(1);
      }
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

    /* ================ RIGHT SECTION (Form Login) ================ */
    .right-section {
      width: 50%;
      height: 100vh;
      position: relative;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .main-container-login {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* LOGIN CARD kini memenuhi penuh main-container-login */
    .login-card {
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.98);
      border-radius: 20px 0 0 20px; /* Hanya sisi kiri membulat */
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      padding: 40px;
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      animation: slideInRight 0.8s ease-out;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: start;
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Logo & Text di atas form */
    .logo-login {
      display: flex;
      justify-content: start;
      align-items: center;
      margin-bottom: 20px;
      width: 100%;
    }

    .logo-login img {
      max-width: 120px;
    }

    .welcome-text-login {
      margin-bottom: 30px;
    }

    .welcome-text-login h2 {
      color: #333;
      font-size: 35px;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .welcome-text-login p {
      color: #666;
      font-size: 18px;
      opacity: 0.5;
      line-height: 1.5;
    }

    /* Lebar form login */
    .form-fields-container-login {
      max-width: 600px;
      width: 100%;
      margin: 0 auto;
      flex: 1 1 auto;
      overflow-y: auto;
    }

    /* Form Group Login */
    .form-group-login {
      margin-bottom: 20px;
      position: relative;
    }

    .form-group-login label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
      font-size: 14px;
      text-align: left;
    }

    .password-container-login {
      position: relative;
    }

    .password-toggle-login {
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

    .password-toggle-login:hover {
      color: #333;
    }

    .form-control-login {
      width: 100%;
      padding: 15px 20px;
      border: 2px solid #e1e5e9;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.95);
    }

    .form-control-login::placeholder {
      font-size: 16px;
      color: #999;
    }

    .form-control-login:focus {
      border-color: #667eea;
      outline: none;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    .form-control-login:hover {
      border-color: #667eea;
      transform: translateY(-1px);
    }

    /* Validasi Input Login */
    .is-invalid {
      border-color: #dc3545 !important;
      animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
      0%,
      100% {
        transform: translateX(0);
      }
      25% {
        transform: translateX(-5px);
      }
      75% {
        transform: translateX(5px);
      }
    }

    .invalid-feedback-login {
      display: block;
      width: 100%;
      margin-top: 5px;
      font-size: 12px;
      color: #dc3545;
      text-align: left;
    }

    /* Tombol Login */
    .btn-primary-login {
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

    .btn-primary-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-login:active {
      transform: translateY(0);
    }

    .loading-login::after {
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
      0% {
        left: -100%;
      }
      100% {
        left: 100%;
      }
    }

    /* Link Register di Bawah */
    .register-link {
      text-align: center;
      margin-top: 15px;
      color: #666;
      font-size: 14px;
      flex-shrink: 0;
    }

    .register-link a {
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
    }

    .register-link a:hover {
      text-decoration: underline;
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
      .purple-card {
        display: none; /* Sembunyikan kartu di mobile */
      }
      .tooltip-card {
        display: none;
      }
      .notification-card {
        display: none;
      }
      .login-card {
        height: auto;
        border-radius: 20px;
        margin: 20px;
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="left-section">
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

<div class="right-section">
  <div class="main-container-login">
    <div class="login-card">

      <div class="login-content-wrapper">

        <div class="logo-login">
          <a href="{{ route('landing.index') }}"><img src="{{ asset('assets/images/fasilita-logo.png') }}" alt="FASILITA Logo"></a>
        </div>

        <div class="welcome-text-login">
          <h2>Welcome Back</h2>
          <p>Sistem pelaporan dan perbaikan fasilitas kampus yang cepat dan terpercaya.</p>
        </div>

        <form
          id="form-login"
          action="{{ route('login.attempt') }}"
          method="POST"
          novalidate
        >
          @csrf

        <div class="form-group-login">
            <label for="username">Username atau No Induk</label>
            <input
                type="text"
                name="username"
                id="username"
                class="form-control-login"
                placeholder="Masukkan Username atau No Induk"
            />
            <div id="error-username" class="invalid-feedback-login"></div>
        </div>

           <div class="form-group-login">
            <label for="password">Password</label>
            <div class="password-container-login">
              <input
                type="password"
                name="password"
                id="password"
                class="form-control-login"
                placeholder="Password"
              />
              <i class="fas fa-eye password-toggle-login" id="toggle-password-login" data-target="password"></i>
            </div>
            <div id="error-password" class="invalid-feedback-login"></div>
          </div>

          <button
            type="submit"
            class="btn-primary-login"
            id="submit-btn-login"
          >
            <span class="btn-text-login">Login</span>
          </button>

          <div class="register-link">
            Belum punya akun?
            <a href="{{ route('register') }}">Register di sini</a>
          </div>
        </form>

      </div>
      </div>
  </div>
</div>

  {{-- SweetAlert2 --}}
  <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
  {{-- Vendor bundle (jQuery, Bootstrap, dll.) --}}
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  {{-- jQuery --}}
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  {{-- jQuery Validate --}}
  <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>

  <script>
    // Set CSRF token untuk AJAX
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
    });

    $(document).ready(function () {
      // Animasi fokus/blur untuk input login
      $('.form-control-login')
        .on('focus', function () {
          $(this).css({
            transform: 'translateY(-2px)',
            'box-shadow': '0 8px 25px rgba(102, 126, 234, 0.15)',
          });
        })
        .on('blur', function () {
          $(this).css({
            transform: 'translateY(0)',
            'box-shadow': 'none',
          });
        });

        // Password toggle functionality untuk login
      $('.password-toggle-login').on('click', function() {
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

      // Inisialisasi jQuery Validate pada form login
      $('#form-login').validate({
        rules: {
          username: {
            required: true,
            minlength: 4,
            maxlength: 20,
          },
          password: {
            required: true,
            minlength: 5,
            maxlength: 20,
          },
        },
        messages: {
          username: {
            required: 'Harap isi username',
            minlength: 'Username minimal 4 karakter',
          },
          password: {
            required: 'Harap isi password',
            minlength: 'Password minimal 5 karakter',
          },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback-login');
          element.closest('.form-group-login').append(error);
        },
        highlight: function (element) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
          $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
          // Reset error sebelumnya
          $('.is-invalid').removeClass('is-invalid');
          $('.invalid-feedback-login').text('');

          const submitBtn = $('#submit-btn-login');
          const originalBtnText = submitBtn.find('.btn-text-login').text();

          // Disable tombol dan tunjukkan animasi loading
          submitBtn
            .attr('disabled', true)
            .addClass('loading-login')
            .html('<i class="fas fa-spinner fa-spin"></i> Processing...');

          $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (response) {
              // Kembalikan tampilan tombol
              submitBtn
                .removeClass('loading-login')
                .attr('disabled', false)
                .html(`<span class="btn-text-login">${originalBtnText}</span>`);

              if (response.status) {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  text: response.message,
                  confirmButtonText: 'OK',
                }).then((result) => {
                  if (result.isConfirmed || result.isDismissed) {
                    // Redirect ke halaman sesuai response.redirect
                    window.location.href = response.redirect || '{{ route("dashboard") }}';
                  }
                });
              } else {
                // Jika validasi server-side gagal
                if (response.errors) {
                  $.each(response.errors, function (field, messages) {
                    const errorMsg = messages[0];
                    const input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    let feedbackDiv = input
                      .closest('.form-group-login')
                      .find('.invalid-feedback-login');
                    if (feedbackDiv.length === 0) {
                      input
                        .closest('.form-group-login')
                        .append('<div class="invalid-feedback-login"></div>');
                      feedbackDiv = input
                        .closest('.form-group-login')
                        .find('.invalid-feedback-login');
                    }
                    feedbackDiv.text(errorMsg);
                  });
                }
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: response.message || 'Username atau password salah.',
                });
              }
            },
            error: function (xhr) {
              // Kembalikan tampilan tombol
              submitBtn
                .removeClass('loading-login')
                .attr('disabled', false)
                .html(`<span class="btn-text-login">${originalBtnText}</span>`);

              if (xhr.status === 422) {
                // Validasi Laravel gagal (422)
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                  const errorMsg = messages[0];
                  const input = $('[name="' + field + '"]');
                  input.addClass('is-invalid');
                  let feedbackDiv = input
                    .closest('.form-group-login')
                    .find('.invalid-feedback-login');
                  if (feedbackDiv.length === 0) {
                    input
                      .closest('.form-group-login')
                      .append('<div class="invalid-feedback-login"></div>');
                    feedbackDiv = input
                      .closest('.form-group-login')
                      .find('.invalid-feedback-login');
                  }
                  feedbackDiv.text(errorMsg);
                });
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal Login',
                  text: 'Data yang Anda masukkan tidak valid.',
                });
              } else {
                // Error server lain (500, 404, dll.)
                Swal.fire({
                  icon: 'error',
                  title: 'Terjadi Kesalahan Server',
                  text: 'Tidak dapat memproses permintaan Anda. Silakan coba lagi nanti.',
                });
              }
            },
          });

          return false; // Mencegah submit standar
        },
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
    var $container = $('.left-section');
    var idx = 0;

    // Pastikan container sudah memiliki positioning yang benar
    $container.css({
        'position': 'relative',
        'overflow': 'hidden'
    });

    // Create overlay div untuk crossfade effect dengan z-index yang tepat
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
        'z-index': 0, // Di bawah gradient overlay (yang z-index: 1)
        'pointer-events': 'none' // Agar tidak menghalangi interaksi
    });

    // Insert overlay sebagai child pertama (di bawah semua elemen lain)
    $container.prepend($overlay);

    // Set background image pertama di container utama
    $container.css({
        'background-image': 'url(' + baseUrl + '/' + images[idx] + ')',
        'background-size': 'cover',
        'background-position': 'center',
        'background-repeat': 'no-repeat'
    });

    // Preload gambar untuk transisi yang smooth
    function preloadImages() {
        images.forEach(function(imageName) {
            var img = new Image();
            img.src = baseUrl + '/' + imageName;
        });
    }

    function rotateBackground() {
        idx = (idx + 1) % images.length;
        var nextImageUrl = 'url(' + baseUrl + '/' + images[idx] + ')';

        // Set gambar baru di overlay terlebih dahulu
        $overlay.css('background-image', nextImageUrl);

        // Fade in overlay dengan smooth transition
        $overlay.stop(true, true).animate({
            opacity: 1
        }, {
            duration: 2000,
            easing: 'swing',
            complete: function() {
                // Setelah overlay fade in selesai:
                // 1. Pindahkan gambar overlay ke background utama
                $container.css('background-image', nextImageUrl);
                // 2. Reset opacity overlay ke 0 untuk transisi selanjutnya
                $overlay.css('opacity', 0);
            }
        });
    }

    // Preload semua gambar terlebih dahulu
    preloadImages();

    // Mulai rotasi setelah 6 detik
    setInterval(rotateBackground, 6000);

    // Optional: Trigger rotasi pertama setelah 3 detik untuk demo
    // setTimeout(rotateBackground, 3000);
});
  </script>
</body>
</html>
