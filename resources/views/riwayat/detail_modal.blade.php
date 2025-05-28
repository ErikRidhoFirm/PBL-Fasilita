<div class="modal-dialog modal-lg">
  <div class="modal-content">
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
      <button type="button" class="btn-close" data-dismiss="modal"></button>
    </div>

    <div class="modal-body">
      {{-- Common header --}}
      <p><strong>Fasilitas:</strong> {{ $riwayat->laporanFasilitas->fasilitas->nama_fasilitas }}</p>
      <p><strong>Pelapor:</strong>  {{ $riwayat->laporanFasilitas->laporan->pengguna->nama }}</p>
      <p><strong>Status:</strong>   {{ $s }}</p>
      <p><strong>Oleh:</strong>     {{ $riwayat->pengguna->nama }} ({{ $riwayat->pengguna->peran->nama_peran }})</p>
      <p><strong>Waktu:</strong>    {{ $riwayat->created_at->translatedFormat('d M Y H:i') }}</p>
      <p><strong>Catatan:</strong>  {!! $riwayat->catatan ? nl2br(e($riwayat->catatan)) : '<em>-</em>' !!}</p>

      {{-- Now branch by status --}}
      @switch($s)
        @case('Valid')
            <hr>
            <h6>Nilai Mentah per Kriteria</h6>
            @php
                $penilaian = $riwayat
                ->laporanFasilitas
                ->penilaian
                ->first();
            @endphp

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th class="text-center">Nilai</th>
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
                    <td colspan="4" class="text-center text-muted">Belum ada penilaian tersimpan.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        @break

        @case('Tidak Valid')
        @case('Ditolak')
          
          @break

        @case('Ditugaskan')
          <hr>
          <h6>Ditugaskan ke Teknisi</h6>
          @if($riwayat->laporanFasilitas->penugasan)
            <p><strong>Teknisi:</strong>
               {{ $riwayat->laporanFasilitas->penugasan->teknisi->nama }}</p>
          @else
            <p><em> Belum ada data teknisi </em></p>
          @endif
          @break

        @case('Selesai')
          <hr>
          <h6>Hasil Perbaikan</h6>
            <img src="{{ asset('storage/' . $riwayat->laporanFasilitas->penugasan->perbaikan->foto_perbaikan) }}"
                 class="img-fluid mb-3" alt="Foto Perbaikan">
            <p><strong>Tipe Perbaikan:</strong> {{ $riwayat->laporanFasilitas->penugasan->perbaikan->jenis_perbaikan }}</p>
            <p><strong>Deskripsi Perbaikan:</strong> {{ $riwayat->laporanFasilitas->penugasan->perbaikan->deskripsi_perbaikan }}</p>
          @break
        @default
          <p><em>Tidak ada detail lebih lanjut.</em></p>
      @endswitch
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
  </div>
</div>
