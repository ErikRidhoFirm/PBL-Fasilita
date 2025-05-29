@extends('layouts.main')

@section('content')
<div class="container-fluid">

    {{-- 1) BARIS RINGKASAN STATUS --}}
    <div class="row gx-3 gy-4 mb-4">
        <div class="col-12">
            <h4 class="mb-0">Ringkasan Status Pelaporan</h4>
            <hr class="mt-2">
        </div>
        @foreach($counts as $status => $cnt)
            @php
                // Mapping status → [mdi-icon, border-color, text-color]
               $styles = [
                    'Menunggu'    => ['mdi-history',                         'border-warning',  'text-warning'],   // tetap
                    'Tidak Valid' => ['mdi-file-cancel-outline',             'border-danger',   'text-danger'],    // seperti 'Laporan Ditolak'
                    'Ditolak'     => ['mdi-file-cancel-outline',             'border-danger',   'text-danger'],    // sama dengan 'Tidak Valid'
                    'Valid'       => ['mdi-checkbox-marked-circle-outline',  'border-success',  'text-success'],   // seperti 'Aktivasi Laporan'
                    'Ditugaskan'  => ['mdi-worker',                'border-info',     'text-info'],      // tetap, sudah cocok
                    'Selesai'     => ['mdi-check-all',                       'border-success',  'text-success'],   // tetap
                ];

                // Fallback jika ada status baru
                $m = $styles[$status] ?? ['mdi-file-outline', 'border-secondary', 'text-secondary'];
            @endphp

            <div class="col-6 col-md-4 col-lg-3 col-xl-2"> {{-- Adjusted columns for better fit --}}
                <div class="card shadow-sm rounded-3 h-100 border-start {{ $m[1] }}" style="border-width: 4px !important;">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                        <div>
                            <h6 class="card-title text-muted mb-1">{{ $status }}</h6>
                            <span class="h4 mb-0 fw-bold">{{ $cnt }}</span>
                        </div>
                        <i class="mdi {{ $m[0] }} {{ $m[2] }} fs-1 opacity-50"></i> {{-- Used fs-1 and opacity --}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 2) TABEL RIWAYAT --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom"> {{-- Added Card Header --}}
            <h5 class="card-title mb-0">Daftar Riwayat Pelaporan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="riwayat-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Fasilitas</th>
                            <th>Gedung</th>
                            <th>Ruangan</th>
                            <th>Status</th>
                            <th style="width:10%" class="text-center">Aksi</th> {{-- Centered Aksi Header --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $item)
                            @php
                                $fac    = $item->fasilitas;
                                $room   = $fac->ruangan;
                                $lantai = $room->lantai;
                                $ged    = $lantai->gedung;

                                // Badge mapping remains the same
                                $badge = match($item->status->nama_status){
                                    'Menunggu' => 'bg-warning text-dark',
                                    'Tidak Valid', 'Ditolak' => 'bg-danger text-light',
                                    'Valid', 'Selesai' => 'bg-success text-light',
                                    'Ditugaskan' => 'bg-info text-light',
                                    default => 'bg-secondary text-light',
                                };
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td> {{-- Centered No --}}
                                <td>{{ $fac->nama_fasilitas }}</td>
                                <td>{{ $ged->nama_gedung }}</td>
                                <td>{{ $room->kode_ruangan }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $badge }}"> {{-- Used rounded-pill badge --}}
                                        {{ $item->status->nama_status }}
                                    </span>
                                </td>
                                <td class="text-center"> {{-- Centered Aksi Buttons --}}
                                    {{-- Tombol Edit --}}
                                    @if(in_array($item->status->id_status, [\App\Models\Status::MENUNGGU, \App\Models\Status::TIDAK_VALID]))
                                        <button onclick="modalAction('{{ route('riwayatPelapor.edit', $item->id_laporan_fasilitas) }}')"
                                                class="btn btn-sm btn-outline-warning me-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Laporan">
                                            <i class="mdi mdi-pencil-box-outline"></i>
                                        </button>
                                    @endif
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('riwayatPelapor.show', $item->id_laporan_fasilitas) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                        <i class="mdi mdi-file-document-box"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-5"> {{-- Enhanced empty message --}}
                                    <i class="mdi mdi-information-outline mdi-24px text-muted mb-2 d-block"></i>
                                    <span class="text-muted">Belum ada riwayat pelaporan yang dapat ditampilkan.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <!-- Modal content will be loaded here -->
  </div>
</div>
@endsection

@push('js')
<script>
  function modalAction(url) {
    $('#myModal .modal-dialog').load(url, function() {
      $('#myModal').modal('show');
    });
  }
</script>
@endpush
