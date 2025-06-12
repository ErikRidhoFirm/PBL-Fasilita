<nav class="sidebar sidebar-offcanvas w-auto position-fixed" id="sidebar">
    <ul class="nav">
        {{-- Dashboard (all users) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- ADMIN MENU --}}
        @if (auth()->user()->hasRole('ADM'))
            {{-- User Management --}}
            <li class="nav-item">
                {{-- [FIX] ID Unik: user-management-menu --}}
                <a class="nav-link" data-toggle="collapse" href="#user-management-menu" aria-expanded="false"
                    aria-controls="user-management-menu">
                    <i class="fas fa-users-cog menu-icon"></i>
                    <span class="menu-title">Manajemen Pengguna</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="user-management-menu" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('peran.index') }}"> <i class="fas fa-user-shield menu-icon"></i> Peran & Hak Akses </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('pengguna.index') }}"> <i class="fas fa-user-friends menu-icon"></i> Daftar Pengguna </a> </li>
                    </ul>
                </div>
            </li>

            {{-- Facility Management --}}
            <li class="nav-item">
                {{-- [FIX] ID Unik: facility-management-menu --}}
                <a class="nav-link" data-toggle="collapse" href="#facility-management-menu" aria-expanded="false"
                    aria-controls="facility-management-menu">
                    <i class="fas fa-building menu-icon"></i>
                    <span class="menu-title">Manajemen Fasilitas</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="facility-management-menu" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('gedung.index') }}"> <i class="fas fa-university menu-icon"></i> Gedung </a> </li>
                    </ul>
                </div>
            </li>

            {{-- Master Data --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#master-data" aria-expanded="false">
                    <i class="icon-layers menu-icon"></i>
                    <span class="menu-title">Master Data</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master-data">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('kategoriF.index') }}"> <i class="fas fa-tags menu-icon"></i> Kategori Fasilitas </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('kategori_kerusakan.index') }}"> <i class="fas fa-exclamation-triangle menu-icon"></i> Kategori Kerusakan </a> </li>
                    </ul>
                </div>
            </li>

            {{-- SPK & Criteria --}}
            <li class="nav-item">
                 {{-- [FIX] ID Unik: spk-management-adm-menu --}}
                <a class="nav-link" data-toggle="collapse" href="#spk-management-adm-menu" aria-expanded="false">
                    <i class="fas fa-calculator menu-icon"></i>
                    <span class="menu-title">SPK & Kriteria</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="spk-management-adm-menu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('kriteria.index') }}"> <i class="fas fa-list-ol menu-icon"></i> Kriteria Penilaian </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('skoring.index') }}"> <i class="fas fa-star-half-alt menu-icon"></i> Skoring Kriteria </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('spk.index') }}"> <i class="fas fa-calculator menu-icon"></i>Perhitungan </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('skorTopsis.index') }}"> <i class="fas fa-tasks menu-icon"></i>Prioritas Perbaikan </a> </li>
                    </ul>
                </div>
            </li>
        @endif

        {{-- ADMIN & SARPRAS MENU --}}
        @if (auth()->user()->hasAnyRole(['ADM', 'SPR']))
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#laporan-management" aria-expanded="false"
                    aria-controls="laporan-management">
                    <i class="fas fa-clipboard-list menu-icon"></i>
                    <span class="menu-title">Laporan</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="laporan-management" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('laporan.index') }}"> <i class="fas fa-clipboard-check menu-icon"></i> Verifikasi Laporan </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('riwayat.index') }}"> <i class="fas fa-history menu-icon"></i> Riwayat Laporan </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('riwayat-perbaikan.index') }}"> <i class="fas fa-tools menu-icon"></i> Riwayat Perbaikan </a> </li>
                    </ul>
                </div>
            </li>
        @endif

        {{-- SARPRAS MENU --}}
        @if (auth()->user()->hasRole('SPR'))
            <li class="nav-item">
                {{-- [FIX] ID Unik: spk-management-spr-menu --}}
                <a class="nav-link" data-toggle="collapse" href="#spk-management-spr-menu" aria-expanded="false">
                    <i class="fas fa-calculator menu-icon"></i>
                    <span class="menu-title">SPK & Kriteria</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="spk-management-spr-menu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('kriteria.index') }}"> <i class="fas fa-list-ol menu-icon"></i> Kriteria Penilaian </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('skoring.index') }}"> <i class="fas fa-star-half-alt menu-icon"></i> Skoring Kriteria </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('spk.index') }}"> <i class="fas fa-calculator menu-icon"></i>Perhitungan </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('skorTopsis.index') }}"> <i class="fas fa-tasks menu-icon"></i>Prioritas Perbaikan </a> </li>
                    </ul>
                </div>
            </li>
        @endif

        {{-- EXTERNAL USER MENU (MHS/DSN/TDK) --}}
        @if (auth()->user()->hasAnyRole(['MHS', 'DSN', 'TDK']))
            <li class="nav-item">
                {{-- [FIX] ID Unik: pelaporan-menu --}}
                <a class="nav-link" data-toggle="collapse" href="#pelaporan-menu" aria-expanded="false"
                    aria-controls="pelaporan-menu">
                    <i class="fas fa-file-alt menu-icon"></i>
                    <span class="menu-title">Pelaporan</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pelaporan-menu" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('laporanPelapor.index') }}"> <i class="fas fa-plus-circle menu-icon"></i> Buat Laporan </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('riwayatPelapor.index') }}"> <i class="fas fa-history menu-icon"></i> Riwayat Laporan </a> </li>
                    </ul>
                </div>
            </li>
        @endif

        {{-- TECHNICIAN MENU --}}
        @if (auth()->user()->hasRole('TNS'))
            <li class="nav-item">
                {{-- [FIX] ID Unik: penugasan-menu --}}
                <a class="nav-link" data-toggle="collapse" href="#penugasan-menu" aria-expanded="false"
                    aria-controls="penugasan-menu">
                    <i class="fas fa-file-alt menu-icon"></i>
                    <span class="menu-title">Penugasan</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="penugasan-menu" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('penugasan.index') }}"> <i class="mdi mdi-clipboard-text menu-icon"></i> <span>Daftar Tugas</span> </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('riwayat-perbaikan-teknisi.index') }}"> <i class="fas fa-history menu-icon"></i> Riwayat Perbaikan </a> </li>
                    </ul>
                </div>
            </li>
        @endif

        {{-- LOGOUT --}}
        <li class="nav-item mt-4">
            <a class="nav-link text-danger" href="#" id="logout-btn">
                <i class="icon-power menu-icon"></i>
                <span class="menu-title">Keluar</span>
            </a>
        </li>

        {{-- Form logout tersembunyi --}}
        <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display:none;">
            @csrf
        </form>
    </ul>
