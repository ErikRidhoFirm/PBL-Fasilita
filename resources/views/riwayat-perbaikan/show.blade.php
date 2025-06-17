{{-- resources/views/riwayat-perbaikan/show.blade.php --}}
<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">

    {{-- Header --}}
    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title">
        <i class="fas fa-history mr-2"></i>Detail Riwayat Fasilitas
      </h5>
      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    {{-- Body --}}
    <div class="modal-body p-4 bg-light">

      @php
        // Ambil relasi laporanFasilitas, bisa null jika data belum lengkap
        $lapFas = $perbaikan->penugasan->laporanFasilitas ?? null;
        // Ambil objek fasilitas, bisa null
        $fas    = optional($lapFas)->fasilitas;
        $fotoF  = optional($lapFas)->path_foto;
        $namaF  = optional($fas)->nama_fasilitas ?? '-';
      @endphp

      {{-- Baris Utama: Foto Fasilitas + Informasi Lokasi/Fasilitas --}}
      <div class="row mb-4">
        {{-- Kolom Kiri: Foto Fasilitas --}}
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body p-0">
              @if($fotoF)
                <img src="{{ asset('storage/'.$fotoF) }}"
                     alt="Foto Fasilitas {{ $namaF }}"
                     class="img-fluid rounded-top">
              @else
                <div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 250px;">
                  <i class="fas fa-image fa-3x"></i>
                  <span class="ml-2">Foto Fasilitas</span>
                </div>
              @endif

              <div class="p-3 text-center">
                <h6 class="mb-0 font-weight-bold">Foto Fasilitas</h6>
                <small class="text-muted">{{ $namaF }}</small>
              </div>
            </div>
          </div>
        </div>

        {{-- Kolom Kanan: Informasi Lokasi + Fasilitas --}}
        <div class="col-md-8">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-uppercase text-secondary mb-3">
                <i class="fas fa-info-circle mr-1"></i>Informasi Laporan
              </h6>
              <div class="row">
                {{-- Subkolom Kiri: Gedung, Lantai, Ruangan, Fasilitas --}}
                <div class="col-md-6">
                  {{-- Gedung --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-university fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Gedung:</div>
                      <div>{{ optional(optional($lapFas)->laporan->gedung)->nama_gedung ?? '-' }}</div>
                    </div>
                  </div>
                  {{-- Lantai --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-layer-group fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Lantai:</div>
                      <div>{{ optional(optional($lapFas)->laporan->lantai)->nomor_lantai ?? '-' }}</div>
                    </div>
                  </div>
                  {{-- Ruangan --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-door-closed fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Ruangan:</div>
                      <div>{{ optional(optional($lapFas)->laporan->ruangan)->nama_ruangan ?? '-' }}</div>
                    </div>
                  </div>
                  {{-- Fasilitas --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-boxes fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Fasilitas:</div>
                      <div>{{ $namaF }}</div>
                    </div>
                  </div>
                </div>

                {{-- Subkolom Kanan: Penugasan (Teknisi), Pelapor, Tanggal Pelaporan --}}
                <div class="col-md-6">
                  {{-- Penugasan (Teknisi) --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-user-cog fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Penugasan (Teknisi):</div>
                      <div>{{ optional($perbaikan->penugasan->teknisi)->nama ?? '-' }}</div>
                    </div>
                  </div>
                  {{-- Pelapor --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-user fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Pelapor:</div>
                      <div>{{ optional(optional($lapFas)->laporan->pengguna)->nama ?? '-' }}</div>
                    </div>
                  </div>
                  {{-- Tanggal Pelaporan --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-calendar-alt fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Tanggal Pelaporan:</div>
                      <div>{{ optional(optional($lapFas)->laporan)->created_at->format('d M Y, H:i') ?? '-' }}</div>
                    </div>
                  </div>
                </div>
              </div> {{-- End row --}}
            </div> {{-- End card-body --}}
          </div> {{-- End card --}}
        </div> {{-- End col-md-8 --}}
      </div> {{-- End baris utama --}}

      {{-- Baris Kedua: Gambar Perbaikan + Detail Perbaikan --}}
      <div class="row mb-4">
        {{-- Kolom Kiri (Gambar Perbaikan) --}}
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body p-0">
              @php $gambarPerbaikan = $perbaikan->foto_perbaikan; @endphp

              @if($gambarPerbaikan)
                <img src="{{ asset('storage/'.$gambarPerbaikan) }}"
                     alt="Gambar Perbaikan"
                     class="img-fluid rounded-top">
              @else
                <div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 250px;">
                  <i class="fas fa-tools fa-3x"></i>
                  <span class="ml-2">Gambar Perbaikan</span>
                </div>
              @endif

              <div class="p-3 text-center">
                <h6 class="mb-0 font-weight-bold">Gambar Perbaikan</h6>
              </div>
            </div>
          </div>
        </div>

        {{-- Kolom Kanan (Detail Perbaikan: Deskripsi, Waktu, Status, Jenis) --}}
        <div class="col-md-8">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-uppercase text-secondary mb-3">
                <i class="fas fa-wrench mr-1"></i>Detail Perbaikan
              </h6>
              <div class="row">
                {{-- Subkolom Kiri --}}
                <div class="col-md-6">
                  {{-- Deskripsi Perbaikan --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-align-left fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Deskripsi Perbaikan:</div>
                      <div>{{ optional($perbaikan)->deskripsi_perbaikan ?? '-' }}</div>
                    </div>
                  </div>
                  {{-- Waktu Perbaikan (Selesai) --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-clock fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Waktu Perbaikan:</div>
                      <div>{{ optional($perbaikan)->updated_at->format('d M Y, H:i') ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                {{-- Subkolom Kanan --}}
                <div class="col-md-6">
                  {{-- Status --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-info fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Status:</div>
                      @php
                        $stat = optional(optional($lapFas)->status)->nama_status ?? '-';
                        $lower = strtolower($stat);
                      @endphp
                      @if($lower === 'selesai' || $lower === 'valid')
                        <span class="badge badge-success">{{ $stat }}</span>
                      @elseif($lower === 'menunggu' || $lower === 'pending')
                        <span class="badge badge-warning">{{ $stat }}</span>
                      @else
                        <span class="badge badge-secondary">{{ $stat }}</span>
                      @endif
                    </div>
                  </div>
                  {{-- Jenis Perbaikan --}}
                  <div class="d-flex mb-3">
                    <div class="pr-2"><i class="fas fa-cogs fa-lg text-primary"></i></div>
                    <div>
                      <div class="font-weight-bold">Jenis Perbaikan:</div>
                      <div class="text-capitalize">{{ optional($perbaikan)->jenis_perbaikan ?? '-' }}</div>
                    </div>
                  </div>
                </div>
              </div> {{-- End row --}}
            </div> {{-- End card-body --}}
          </div> {{-- End card --}}
        </div> {{-- End col-md-8 --}}
      </div> {{-- End baris kedua --}}

      {{-- Riwayat Perubahan Status --}}
      <div class="mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-uppercase text-secondary mb-3">
              <i class="fas fa-history mr-1"></i>Riwayat Perubahan Status
            </h6>
            <div class="table-responsive">
              <table class="table table-hover table-sm">
                <thead class="thead-light">
                  <tr>
                    <th style="width:5%;">#</th>
                    <th>Status</th>
                    <th>Modifikator</th>
                    <th>Peran</th>
                    <th>Waktu</th>
                    <th>Durasi</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $riwayatList = optional($lapFas)->riwayatLaporanFasilitas()
                                      ->with(['status','pengguna.peran'])
                                      ->orderBy('created_at')
                                      ->get() ?? collect();
                  @endphp

                  @forelse($riwayatList as $idx => $riw)
                    @php
                      $statVal  = optional($riw->status)->nama_status ?? '-';
                      $modif    = optional($riw->pengguna)->nama ?? '-';
                      $peranMod = optional($riw->pengguna->peran)->nama_peran ?? '-';
                      $waktu    = $riw->created_at->format('d M Y, H:i');
                      if ($idx > 0) {
                        $prev   = $riwayatList[$idx - 1]->created_at;
                        $diff   = $riw->created_at->diff($prev);
                        $durasi = $diff->d . 'd ' . $diff->h . 'j';
                      } else {
                        $durasi = '-';
                      }
                    @endphp

                    <tr class="@if($idx % 2 == 0) bg-white @else bg-light @endif">
                      <td>{{ $idx + 1 }}</td>
                      <td>
                        @php $lowerStat = strtolower($statVal); @endphp
                        @if($lowerStat === 'selesai' || $lowerStat === 'valid')
                          <span class="badge badge-success">{{ $statVal }}</span>
                        @elseif($lowerStat === 'menunggu' || $lowerStat === 'pending')
                          <span class="badge badge-warning">{{ $statVal }}</span>
                        @else
                          <span class="badge badge-secondary">{{ $statVal }}</span>
                        @endif
                      </td>
                      <td>{{ $modif }}</td>
                      <td>{{ $peranMod }}</td>
                      <td>{{ $waktu }}</td>
                      <td>{{ $durasi }}</td>
                      <td class="text-center">
                        {{-- Karena belum ada fitur penilaian, cukup tampilkan tanda dash --}}
                        <span class="text-muted">—</span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center text-muted py-3">
                        Belum ada riwayat perubahan status.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div> {{-- End card-body --}}
        </div> {{-- End card --}}
      </div> {{-- End mb-4 --}}

    </div> {{-- End modal-body --}}

    {{-- Footer Modal --}}
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>

  </div> {{-- End modal-content --}}
</div> {{-- End modal-dialog --}}
