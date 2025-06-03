@extends('layouts.main')

@section('content')
  <h3 class="mb-4">Data Skoring Kriteria</h3>

  @foreach($kriterias as $k)
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ $k->kode_kriteria }}</strong> — {{ $k->nama_kriteria }} ({{ $k->tipe_kriteria }})
        </div>
        <button
          class="btn btn-sm btn-success"
          onclick="modalAction('{{ route('skoring.create', $k->id_kriteria) }}')"
        >
          <i class="mdi mdi-plus"></i> Tambah Skoring
        </button>
      </div>
      <div class="card-body">
        <table
          class="table table-sm table-striped"
          id="table-skoring-{{ $k->id_kriteria }}"
        >
          <thead>
            <tr>
              <th>No</th>
              <th>Parameter</th>
              <th>Nilai Referensi</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  @endforeach

  <div id="myModal" class="modal fade"></div>
@endsection

@push('js')
<script>
  window.tableSkoring = {};  // global object untuk menyimpan DataTable instances

  function modalAction(url) {
    $('#myModal').load(url, () => $('#myModal').modal('show'));
  }

  $(document).ready(function(){
    @foreach($kriterias as $k)
      window.tableSkoring[{{ $k->id_kriteria }}] =
        $('#table-skoring-{{ $k->id_kriteria }}').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ route("skoring.list", $k->id_kriteria) }}',
          columns: [
            { data: 'DT_RowIndex',      orderable:false, searchable:false },
            { data: 'parameter',        name:'parameter' },
            { data: 'nilai_referensi',  name:'nilai_referensi' },
            { data: 'aksi',             orderable:false, searchable:false },
          ],
          error: function(xhr) {
            if (xhr.status === 401 || xhr.status === 403) {
              window.location.reload();
            }
          }
        });
    @endforeach
  });
</script>
@endpush
