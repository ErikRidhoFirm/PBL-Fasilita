{{-- resources/views/perhitungan/partials/calculation-steps.blade.php --}}

{{-- 1) Matriks Keputusan Ternormalisasi --}}
<hr class="my-4">
<h6 class="mt-4">1) Matriks Keputusan Ternormalisasi (R)</h6>
<div class="table-responsive">
  <table class="table table-sm table-striped table-bordered mb-4">
    <thead class="thead-light">
      <tr>
        <th>Alternatif</th>
        @foreach($kriterias as $k)
          <th class="text-center">{{ $k->kode_kriteria }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($norm as $i => $row)
        <tr>
          <td>{{ $alternatifs[$i]->fasilitas->nama_fasilitas }}</td>
          @foreach($kriterias as $k)
            <td class="text-center">{{ number_format($row[$k->kode_kriteria], 4) }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{-- 2) Matriks Keputusan Ternormalisasi Terbobot --}}
<h6 class="mt-4">2) Matriks Keputusan Ternormalisasi Terbobot (V)</h6>
<div class="table-responsive">
  <table class="table table-sm table-striped table-bordered mb-4">
    <thead class="thead-light">
      <tr>
        <th>Alternatif</th>
        @foreach($kriterias as $k)
          <th class="text-center">{{ $k->kode_kriteria }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($V as $i => $row)
        <tr>
          <td>{{ $alternatifs[$i]->fasilitas->nama_fasilitas }}</td>
          @foreach($kriterias as $k)
            <td class="text-center">{{ number_format($row[$k->kode_kriteria], 4) }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{-- 3) Solusi Ideal Positif & Negatif --}}
<h6 class="mt-4">3) Solusi Ideal Positif (A<sup>+</sup>) dan Negatif (A<sup>-</sup>)</h6>
<div class="table-responsive">
  <table class="table table-sm table-bordered mb-4">
    <thead class="thead-light">
      <tr>
        <th>Jenis Solusi Ideal</th>
        @foreach($kriterias as $k)
          <th class="text-center">{{ $k->kode_kriteria }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Positif (A<sup>+</sup>)</strong></td>
        @foreach($kriterias as $k)
          <td class="text-center">
            {{ number_format($idealPos[$k->kode_kriteria] ?? 0, 4) }}
          </td>
        @endforeach
      </tr>
      <tr>
        <td><strong>Negatif (A<sup>-</sup>)</strong></td>
        @foreach($kriterias as $k)
          <td class="text-center">
            {{ number_format($idealNeg[$k->kode_kriteria] ?? 0, 4) }}
          </td>
        @endforeach
      </tr>
    </tbody>
  </table>
</div>
<small class="text-muted">
  * Untuk kriteria Cost: ideal positif = nilai minimum, ideal negatif = nilai maksimum. Sebaliknya untuk kriteria Benefit.
</small>

{{-- 4) Jarak ke Solusi Ideal --}}
<h6 class="mt-4">4) Jarak Setiap Alternatif ke Solusi Ideal</h6>
<div class="table-responsive">
  <table class="table table-sm table-striped table-bordered mb-4">
    <thead class="thead-light">
      <tr>
        <th>Alternatif</th>
        <th class="text-center">Jarak ke Ideal Positif (D<sup>+</sup>)</th>
        <th class="text-center">Jarak ke Ideal Negatif (D<sup>-</sup>)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($distPos as $i => $d1)
        <tr>
          <td>{{ $alternatifs[$i]->fasilitas->nama_fasilitas }}</td>
          <td class="text-center">{{ number_format($d1, 4) }}</td>
          <td class="text-center">{{ number_format($distNeg[$i], 4) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
