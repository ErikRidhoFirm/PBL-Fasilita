

<div class="modal-dialog modal-lg w-75">
  <div class="modal-content">
    {{-- Header Modal --}}
    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title">
        @php
          $s = $riwayat->status->nama_status;
          $titles = [
            'Valid'       => 'Detail Penilaian',
            'Tidak Valid' => 'Alasan Ketidak-validan',
            'Ditolak'     => 'Alasan Penolakan',
            'Ditugaskan'  => 'Detail Penugasan',
            'Selesai'     => 'Detail Perbaikan',
          ];
        @endphp
        <i class="fas fa-info-circle me-2"></i>{{ $titles[$s] ?? 'Detail' }}
      </h5>
      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>

    {{-- Body Modal --}}
    <div class="modal-body">
      @if(! $riwayat)
        <div class="alert alert-danger">
          <h5>
            <i class="fas fa-ban me-2"></i>Kesalahan!!!
          </h5>
          Data tidak ditemukan.
        </div>
        <a href="{{ url('/riwayat') }}" class="btn btn-warning">Kembali</a>
      @else
        {{-- Tabel Informasi Umum --}}
        <table class="table table-striped">
          <tbody>
            <tr>
              <th style="width: 30%;">Fasilitas</th>
              <td>{{ $riwayat->laporanFasilitas->fasilitas->nama_fasilitas }}</td>
            </tr>
            <tr>
              <th>Pelapor</th>
              <td>{{ $riwayat->laporanFasilitas->laporan->pengguna->nama }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>
                <span class="badge bg-{{
                  $s === 'Valid'         ? 'success'   :
                  ($s === 'Tidak Valid'  ? 'warning'   :
                  ($s === 'Ditolak'      ? 'danger'    :
                  ($s === 'Ditugaskan'   ? 'info'      :
                  ($s === 'Selesai'      ? 'primary'   : 'secondary'))))
                }} text-white">
                  {{ $s }}
                </span>
              </td>
            </tr>
            <tr>
              <th>Oleh</th>
              <td>
                {{ $riwayat->pengguna->nama }}
                (<em>{{ $riwayat->pengguna->peran->nama_peran }}</em>)
              </td>
            </tr>
            <tr>
              <th>Waktu</th>
              <td>{{ $riwayat->created_at->translatedFormat('d F Y H:i') }}</td>
            </tr>
            <tr>
              <th>Catatan</th>
              <td>
                {!! $riwayat->catatan
                    ? nl2br(e($riwayat->catatan))
                    : '<em>-</em>'
                !!}
              </td>
            </tr>
          </tbody>
        </table>

        {{-- Konten Khusus Berdasarkan Status --}}
        @switch($s)
          @case('Valid')
            <hr>
            <h6 class="mb-3">Nilai Mentah per Kriteria</h6>
            @php
              $penilaian = $riwayat->laporanFasilitas->penilaian->first();
            @endphp

            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th style="width: 5%;">No</th>
                  <th style="width: 20%;">Kode</th>
                  <th>Nama Kriteria</th>
                  <th class="text-center" style="width: 15%;">Nilai</th>
                </tr>
              </thead>
              <tbody>
                @if($penilaian && $penilaian->skorKriteriaLaporan->isNotEmpty())
                  @foreach($penilaian->skorKriteriaLaporan as $i => $sk)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ $sk->kriteria->kode_kriteria }}</td>
                      <td>{{ $sk->kriteria->nama_kriteria }}</td>
                      <td class="text-center">{{ $sk->nilai_mentah }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="4" class="text-center text-muted">
                      Belum ada penilaian tersimpan.
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          @break

          @case('Tidak Valid')
            <hr>
            <h6 class="mb-3">Alasan Ketidak-validan</h6>
            <div class="alert alert-warning">
              {!! $riwayat->catatan
                  ? nl2br(e($riwayat->catatan))
                  : '<em>Tidak ada keterangan tambahan.</em>'
              !!}
            </div>
          @break

          @case('Ditolak')
            <hr>
            <h6 class="mb-3">Alasan Penolakan</h6>
            <div class="alert alert-danger">
              {!! $riwayat->catatan
                  ? nl2br(e($riwayat->catatan))
                  : '<em>Tidak ada keterangan tambahan.</em>'
              !!}
            </div>
          @break

          @case('Ditugaskan')
            <hr>
            <h6 class="mb-3">Detail Penugasan</h6>
            @if($riwayat->laporanFasilitas->penugasan)
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <th style="width: 30%;">Teknisi</th>
                    <td>
                      {{ $riwayat->laporanFasilitas->penugasan->teknisi->nama }}
                    </td>
                  </tr>
                  <tr>
                    <th>Waktu Penugasan</th>
                    <td>
                      {{ optional($riwayat->laporanFasilitas->penugasan->created_at)
                         ->translatedFormat('d F Y H:i') }}
                    </td>
                  </tr>
                </tbody>
              </table>
            @else
              <p><em>Belum ada data teknisi atau penugasan.</em></p>
            @endif
          @break

          @case('Selesai')
            <hr>
            <h6 class="mb-3">Detail Perbaikan</h6>
            @php
              $penugasan = $riwayat->laporanFasilitas->penugasan;
              $perbaikan = optional($penugasan)->perbaikan;
            @endphp

            @if($penugasan && $perbaikan)
              <div class="mb-3">
                <img src="{{ asset('storage/' . $perbaikan->foto_perbaikan) }}"
                     alt="Foto Perbaikan"
                     class="img-fluid rounded shadow-sm">
              </div>
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <th style="width: 30%;">Tipe Perbaikan</th>
                    <td>{{ $perbaikan->jenis_perbaikan }}</td>
                  </tr>
                  <tr>
                    <th>Deskripsi Perbaikan</th>
                    <td>{{ $perbaikan->deskripsi_perbaikan }}</td>
                  </tr>
                  <tr>
                    <th>Waktu Selesai</th>
                    <td>
                      {{ optional($perbaikan->updated_at)
                         ->translatedFormat('d F Y H:i') }}
                    </td>
                  </tr>
                </tbody>
              </table>
            @else
              <p><em>Belum ada hasil perbaikan yang diunggah.</em></p>
            @endif
          @break

          @default
            <p><em>Tidak ada detail lebih lanjut untuk status ini.</em></p>
        @endswitch
      @endif
    </div>

    {{-- Footer Modal --}}
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
  </div>
</div>
