<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

    <div class="navbar-toggler-wrapper d-lg-none">
        <button class="navbar-toggler navbar-toggler-left" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>

    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/fasilita.png') }}" style="width: 80px; height: auto;" alt="logo"/>
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
             <img src="{{ asset('assets/images/fasilita-icon.png') }}" alt="logo"/>
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav navbar-nav-right">
            @php
                use Illuminate\Support\Facades\Auth;
                $authUser = Auth::user();
                $allowedRoles = ['MHS', 'DSN', 'TDK'];
                $userRole = $authUser->peran->kode_peran ?? null;
                // Pastikan variabel $unreadCount ada untuk menghindari error
                $unreadCount = $authUser->notifikasi()->where('is_read', false)->count() ?? 0;
            @endphp

            @if(in_array($userRole, $allowedRoles))
            <li class="nav-item">
                <a class="nav-link position-relative" href="{{ route('notifikasi.index') }}">
                    <i class="icon-bell mx-0"></i>
                    @if($unreadCount > 0)
                        <span class="count bg-danger" style="position: absolute; top: 11px; right: 5px; width: 8px; height: 8px; border-radius: 50%;"></span>
                    @endif
                </a>
            </li>
            @endif

            <li class="nav-item nav-profile d-flex align-items-center">
                <a class="nav-link" href="{{ route('profile.index') }}">
                    <img src="{{ $authUser->foto_profile ? asset('storage/uploads/profiles/' . $authUser->foto_profile) : asset('foto/default.jpg') }}"
                        alt="profile" class="rounded-circle"/>
                    <span class="nav-profile-name d-none d-sm-inline-block">{{ $authUser->username }}</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
