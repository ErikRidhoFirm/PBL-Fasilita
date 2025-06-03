{{-- resources/views/riwayat-perbaikan-teknisi/index.blade.php --}}
@extends('layouts.main')

@section('content')
  <div class="w-100 grid-margin stretch-card">
    <div class="card">
      <div class="card-body w-auto">
        {{-- Judul Halaman --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="card-title">Riwayat Perbaikan</h3>
        </div>

        {{-- Alert Success / Error --}}
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Tabel DataTable --}}
        <div class="table-responsive">
          <table class="table table-hover table-striped" id="table-riwayat-teknisi">
            <thead>
              <tr>
                <th style="width:5%;">No</th>
                <th style="width:25%;">Gedung</th>
                <th style="width:20%;">Ruangan</th>
                <th style="width:25%;">Nama Fasilitas</th>
                <th style="width:15%;">Nama Teknisi</th>
                <th class="text-center" style="width:10%;">Aksi</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  </div>

  {{-- Modal kosong untuk AJAX --}}
  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
       data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
  /* Hover effect pada tombol */
  .btn-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    will-change: transform;
  }
  .btn-hover:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    z-index: 2;
    position: relative;
  }
  /* Padding header tabel agar lebih tinggi */
  #table-riwayat-teknisi thead th {
    padding-top: 12px !important;
    padding-bottom: 12px !important;
  }
</style>
@endpush

@push('js')
<script>
  // Fungsi AJAX modal (memanggil partial show blade)
  function modalAction(url = '') {
    $('#myModal').load(url, function() {
      $('#myModal').modal('show');
    });
  }

  $(document).ready(function() {
    $('#table-riwayat-teknisi').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('riwayat-perbaikan-teknisi.list') }}",
        type: "GET",
        dataType: "json"
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'gedung',      name: 'gedung' },
        { data: 'ruangan',     name: 'ruangan' },
        { data: 'fasilitas',   name: 'fasilitas' },
        { data: 'teknisi',     name: 'teknisi' },
        {
          data: 'aksi',
          name: 'aksi',
          orderable: false,
          searchable: false,
          className: 'text-center'
        }
      ],
      order: [[0, 'asc']],
      pageLength: 10,
      lengthChange: false,
      language: {
        emptyTable: "Belum ada riwayat perbaikan"
      }
    });
  });
</script>
@endpush
