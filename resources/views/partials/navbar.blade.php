<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="mr-5" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/fasilita.png') }}" style="width: 80px;" class="mr-2" alt="logo"/>
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <ul class="navbar-nav navbar-nav-right">
            @php
                use Illuminate\Support\Facades\Auth;
                $authUser = Auth::user();
                // Hanya tampilkan ikon notifikasi untuk peran MHS, DSN, TDK
                $allowedRoles = ['MHS', 'DSN', 'TDK'];
                $userRole     = $authUser->peran->kode_peran ?? null;

                // Hitung jumlah notifikasi belum dibaca untuk user ini
                use App\Models\Notifikasi;
                $unreadCount = $authUser->notifikasi()->where('is_read', false)->count();
            @endphp

            @if(in_array($userRole, $allowedRoles))
    <li class="nav-item">
        <a class="nav-link position-relative" href="{{ route('notifikasi.index') }}">
            <i class="icon-bell mx-0"></i>
            @if($unreadCount > 0)
                {{-- Lingkaran indikator berwarna merah --}}
                <span class="count bg-danger" style="position: absolute; top: 11px; right: -5px; width: 8px; height: 8px; border-radius: 50%;"></span>
            @endif
        </a>
    </li>
@endif

            <li class="nav-item d-none d-lg-flex bg-transparent">
                <a class="nav-link" href="{{ route('profile.index') }}">
                    <span class="mx-2">
                        {{ $authUser->username }}
                    </span>
                    <img src="{{ $authUser->foto_profile
                                  ? asset('storage/uploads/profiles/' . $authUser->foto_profile)
                                  : asset('foto/default.jpg') }}"
                         class="rounded-circle"
                         alt="profile"
                         style="max-height: 40px"/>
                </a>
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                type="button"
                data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
