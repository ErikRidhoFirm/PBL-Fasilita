@extends('layouts.main')

@section('content')
  <div class="w-100 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <!-- Header with title and Add button -->
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title my-5 w-25">Data Kriteria</h3>
          <button
            class="btn btn-primary btn-sm"
            onclick="modalAction('{{ route('kriteria.create') }}')"
          >
            Tambah Kriteria
          </button>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
          <table class="table table-hover table-striped" id="table-kriteria">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Kriteria</th>
                <th>Nama Kriteria</th>
                <th>Bobot</th>
                <th>Tipe</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal placeholder for Create/Edit/Delete -->
  <div
    id="myModal"
    class="modal fade animate shake"
    tabindex="-1"
    role="dialog"
    data-backdrop="static"
    data-keyboard="false"
    aria-hidden="true"
  ></div>
@endsection

@push('js')
<script>
  /**
   * Load a remote Blade partial into #myModal and show as Bootstrap modal
   */
  function modalAction(url = '') {
    $('#myModal').load(url, function() {
      $(this).modal('show');
    });
  }

  let tableKriteria;
  $(document).ready(function() {
    tableKriteria = $('#table-kriteria').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        "url": "{{ route("kriteria.list") }}",
        "dataType": "json",
        "type": "GET"
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'kode_kriteria', name: 'kode_kriteria' },
        { data: 'nama_kriteria', name: 'nama_kriteria' },
        { data: 'bobot_kriteria', name: 'bobot_kriteria' },
        { data: 'tipe_kriteria', name: 'tipe_kriteria' },
        { data: 'deskripsi', name: 'deskripsi' },
        { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
      ],
      error: function(xhr) {
        // On unauthorized or forbidden, redirect to dashboard
        if (xhr.status === 401 || xhr.status === 403) {
          window.location.href = '{{ route("dashboard") }}';
        }
      }
    });
  });
</script>
@endpush
