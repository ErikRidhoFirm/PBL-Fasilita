@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Notifikasi</h3>
                <h6 class="font-weight-normal mb-0">Kelola dan pantau semua notifikasi Anda</h6>
            </div>
        </div>
    </div>
</div>

<!-- Action Bar: tombol filter dan Tandai Semua -->
<div class="row mb-4">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                {{-- Filter tabs (Semua / Belum Dibaca / Sudah Dibaca) --}}
                <div>
                    @php
                        // Membangun base URL tanpa query string, untuk menambahkan ?filter=
                        $baseUrl = route('notifikasi.index');
                    @endphp

                    <a href="{{ $baseUrl }}?filter=all"
                       class="btn btn-sm @if($filter === 'all') btn-primary @else btn-outline-secondary @endif mr-2">
                        Semua
                    </a>
                    <a href="{{ $baseUrl }}?filter=unread"
                       class="btn btn-sm @if($filter === 'unread') btn-primary @else btn-outline-secondary @endif mr-2">
                        Belum Dibaca
                        @if($totalUnread > 0)
                            <span class="badge badge-pill badge-warning ml-1">{{ $totalUnread }}</span>
                        @endif
                    </a>
                    <a href="{{ $baseUrl }}?filter=read"
                       class="btn btn-sm @if($filter === 'read') btn-primary @else btn-outline-secondary @endif">
                        Sudah Dibaca
                        @if($totalRead > 0)
                            <span class="badge badge-pill badge-secondary ml-1">{{ $totalRead }}</span>
                        @endif
                    </a>
                </div>

                {{-- Tombol "Tandai Semua Dibaca" --}}
                @if($totalUnread > 0)
                    <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm">
                            <i class="ti-check mr-1"></i>
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Notifications List -->
<div class="row">
    @forelse($notifikasi as $item)
        <div class="col-12">
            <div class="card mb-3 {{ !$item->is_read ? 'border-primary' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        {{-- Konten Notifikasi --}}
                        <div class="d-flex flex-grow-1">
                            {{-- Indikator status: biru = belum dibaca, abu = sudah dibaca --}}
                            <div class="mr-3 mt-1">
                                <div class="badge {{ !$item->is_read ? 'badge-primary' : 'badge-secondary' }} badge-pill"
                                     style="width: 12px; height: 12px;"></div>
                            </div>

                            <div class="flex-grow-1">
                                {{-- Judul dan label "Baru" jika belum dibaca --}}
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="card-title mb-0 mr-2">{{ $item->judul }}</h5>
                                    @if(!$item->is_read)
                                        <span class="badge badge-warning badge-sm">Baru</span>
                                    @endif
                                </div>

                                {{-- Pesan --}}
                                <p class="card-text text-muted mb-2">
                                    {{ Str::limit($item->pesan, 100) }}
                                </p>
                                {{-- Timestamp --}}
                                <div class="d-flex align-items-center text-muted">
                                    <i class="ti-time mr-1"></i>
                                    <small title="{{ $item->created_at->format('l, d F Y \p\u\k\u\l H:i') }}">
                                        {{ $item->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi: Tandai Dibaca atau Hapus --}}
                        <div class="ml-3 d-flex align-items-center">
                            {{-- Tombol Detail --}}
                            <button type="button"
                                    class="btn btn-outline-info btn-sm mr-2 btn-detail"
                                    data-id="{{ $item->id_notifikasi }}">
                                <i class="ti-eye"></i>
                                <span class="d-none d-sm-inline ml-1">Detail</span>
                            </button>

                            {{-- Tandai Dibaca jika belum dibaca --}}
                            @if(!$item->is_read)
                                <form action="{{ route('notifikasi.markAsRead', $item->id_notifikasi) }}"
                                      method="POST"
                                      class="d-inline mr-2">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-outline-primary btn-sm"
                                            title="Tandai sebagai dibaca">
                                        <i class="ti-check"></i>
                                        <span class="d-none d-sm-inline ml-1">Tandai Dibaca</span>
                                    </button>
                                </form>
                            @endif

                            {{-- Hapus --}}
                            <form action="{{ route('notifikasi.destroy', $item->id_notifikasi) }}"
                                  method="POST"
                                  onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Hapus Notifikasi">
                                    <i class="ti-trash"></i>
                                    <span class="d-none d-sm-inline ml-1">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ti-bell text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-2">Tidak ada notifikasi</h4>
                    <p class="text-muted">
                        Anda akan menerima notifikasi di sini ketika ada pembaruan status laporan fasilitas.
                    </p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div id="modalContainer"></div>

<!-- Pagination (pastikan menyertakan query string filter) -->
@if($notifikasi->hasPages())
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        {{ $notifikasi->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Notifikasi toast (SweetAlert2) jika ada session pesan
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session('info') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    $('.btn-detail').on('click', function() {
        let id = $(this).data('id');
        let url = "{{ url('notifikasi') }}/" + id; // URL: /notifikasi/{id}

        // GET HTML modal dari server
        $.get(url, function(html) {
            // Tambahkan (atau replace) isi modalContainer dengan respons HTML
            $('#modalContainer').html(html);

            // Setelah HTML dimasukkan, tampilkan modal dengan ID yang tepat
            $('#modalDetail' + id).modal('show');
        });
    });
});
</script>

<style>
.card {
    transition: all 0.15s ease-in-out;
    border-radius: 8px;
}

.card:hover {
    transform: translateY(-2px);
}

.border-primary {
    border-left: 4px solid #007bff !important;
}

.badge-pill {
    border-radius: 50%;
    padding: 0;
    display: inline-block;
}

.badge-sm {
    font-size: 0.75rem;
    padding: 0.2rem 0.4rem;
}

.page-link {
    color: #007bff;
    border: 1px solid #dee2e6;
}

.page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endpush
