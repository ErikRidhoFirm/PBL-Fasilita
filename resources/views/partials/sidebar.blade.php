<nav class="sidebar sidebar-offcanvas w-auto" id="sidebar">
    <ul class="nav">

        {{-- Dashboard (all users) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- ADMIN MENU --}}
        @if(auth()->user()->hasRole('ADM'))
            {{-- User Management --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#user-management"
                    aria-expanded="false" aria-controls="user-management">
                    <i class="fas fa-users-cog menu-icon"></i>
                    <span class="menu-title">Manajemen Pengguna</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="user-management" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('peran.index') }}">
                                <i class="fas fa-user-shield menu-icon"></i> Peran & Hak Akses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pengguna.index') }}">
                                <i class="fas fa-user-friends menu-icon"></i> Daftar Pengguna
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Facility Management --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#facility-management"
                    aria-expanded="false" aria-controls="facility-management">
                    <i class="fas fa-building menu-icon"></i>
                    <span class="menu-title">Manajemen Fasilitas</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="facility-management" data-parent="#sidebar">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gedung.index') }}">
                                <i class="fas fa-university menu-icon"></i> Gedung
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lantai.index') }}">
                                <i class="fas fa-layer-group menu-icon"></i> Lantai
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ruangan.index') }}">
                                <i class="fas fa-door-open menu-icon"></i> Ruangan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('fasilitas.index') }}">
                                <i class="fas fa-couch menu-icon"></i> Fasilitas
                            </a>
                        </li>
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
              <li class="nav-item">
                <a class="nav-link" href="{{ route('kategoriF.index') }}">
                    <i class="fas fa-tags menu-icon"></i> Kategori Fasilitas
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('kategori_kerusakan.index') }}">
                    <i class="fas fa-exclamation-triangle menu-icon"></i> Kategori Kerusakan
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{--SPK & Criteria --}}
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#spk-management" aria-expanded="false">
            <i class="fas fa-calculator menu-icon"></i>
            <span class="menu-title">SPK & Kriteria</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="spk-management">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('kriteria.index') }}">
                    <i class="fas fa-list-ol menu-icon"></i> Kriteria Penilaian
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('skoring.index') }}">
                     <i class="fas fa-star-half-alt menu-icon"></i> Skoring Kriteria
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('spk.index') }}">
                     <i class="fas fa-calculator menu-icon"></i>Perhitungan
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('skorTopsis.index') }}">
                     <i class="fas fa-tasks menu-icon"></i>Prioritas Perbaikan
                </a>
              </li>
            </ul>
          </div>
        </li>
      @endif

      {{-- ============================= --}}
      {{-- ADMIN & SARPRAS MENU          --}}
      {{-- ============================= --}}
      @if(auth()->user()->hasAnyRole(['ADM','SPR']))
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#laporan-management"
            aria-expanded="false" aria-controls="laporan-management">
            <i class="fas fa-clipboard-list menu-icon"></i>
            <span class="menu-title">Laporan</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="laporan-management" data-parent="#sidebar">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                <a class="nav-link" href="{{ route('laporan.index') }}">
                    <i class="fas fa-clipboard-check menu-icon"></i> Verifikasi Laporan
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('riwayat.index') }}">
                    <i class="fas fa-history menu-icon"></i> Riwayat Laporan
                </a>
                </li>
            </ul>
            </div>
        </li>
        @endif

      {{-- ============================= --}}
      {{-- SARPRAS MENU                  --}}
      {{-- ============================= --}}
      @if(auth()->user()->hasRole('SPR'))
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#task-management" aria-expanded="false">
            <i class="icon-wrench menu-icon"></i>
            <span class="menu-title">Manajemen Tugas</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="task-management">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                {{-- <a class="nav-link" href="{{ route('spk.topsis') }}">SPK TOPSIS</a> --}}
              </li>
              <li class="nav-item">
                {{-- <a class="nav-link" href="{{ route('penugasan.index') }}">Penugasan Teknisi</a> --}}
              </li>
            </ul>
          </div>
        </li>
      @endif

      {{-- ============================= --}}
      {{-- EXTERNAL USER MENU (MHS/DSN/TDK) --}}
      {{-- ============================= --}}
      @if(auth()->user()->hasAnyRole(['MHS','DSN','TDK']))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('laporan.create') }}">
            <i class="icon-note menu-icon"></i>
            <span class="menu-title">Buat Laporan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('laporan.index') }}">
            <i class="icon-clock menu-icon"></i>
            <span class="menu-title">Riwayat Laporan</span>
          </a>
        </li>
      @endif

      {{-- ============================= --}}
      {{-- TECHNICIAN MENU               --}}
      {{-- ============================= --}}
      @if(auth()->user()->hasRole('TNS'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('tugas.index') }}">
            <i class="icon-wrench menu-icon"></i>
            <span class="menu-title">Daftar Tugas</span>
          </a>
        </li>
      @endif

      {{-- ============================= --}}
      {{-- LOGOUT                        --}}
      {{-- ============================= --}}
      <li class="nav-item mt-4">
        <a class="nav-link text-danger" href="{{ route('logout') }}">
          <i class="icon-power menu-icon"></i>
          <span class="menu-title">Keluar</span>
        </a>
      </li>
      {{-- <li class="nav-item mt-4">
        <a class="nav-link text-danger" href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="icon-power menu-icon"></i>
          <span class="menu-title">Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
      </li> --}}

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
});
</script>
@endpush
