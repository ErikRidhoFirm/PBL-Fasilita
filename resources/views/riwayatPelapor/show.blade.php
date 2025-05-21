@extends('layouts.main')

@section('content')
<div class="container-fluid">

  {{-- 1) Link “Back” --}}
  <div class="mb-3">
    <a href="{{ route('riwayatPelapor.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center">
      <i class="mdi mdi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  {{-- Main Card for Laporan Detail --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light py-3">
        {{-- Page Title --}}
        <h1 class="h4 mb-0 fw-bold">Detail Laporan</h1>
    </div>
    <div class="card-body">
        {{-- Banner Foto Kerusakan --}}
        @if($lf->path_foto)
        <div class="mb-4 text-center">
            <a href="{{ $lf->path_foto ? asset('uploads/laporan/'. $lf->path_foto) : asset('storage/'.$lf->path_foto) }}"
               data-bs-toggle="tooltip"
               data-bs-placement="bottom"
               title="Lihat gambar penuh"
               target="_blank"> <img
                    src="{{ $lf->path_foto ? asset('uploads/laporan/'. $lf->path_foto) : asset('storage/'.$lf->path_foto) }}"
                    alt="Foto Kerusakan: {{ !empty($lf->deskripsi) ? Str::limit($lf->deskripsi, 50) : 'Laporan Fasilitas' }}"
                    class="img-fluid rounded-3 mw-100 shadow"
                    style="max-height: 450px; object-fit: contain; border: 1px solid #dee2e6;">
            </a>
        </div>
        @else
        <div class="mb-4 p-4 bg-light-subtle rounded-3 text-center text-muted border">
            <i class="mdi mdi-image-off-outline display-4"></i>
            <p class="mb-0 mt-2">Tidak ada foto kerusakan yang diunggah.</p>
        </div>
        @endif

        {{-- Deskripsi --}}
        <div class="mb-4">
            <h5 class="fw-bold mb-2 pb-2 border-bottom">
                <i class="mdi mdi-text-box-outline me-2"></i>Deskripsi Kerusakan
            </h5>
            <p class="text-dark lh-lg" style="white-space: pre-wrap;">{{ $lf->deskripsi ?: 'Tidak ada deskripsi yang diberikan.' }}</p>
        </div>
    </div>
  </div> {{-- End of Main Card --}}


  {{-- Detail Tempat Card --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0 fw-bold"><i class="mdi mdi-map-marker-radius-outline me-2"></i>Detail Lokasi & Informasi Tambahan</h5>
    </div>
    <div class="card-body">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            {{-- Gedung --}}
            <div class="col">
                <div class="d-flex align-items-start p-3 bg-light-subtle rounded-3 shadow-sm h-100 border-start border-primary border-4">
                    <i class="mdi mdi-office-building-outline fs-2 text-primary me-3 mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Gedung</small>
                        <div class="fw-semibold fs-6">{{ $lf->fasilitas->ruangan->lantai->gedung->nama_gedung }}</div>
                    </div>
                </div>
            </div>

            {{-- Lantai --}}
            <div class="col">
                <div class="d-flex align-items-start p-3 bg-light-subtle rounded-3 shadow-sm h-100 border-start border-info border-4">
                    <i class="mdi mdi-layers-triple-outline fs-2 text-info me-3 mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Lantai</small>
                        <div class="fw-semibold fs-6">{{ $lf->fasilitas->ruangan->lantai->nomor_lantai }}</div>
                    </div>
                </div>
            </div>

            {{-- Ruangan --}}
            <div class="col">
                <div class="d-flex align-items-start p-3 bg-light-subtle rounded-3 shadow-sm h-100 border-start border-success border-4">
                    <i class="mdi mdi-door-closed-lock fs-2 text-success me-3 mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Ruangan</small>
                        <div class="fw-semibold fs-6">{{ $lf->fasilitas->ruangan->kode_ruangan }}</div>
                    </div>
                </div>
            </div>

            {{-- Fasilitas --}}
            <div class="col">
                <div class="d-flex align-items-start p-3 bg-light-subtle rounded-3 shadow-sm h-100 border-start border-warning border-4">
                    <i class="mdi mdi-archive-wrench-outline fs-2 text-warning me-3 mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Fasilitas Dilaporkan</small>
                        <div class="fw-semibold fs-6">{{ $lf->fasilitas->nama_fasilitas }}</div>
                    </div>
                </div>
            </div>

            {{-- Kategori Kerusakan --}}
            <div class="col">
                <div class="d-flex align-items-start p-3 bg-light-subtle rounded-3 shadow-sm h-100 border-start border-danger border-4">
                    <i class="mdi mdi-alert-decagram-outline fs-2 text-danger me-3 mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Kategori Kerusakan</small>
                        <div class="fw-semibold fs-6">
                            {{ optional($lf->kategoriKerusakan)->nama_kategori ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jumlah Rusak --}}
            <div class="col">
                <div class="d-flex align-items-start p-3 bg-light-subtle rounded-3 shadow-sm h-100 border-start border-secondary border-4">
                    <i class="mdi mdi-counter fs-2 text-secondary me-3 mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Perkiraan Jumlah Rusak</small>
                        <div class="fw-semibold fs-6">{{ $lf->jumlah_rusak }} {{ $lf->satuan_jumlah ?? 'unit' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div> {{-- End of Detail Tempat Card --}}


  {{-- Riwayat Status Card --}}
  <div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0 fw-bold"><i class="mdi mdi-timeline-text-outline me-2"></i>Riwayat Status Laporan</h5>
    </div>
    <div class="card-body">
        @if($lf->riwayatLaporanFasilitas->isNotEmpty())
        <ul class="list-unstyled mb-0">
            @foreach($lf->riwayatLaporanFasilitas->sortBy('created_at') as $index => $r)
            <li class="d-flex mb-3 @if(!$loop->last) pb-3 border-bottom @endif">
                <div class="me-3 pt-1">
                    <span class="badge p-2 fs-caption
                        @switch($r->status->nama_status)
                            @case('Menunggu') bg-warning text-dark @break
                            @case('Selesai') bg-success text-light @break
                            @case('Valid') bg-primary text-light @break
                            @case('Tidak Valid') bg-warning text-dark @break
                            @case('Ditolak') bg-danger text-light @break
                            @case('Ditugaskan') bg-info text-light @break
                            @case('Ditutup') bg-secondary @break
                            @default bg-secondary @endswitch
                    ">
                        <i class="mdi
                            @switch($r->status->nama_status)
                                @case('Menunggu') mdi-clock-outline @break
                                @case('Selesai') mdi-check-circle @break
                                @case('Valid') mdi-check-decagram @break
                                @case('Tidak Valid') mdi-alert-circle @break
                                @case('Ditolak') mdi-close-circle @break
                                @case('Ditugaskan') mdi-account-arrow-right @break
                                @case('Ditutup') mdi-lock @break
                                @default mdi-information @endswitch
                        me-1"></i>
                        {{ $r->status->nama_status }}
                    </span>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-1 fw-semibold text-dark">{{ $r->catatan ?: 'Tidak ada catatan tambahan.' }}</p>
                    <small class="text-muted">
                        Oleh: <strong>{{ optional($r->pengguna)->nama ?? 'Sistem' }}</strong>
                        <span class="mx-1">&bull;</span>
                        {{ $r->created_at->isoFormat('dddd, D MMMM YYYY \p\u\k\u\l HH:mm') }}
                        ({{ $r->created_at->diffForHumans() }})
                    </small>
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <div class="text-center text-muted p-3">
            <i class="mdi mdi-information-off-outline fs-1"></i>
            <p class="mb-0 mt-2">Belum ada riwayat status untuk laporan ini.</p>
        </div>
        @endif
    </div>
  </div> {{-- End of Riwayat Status Card --}}

</div>

@endsection

@push('js')
<script>
// Initialize Bootstrap Tooltips
document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
});
</script>
@endpush