</nav>

@push('js')
    <script>
        $(document).ready(function() {

            $('.nav-link[data-toggle="collapse"]').on('click', function(e) {

                e.preventDefault();

                e.stopImmediatePropagation();

                var $target = $($(this).attr('href'));

        if ($target.hasClass('show')) {
            $target.collapse('hide');
        } else {
            $target.collapse('show');
        }
    });

// Handle logout dengan SweetAlert
    $('#logout-btn').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari sistem?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Ya, Keluar',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            customClass: {
                popup: 'swal-popup-logout',
                title: 'swal-title-logout',
                content: 'swal-content-logout',
                confirmButton: 'swal-confirm-logout',
                cancelButton: 'swal-cancel-logout'
            },
            backdrop: `
                rgba(0,0,0,0.6)
                left top
                no-repeat
            `,
            allowOutsideClick: false,
            allowEscapeKey: true,
            timer: null,
            timerProgressBar: false,
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading saat logout
                Swal.fire({
                    title: 'Sedang Logout...',
                    text: 'Mohon tunggu sebentar',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form logout
                setTimeout(() => {
                    document.getElementById('logout-form').submit();
                }, 1000);
            }
        });
    });
});
</script>

{{-- Custom CSS untuk SweetAlert --}}
<style>
.swal-popup-logout {
    border-radius: 15px !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important;
}

.swal-title-logout {
    color: #333 !important;
    font-weight: 600 !important;
    font-size: 1.5rem !important;
}

.swal-content-logout {
    color: #666 !important;
    font-size: 1rem !important;
    margin: 10px 0 !important;
}

.swal-confirm-logout {
    background: linear-gradient(45deg, #dc3545, #c82333) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 500 !important;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3) !important;
    transition: all 0.3s ease !important;
}

.swal-confirm-logout:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4) !important;
}

.swal-cancel-logout {
    background: linear-gradient(45deg, #6c757d, #5a6268) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 500 !important;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3) !important;
    transition: all 0.3s ease !important;
}

.swal-cancel-logout:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4) !important;
}

/* Animation classes (optional - jika menggunakan animate.css) */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translate3d(0, -100%, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

@keyframes fadeOutUp {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
        transform: translate3d(0, -100%, 0);
    }
}

.animate__animated {
    animation-duration: 0.5s;
    animation-fill-mode: both;
}

.animate__faster {
    animation-duration: 0.3s;
}

.animate__fadeInDown {
    animation-name: fadeInDown;
}

.animate__fadeOutUp {
    animation-name: fadeOutUp;
}
</style>
@endpush
