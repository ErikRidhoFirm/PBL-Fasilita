@extends('layouts.main')

 @section('content')
 @php
  use Carbon\Carbon;
  // Atur locale Carbon ke Bahasa Indonesia jika belum di AppServiceProvider
  Carbon::setLocale('id');
 @endphp

 <div class="card mb-4 shadow-sm">
  <div class="card-header bg-primary text-white d-flex align-items-center">
  <i class="fas fa-history fa-fw me-3"></i>
  <h5 class="mb-0">Detail Riwayat Fasilitas</h5>
  </div>
  <div class="card-body p-4">

   <div class="row g-4 mb-4">
   <div class="col-md-4 col-lg-3">
   <div class="card h-100 shadow-sm border-light">
    <img src="{{ asset('storage/' . $lapfas->path_foto) }}"
      class="card-img-top img-fluid rounded-top" alt="Foto Fasilitas: {{ $lapfas->fasilitas->nama_fasilitas }}"
      style="height: 200px; object-fit: cover;">
    <div class="card-footer text-center bg-light">
    <small class="text-muted">Foto Fasilitas</small>
    </div>
   </div>
   </div>

   <div class="col-md-8 col-lg-9">
   <div class="card h-100 shadow-sm border-light">
    <div class="card-header bg-light">
    <h6 class="mb-0"><i class="fas fa-info-circle fa-fw me-2"></i>Informasi Laporan</h6>
    </div>
    <div class="card-body">
    <div class="row">
     <div class="col-lg-6">
     <p class="mb-3">
      <strong class="d-block"><i class="fas fa-warehouse fa-fw me-2"></i>Fasilitas:</strong>
      {{ $lapfas->fasilitas->nama_fasilitas }}
     </p>
     <p class="mb-3">
      <strong class="d-block"><i class="fas fa-tools fa-fw me-2"></i>Tingkat Kerusakan:</strong>
      {{ $lapfas->tingkatKerusakan->parameter }}
     </p>
     <p class="mb-3 mb-lg-0">
      <strong class="d-block"><i class="fas fa-hashtag fa-fw me-2"></i>Dampak bagi Pengguna:</strong>
      {{ $lapfas->dampakPengguna->parameter }} 
     </p>
     </div>
     <div class="col-lg-6">
     <p class="mb-3">
      <strong class="d-block"><i class="fas fa-user fa-fw me-2"></i>Pelapor:</strong>
      {{ $lapfas->laporan->pengguna->nama }}
     </p>
     <p class="mb-3">
      <strong class="d-block"><i class="fas fa-map-marker-alt fa-fw me-2"></i>Lokasi:</strong>
      {{ $lapfas->laporan->gedung->nama_gedung }} &raquo;
      Lantai {{ $lapfas->laporan->lantai->nomor_lantai }} &raquo;
      {{ $lapfas->laporan->ruangan->nama_ruangan }}
     </p>
     <p class="mb-0">
      <strong class="d-block"><i class="far fa-clock fa-fw me-2"></i>Tanggal Pelaporan:</strong>
      {{ $lapfas->created_at->translatedFormat('d F Y H:i') }}
     </p>
     </div>
    </div>
    </div>
   </div>
   </div>
  </div>

   <div class="card mb-4 shadow-sm border-light">
   <div class="card-header bg-light">
   <h6 class="mb-0"><i class="fas fa-align-left fa-fw me-2"></i>Deskripsi Kerusakan</h6>
   </div>
   <div class="card-body">
   <p class="mb-0">{{ $lapfas->deskripsi ?: 'Tidak ada deskripsi yang diberikan.' }}</p>
   </div>
  </div>

   <div class="card shadow-sm border-light">
   <div class="card-header bg-light">
   <h6 class="mb-0"><i class="fas fa-list-ol fa-fw me-2"></i>Riwayat Perubahan Status</h6>
   </div>
   <div class="card-body p-0">
   <div class="table-responsive">
    <table class="table table-striped table-hover mb-0 align-middle">
    <thead class="table-light">
     <tr>
     <th style="width: 5%;" class="text-center">#</th>
     <th style="width: 15%;">Status</th>
     <th style="width: 20%;">Modifikator</th>
     <th style="width: 15%;">Peran</th>
     <th style="width: 18%;">Waktu</th>
     <th>Durasi</th>
     <th style="width: 18%;" class="text-center">Aksi</th>
     </tr>
    </thead>
    <tbody>
     @php
     // Cari waktu Valid dan Ditugaskan SEKALI sebelum loop
     $t_valid = optional($riwayats->firstWhere(fn($r) => $r->status->nama_status === 'Valid'))->created_at;
     $t_tug = optional($riwayats->firstWhere(fn($r) => $r->status->nama_status === 'Ditugaskan'))->created_at;
     @endphp

     @forelse($riwayats as $item)
     @php
      $now = $item->created_at; // Waktu status saat ini
      $from = null; // Inisialisasi waktu 'dari'

      switch($item->status->nama_status) {
       case 'Valid':
       case 'Ditolak':
       case 'Tidak Valid':
        $from = $lapfas->created_at; // Dari waktu Laporan dibuat
        break;
       case 'Ditugaskan':
        // Dari waktu Valid, atau jika tidak ada, dari waktu Laporan dibuat
        $from = $t_valid ?: $lapfas->created_at;
        break;
       case 'Selesai':
        // Dari waktu Ditugaskan, atau jika tidak ada, dari Valid, atau dari Laporan dibuat
        $from = $t_tug ?: ($t_valid ?: $lapfas->created_at);
        break;
      }

      // Hitung durasi jika $from ditemukan
      $durasi = $from && $now
       ? Carbon::parse($from)->diffForHumans($now, [
         'parts' => 3, // Tampilkan hingga 3 bagian (misal: 1h 5m 10d)
         'short' => true, // Gunakan singkatan (h, m, d)
         'syntax' => Carbon::DIFF_ABSOLUTE, // Tampilkan tanpa "sebelum/sesudah"
         'join' => ', ' // Gabung dengan koma
        ])
       : '-';
     @endphp
     <tr>
      <td class="text-center">{{ $loop->iteration }}</td>
      <td>
      <span class="badge fs-6 px-2 py-1
       @switch($item->status->nama_status)
       @case('Valid') bg-primary text-light @break
       @case('Tidak Valid') bg-warning text-light @break
       @case('Selesai') bg-success text-light @break
       @case('Ditolak') bg-danger text-light @break
       @case('Ditugaskan') bg-info text-light @break
       @default bg-secondary text-light @break
      @endswitch">
       {{ $item->status->nama_status }}
      </span>
      </td>
      <td>{{ $item->pengguna->nama }}</td>
      <td>{{ $item->pengguna->peran->nama_peran }}</td>
      <td>{{ $item->created_at->translatedFormat('d M Y H:i') }}</td>
      <td>{{ $durasi }}</td>
      <td class="text-center">
        @php
            // map status → label
            $btn = match($item->status->nama_status) {
            'Valid'       => ['Penilaian', 'primary'],
            'Tidak Valid' => ['Ketidak-validan', 'warning'],
            'Ditolak'     => ['Penolakan', 'danger'],
            'Ditugaskan'  => ['Penugasan', 'info'],
            'Selesai'     => ['Perbaikan', 'success'],
            default       => null
            };
        @endphp

        @if($btn)
            <a href="{{ route('riwayat.detailModal', $item->id_riwayat_laporan_fasilitas) }}"
                class="btn btn-sm btn-outline-{{ $btn[1] }} btn-detail"
                title="Lihat Detail {{ $btn[0] }}">
            <i class="fas fa-search-plus me-1"></i>{{ $btn[0] }}
            </a>
        @else
            <span class="text-muted">—</span>
        @endif
      </td>
     </tr>
     @empty
     <tr>
     <td colspan="7" class="text-center text-muted py-4">      <i class="fas fa-times-circle me-2"></i> Tidak ada riwayat perubahan status.
     </td>
     </tr>
     @endforelse
    </tbody>
   </table>
   </div>
  </div>
  </div>

      {{-- Feedback Pelapor (jika ada) --}}
    @php
        // Normalize penilaianPengguna: jika collection ambil first()
        $feedback = $lapfas->penilaianPengguna;
        if ($feedback instanceof \Illuminate\Support\Collection) {
            $feedback = $feedback->first();
        }
    @endphp

    @if($feedback)
        <div class="card shadow-sm my-4">
            <div class="card-header bg-light">
            <h6 class="mb-0"><i class="mdi mdi-comment-check-outline me-2"></i>Feedback Pelapor</h6>
            </div>
            <div class="card-body">
            <div class="mb-2">
                {{-- Stars --}}
                @for($i = 1; $i <= 5; $i++)
                @if($i <= ($feedback->nilai ?? 0))
                    <i class="mdi mdi-star fs-4 text-warning me-1"></i>
                @else
                    <i class="mdi mdi-star-outline fs-4 text-muted me-1"></i>
                @endif
                @endfor
            </div>
            <p class="text-muted">
                {{ $feedback->komentar ?: '- Tidak ada komentar -' }}
            </p>
            </div>
        </div>
    @endif

   <div class="text-center mt-4 pt-2">
   <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary">
   <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Riwayat
   </a>
  </div>

  </div> </div> {{-- Modal container --}}
 <div id="modalContainer" class="modal fade" tabindex="-1" aria-hidden="true"></div>
 @endsection

 @push('js')
 <script>
 $(function(){
  // Gunakan event delegation agar bekerja even jika tabel di-reload AJAX (meski tidak saat ini)
  $(document).on('click', '.btn-detail', function(e){
   e.preventDefault(); // Hentikan link default
   let url = $(this).attr('href'); // Ambil URL dari link

   // Kosongkan modal dulu (untuk mencegah konten lama tampil sekilas)
   $('#modalContainer').empty();

   // Muat konten dari URL ke dalam modal
   $('#modalContainer').load(url, function(response, status, xhr) {
      if (status == "error") {
          // Tangani jika gagal load modal
          console.error("Gagal memuat modal:", xhr.status, xhr.statusText);
          alert("Gagal memuat detail. Silakan coba lagi.");
      } else {
          // Jika berhasil, buat instance Bootstrap 5 modal dan tampilkan
          // Pastikan Bootstrap 5 JS sudah dimuat SEBELUM script ini!
          let modalEl = document.getElementById('modalContainer');
          let modalInstance = new bootstrap.Modal(modalEl);
          modalInstance.show();
      }
   });
  });

  // Optional: Bersihkan modal saat ditutup agar tidak ada sisa
  $('#modalContainer').on('hidden.bs.modal', function () {
      $(this).empty();
  });
 });
 </script>
 @endpush
