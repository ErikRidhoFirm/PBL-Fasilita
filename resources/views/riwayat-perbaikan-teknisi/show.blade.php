{{-- resources/views/riwayat-perbaikan-teknisi/show.blade.php --}}
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">

    {{-- Header --}}
    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title">
        <i class="fas fa-info-circle mr-2"></i>Detail Perbaikan Fasilitas
      </h5>
      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    {{-- Body --}}
    <div class="modal-body p-4 bg-light">
      @php
        // Relasi utama
        $lapFas = $perbaikan->penugasan->laporanFasilitas ?? null;
        $laporan = optional($lapFas)->laporan;
        $gedung   = optional($laporan)->gedung;
        $lantai   = optional($laporan)->lantai;
        $ruangan  = optional($laporan)->ruangan;
        $fasilitas= optional($lapFas)->fasilitas;
        $jenis    = optional($perbaikan)->jenis_perbaikan;
        $gambar   = optional($perbaikan)->foto_perbaikan;
        $status   = optional(optional($lapFas)->status)->nama_status;
        $waktu    = optional($perbaikan)->updated_at?->format('d M Y, H:i') ?? '-';
        $deskripsi= optional($perbaikan)->deskripsi_perbaikan;
      @endphp

      {{-- Baris Informasi Lokasi + Fasilitas --}}
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-uppercase text-secondary mb-3">
                <i class="fas fa-map-marked-alt mr-1"></i>Lokasi & Fasilitas
              </h6>
              <div class="mb-3">
                <strong>Gedung:</strong> {{ $gedung->nama_gedung ?? '-' }}
              </div>
              <div class="mb-3">
                <strong>Lantai:</strong> {{ $lantai->nama_lantai ?? '-' }}
              </div>
              <div class="mb-3">
                <strong>Ruangan:</strong> {{ $ruangan->nama_ruangan ?? '-' }}
              </div>
              <div class="mb-3">
                <strong>Fasilitas:</strong> {{ $fasilitas->nama_fasilitas ?? '-' }}
              </div>
            </div>
          </div>
        </div>

        {{-- Kolom Kanan: Detail Perbaikan --}}
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-uppercase text-secondary mb-3">
                <i class="fas fa-tools mr-1"></i>Perbaikan Fasilitas
              </h6>
              <div class="mb-3">
                <strong>Jenis Perbaikan:</strong> {{ $jenis ?? '-' }}
              </div>
              <div class="mb-3">
                <strong>Status:</strong>
                @php $lower = strtolower($status); @endphp
                @if($lower === 'selesai' || $lower === 'valid')
                  <span class="badge badge-success">{{ $status }}</span>
                @elseif($lower === 'menunggu' || $lower === 'pending')
                  <span class="badge badge-warning">{{ $status }}</span>
                @else
                  <span class="badge badge-secondary">{{ $status ?? '-' }}</span>
                @endif
              </div>
              <div class="mb-3">
                <strong>Waktu Perbaikan:</strong> {{ $waktu }}
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Baris Gambar Perbaikan --}}
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body p-0">
              <h6 class="text-uppercase text-secondary p-3">
                <i class="fas fa-image mr-1"></i>Gambar Perbaikan
              </h6>
              @if($gambar)
                <img src="{{ asset('storage/foto_perbaikan/'.$gambar) }}"
                     alt="Gambar Perbaikan"
                     class="img-fluid w-100 rounded-bottom">
              @else
                <div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 250px;">
                  <i class="fas fa-tools fa-3x"></i>
                  <span class="ml-2">Tidak ada gambar perbaikan</span>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- Baris Deskripsi Perbaikan --}}
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-uppercase text-secondary mb-3">
                <i class="fas fa-align-left mr-1"></i>Deskripsi Perbaikan
              </h6>
              <p class="mb-0">{{ $deskripsi ?? '-' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- End modal-body --}}

    {{-- Footer Modal --}}
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>

  </div> {{-- End modal-content --}}
</div> {{-- End modal-dialog --}}
