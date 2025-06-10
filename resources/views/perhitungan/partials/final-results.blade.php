{{-- resources/views/perhitungan/partials/final-results.blade.php --}}

{{-- Hasil Akhir Peringkat --}}
<h5 class="mt-5 text-primary"><i class="fas fa-award mr-2"></i> Hasil Akhir Peringkat Prioritas Perbaikan</h5>
<p class="card-description">
  Alternatif diurutkan berdasarkan skor preferensi (C<sub>i</sub>) tertinggi, menunjukkan prioritas perbaikan tertinggi.
</p>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead class="thead-dark">
      <tr>
        <th class="text-center">Peringkat</th>
        <th>Alternatif (Fasilitas & Pelapor)</th>
        <th class="text-center">Skor Preferensi (C<sub>i</sub>)</th>
      </tr>
    </thead>
    <tbody>
      @php
        $result = collect($alternatifs)->map(function($alt, $index) use ($Ci, $distPos, $distNeg) {
            return [
                'alt'   => $alt,
                'skor'  => $Ci[$alt->id_laporan_fasilitas] ?? 0,
                'd_pos' => $distPos[$index] ?? 0,
                'd_neg' => $distNeg[$index] ?? 0
            ];
        })->sortByDesc('skor')->values();
      @endphp

      @foreach($result as $idx => $row)
        <tr class="{{ $idx === 0 ? 'table-primary' : '' }}">
          <td class="text-center"><strong>{{ $idx + 1 }}</strong></td>
          <td>
            <strong>{{ $row['alt']->fasilitas->nama_fasilitas }}</strong>
            <br>
            <small class="text-muted">Pelapor: {{ $row['alt']->laporan->pengguna->nama }}</small>
            <br>
            <small><em>
              (D<sup>+</sup>: {{ number_format($row['d_pos'], 4) }},
              D<sup>-</sup>: {{ number_format($row['d_neg'], 4) }})
            </em></small>
          </td>
          <td class="text-center"><strong>{{ number_format($row['skor'], 4) }}</strong></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
