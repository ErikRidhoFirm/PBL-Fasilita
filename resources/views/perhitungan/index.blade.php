@extends('layouts.main')

@section('content')

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-4"> <i class="fas fa-cogs me-2"></i> Analisis TOPSIS untuk Prioritas Perbaikan Fasilitas</h4>

    {{-- Tombol Hitung --}}
    <form action="{{ route('spk.hitung') }}" method="POST" class="mb-4">
      @csrf
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-calculator me-2"></i> Hitung TOPSIS
      </button>
    </form>

    {{-- Tabel Input/Nilai Mentah --}}
    <h5 class="mt-4"><i class="fas fa-table me-2"></i> Data Alternatif & Skor Awal</h5>
    <p class="card-description">
        Berikut adalah data alternatif yang akan dievaluasi beserta skor awal berdasarkan kriteria yang ada. Anda dapat mengubah skor melalui tombol "Edit".
    </p>
    <div class="table-responsive">
        <table id="tbl-alternatif" class="table table-bordered">
            <thead>
                <tr>
                <th>No</th>
                <th>Alternatif</th>
                <th>Pelapor</th>
                @foreach($kriterias as $k)
                    <th class="text-center">{{ $k->kode_kriteria }}</th>
                @endforeach
                <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

    @if(isset($Ci))
      <hr class="my-4">
      <h4 class="card-title mt-4 mb-3"><i class="fas fa-chart-line me-2"></i> Hasil Perhitungan TOPSIS (Run #{{ $runId }})</h4>

      {{-- Tampilkan Matriks Normalisasi --}}
      <h6 class="mt-4">1) Matriks Keputusan Ternormalisasi (R)</h6>
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered mb-4">
          <thead class="thead-light">
            <tr>
              <th>Alternatif</th>
              @foreach($kriterias as $k)<th class="text-center">{{ $k->kode_kriteria }}</th>@endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($norm as $i=>$row)
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

      {{-- Tampilkan Matriks Terbobot V --}}
      <h6 class="mt-4">2) Matriks Keputusan Ternormalisasi Terbobot (V)</h6>
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered mb-4">
          <thead class="thead-light">
            <tr>
              <th>Alternatif</th>
              @foreach($kriterias as $k)<th class="text-center">{{ $k->kode_kriteria }}</th>@endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($V as $i=>$row)
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

      {{-- Ideal Positif/Negatif --}}
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
                        <td class="text-center table-success-light">{{-- Skydash might use table-success-light or similar for lighter shades --}}
                           {{ number_format($idealPos[$k->kode_kriteria] ?? 0, 4) }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>Negatif (A<sup>-</sup>)</strong></td>
                    @foreach($kriterias as $k)
                        <td class="text-center table-danger-light">  {{-- Skydash might use table-danger-light or similar --}}
                            {{ number_format($idealNeg[$k->kode_kriteria] ?? 0, 4) }}
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
      </div>
      <small class="text-muted">* Untuk kriteria Cost, nilai ideal positif adalah nilai minimum, dan ideal negatif adalah nilai maksimum. Sebaliknya untuk kriteria Benefit.</small>


      {{-- Jarak --}}
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
            @foreach($distPos as $i=>$d1)
              <tr>
                <td>{{ $alternatifs[$i]->fasilitas->nama_fasilitas }}</td>
                <td class="text-center">{{ number_format($d1, 4) }}</td>
                <td class="text-center">{{ number_format($distNeg[$i], 4) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Tampilkan Ranking Akhir --}}
      <h5 class="mt-5 text-primary"><i class="fas fa-award me-2"></i> Hasil Akhir Peringkat Prioritas Perbaikan</h5>
      <p class="card-description">
        Alternatif diurutkan berdasarkan skor preferensi (C<sub>i</sub>) tertinggi, yang menunjukkan prioritas perbaikan tertinggi.
      </p>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark"> {{-- Using thead-dark for emphasis on final result --}}
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
                      'alt' => $alt,
                      'skor' => $Ci[$alt->id_laporan_fasilitas] ?? 0,
                      'd_pos' => $distPos[$index] ?? 0, // Assuming $distPos is indexed same as $alternatifs
                      'd_neg' => $distNeg[$index] ?? 0  // Assuming $distNeg is indexed same as $alternatifs
                  ];
              })->sortByDesc('skor')->values();
            @endphp

            @foreach($result as $idx => $row)
              <tr class="{{ $idx == 0 ? 'table-primary' : '' }}"> {{-- Highlight top rank --}}
                <td class="text-center"><strong>{{ $idx + 1 }}</strong></td>
                <td>
                    <strong>{{ $row['alt']->fasilitas->nama_fasilitas }}</strong>
                    <br><small class="text-muted">Pelapor: {{ $row['alt']->laporan->pengguna->nama }}</small>
                    <br><small><em>(D<sup>+</sup>: {{ number_format($row['d_pos'], 4) }}, D<sup>-</sup>: {{ number_format($row['d_neg'], 4) }})</em></small>
                </td>
                <td class="text-center"><strong>{{ number_format($row['skor'], 4) }}</strong></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
<div id="modalContainer" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
      <!-- Content will be loaded here by JavaScript -->
    </div>

  </div>
</div>



@endsection

@push('js')
<script>
function modalAction(url) {
  $('#modalContainer').load(url, function() {
    $(this).modal('show');
  });
}

$(function(){
  let cols = [
    { data: 'DT_RowIndex', orderable:false, searchable:false },
    { data: 'alternatif', orderable:false, searchable:false  },
    { data: 'pelapor', orderable:false, searchable:false  },
    @foreach($kriterias as $k)
      { data: '{{ $k->kode_kriteria }}', orderable:false, searchable:false  },
    @endforeach
    { data: 'aksi', orderable:false, searchable:false }
  ];

  let table = $('#tbl-alternatif').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{!! route('spk.alternatif.list') !!}",
    columns: cols
  });

  // Handle edit button clicks
  $(document).on('click', '.btn-edit', function() {
    let url = $(this).data('url');
    modalAction(url);
  });

  // Handle form submission
  $(document).on('submit', '#form-edit', function(e) {
    e.preventDefault();
    let form = $(this);

    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: form.serialize(),
      success: function(response) {
        if (response.success) {
          $('#modalContainer').modal('hide');
          table.ajax.reload();
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.message || 'Data berhasil diperbarui'
          });
        } else {
          // Handle validation errors
          $('.error-text').text('');
          $.each(response.errors || {}, function(prefix, val) {
            $('#error-' + prefix).text(val[0]);
          });
        }
      },
      error: function(xhr) {
        let errors = xhr.responseJSON?.errors;
        if (errors) {
          $('.error-text').text('');
          $.each(errors, function(prefix, val) {
            $('#error-' + prefix).text(val[0]);
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: xhr.responseJSON?.message || 'Terjadi kesalahan'
          });
        }
      }
    });
  });
});
</script>

@endpush
