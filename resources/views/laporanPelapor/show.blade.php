{{-- resources/views/laporanPelapor/show.blade.php --}}

<div class="modal-dialog modal-xl modal-dialog-centered">
  <div class="modal-content">
    {{-- Header Modal --}}
    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title">
        <i class="mdi mdi-file-document-box-outline me-2"></i>
        Detail Laporan Fasilitas
      </h5>
      <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
    </div>

    {{-- Body Modal --}}
    <div class="modal-body p-4">
      <div class="row g-4">
        {{-- Kolom Gambar --}}
        <div class="col-lg-5">
          <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm">
            @if($laporanFasilitas->path_foto)
              <img src="{{ asset('storage/' . $laporanFasilitas->path_foto) }}"
                   alt="Foto {{ optional($laporanFasilitas->fasilitas)->nama_fasilitas }}"
                   class="img-fluid object-fit-cover">
            @else
              <img src="{{ asset('foto/default.jpg') }}"
                   alt="Tidak ada foto"
                   class="img-fluid object-fit-cover">
            @endif
          </div>
        </div>

        {{-- Kolom Detail --}}
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column">
              {{-- Judul / Nama Fasilitas --}}
              <h4 class="card-title mb-3">
                <i class="mdi mdi-seat-legroom-normal-outline me-1 text-secondary"></i>
                {{ optional($laporanFasilitas->fasilitas)->nama_fasilitas ?? '–' }}
              </h4>

              {{-- Status & Kategori Kerusakan --}}
              <div class="mb-4">
                <span class="badge bg-{{
                  $laporanFasilitas->status->nama_status === 'Terverifikasi' ? 'success' :
                  ($laporanFasilitas->status->nama_status === 'Ditolak' ? 'danger' : 'warning')
                }} me-2">
                  <i class="mdi mdi-information-outline me-1"></i>
                  {{ $laporanFasilitas->status->nama_status }}
                </span>

                <span class="badge bg-info text-white">
                  <i class="mdi mdi-alert-circle-outline me-1"></i>
                  {{ optional($laporanFasilitas->kategoriKerusakan)->nama_kerusakan ?? '–' }}
                </span>
              </div>

              {{-- Tabel Ringkasan Informasi --}}
              <dl class="row mb-0">
                <dt class="col-sm-4 text-muted">Gedung</dt>
                <dd class="col-sm-8 fw-semibold">
                  {{ optional($laporanFasilitas->laporan->gedung)->nama_gedung ?? '–' }}
                </dd>

                <dt class="col-sm-4 text-muted">Lantai</dt>
                <dd class="col-sm-8 fw-semibold">
                  {{ optional($laporanFasilitas->laporan->lantai)->nomor_lantai ?? '–' }}
                </dd>

                <dt class="col-sm-4 text-muted">Ruangan</dt>
                <dd class="col-sm-8 fw-semibold">
                  {{ optional($laporanFasilitas->laporan->ruangan)->nama_ruangan ?? '–' }}
                </dd>

                <dt class="col-sm-4 text-muted">Deskripsi Kerusakan</dt>
                <dd class="col-sm-8">
                  <p class="mb-0">{{ $laporanFasilitas->deskripsi ?? '-' }}</p>
                </dd>

                <dt class="col-sm-4 text-muted">Dilaporkan Oleh</dt>
                <dd class="col-sm-8">
                    @php
                        $user       = $laporanFasilitas->laporan->pengguna;
                        $fotoPath   = optional($user)->foto_profile
                                        ? 'storage/uploads/profiles/' . $user->foto_profile
                                        : 'foto/default.jpg';
                    @endphp

                    <img src="{{ asset($fotoPath) }}"
                        alt=""
                        class="rounded-circle me-2"
                        style="width:40px; height:40px; object-fit:cover;">
                  {{ optional($laporanFasilitas->laporan->pengguna)->nama ?? '–' }}
                  <br>
                </dd>

                <dt class="col-sm-4 text-muted">Tanggal Laporan</dt>
                <dd class="col-sm-8">
                  <i class="mdi mdi-calendar-range-outline me-1 text-secondary"></i>
                  {{ $laporanFasilitas->laporan->created_at->translatedFormat('d F Y H:i') }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Footer Modal --}}
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
        <i class="mdi mdi-close-circle-outline me-1"></i> Tutup
      </button>
    </div>
  </div>
</div>
