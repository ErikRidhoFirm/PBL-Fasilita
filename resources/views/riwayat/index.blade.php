@extends('layouts.main')

@section('content')

  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Daftar Laporan Fasilitas</h4>

    {{-- Filter Status & Petugas --}}
    <div class="row mb-3">
    <div class="col-md-4">
        <label for="filter-status">Filter Status:</label>
        <select id="filter-status" class="form-control">
        <option value="">Semua</option>
        @foreach($statuses as $st)
            <option value="{{ $st->id_status }}">{{ $st->nama_status }}</option>
        @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="filter-petugas">Filter Petugas:</label>
        <select id="filter-petugas" class="form-control">
        <option value="">Semua</option>
        @foreach($petugas as $usr)
            <option value="{{ $usr->id_pengguna }}">{{ $usr->nama }}</option>
        @endforeach
        </select>
    </div>
    </div>



      <div class="table-responsive">
        <table id="tabel-laporan" class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Pelapor</th>
              <th>Fasilitas</th>
              <th>Status</th>
              <th>Petugas</th>
              <th>Waktu</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

    {{-- Modal container --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
        data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var tableLaporanFasilitas;
    $(function(){
        tableLaporanFasilitas = $('#tabel-laporan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            url: "{!! route('riwayat.list') !!}",
            data: function(d){
                d.status_id  = $('#filter-status').val();
                d.petugas_id = $('#filter-petugas').val();
            }
            },
            columns: [
                { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
                { data:'pelapor',     name:'laporanFasilitas.laporan.pengguna.nama' },
                { data:'fasilitas',   name:'laporanFasilitas.fasilitas.nama_fasilitas' },
                { data:'status',      name:'status.nama_status' },
                { data:'petugas',     name:'pengguna.nama' },
                { data:'waktu',       name:'created_at' },
                { data:'aksi',        name:'aksi', orderable:false, searchable:false }
            ]
        });


        $('#filter-status, #filter-petugas').on('change', ()=> table.ajax.reload());


        $(document).on('click','.btn-delete',function(){
            if(!confirm('Hapus laporan ini?')) return;
            $.ajax({
            url: $(this).data('url'),
            type: 'DELETE',
            data: {_token:'{{ csrf_token() }}'}
            }).done(()=> table.ajax.reload());
        });
    });
</script>
@endpush
