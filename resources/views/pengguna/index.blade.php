@extends('layouts.main')

@section('content')
  <div class="w-100 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="alert alert-danger d-flex align-items-center mb-4" id="guest-count-info" style="display: none;">
            <i class="fa fa-users mr-2"></i>
            <span id="guest-count-text">Total Guest: 0</span>
            <small class="text-muted ml-3" id="last-update-text"></small>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title my-5 w-25">Data Pengguna</h3>
            <div>
                <button class="btn btn-success btn-sm mr-2">
                    <a href="{{ route('pengguna.export_excel') }}" class="text-white text-decoration-none">
                        <i class="fa fa-file-excel"></i> Excel
                    </a>
                </button>
                <button class="btn btn-danger btn-sm mr-2">
                    <a href="{{ route('pengguna.export_pdf') }}" class="text-white text-decoration-none" target="_blank">
                        <i class="fa fa-file-pdf"></i> PDF
                    </a>
                </button>
                <button class="btn btn-success btn-sm mr-2" onclick="modalAction('{{ route('pengguna.import') }}')">
                    <i class="fa fa-file-import"></i> Import
                </button>
                <button class="btn btn-primary btn-sm" onclick="modalAction('{{ route('pengguna.create') }}')">
                    Tambah Pengguna
                </button>
            </div>
        </div>

        {{-- Filter Role --}}
        <div class="mb-3">
          <label for="filter-role">Filter Peran:</label>
          <select id="filter-role" class="form-control w-25">
            <option value="">Semua</option>
            @foreach($peran as $role)
              <option value="{{ $role->id_peran }}">{{ $role->nama_peran }}</option>
            @endforeach
          </select>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-striped" id="table-pengguna">
            <thead>
              <tr>
                <th>No</th>
                <th>Username Pengguna</th>
                <th>Nama Pengguna</th>
                <th>Peran</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal container --}}
  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
       data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
  </div>
@endsection

@push('css')
<style>
    .guest-row {
        background: linear-gradient(
            to right,
            rgba(255, 240, 240, 0.6),  /* merah transparan lembut */
            rgba(255, 230, 230, 0.6)   /* merah sedikit lebih pekat namun tetap soft */
        ) !important;
        color: #B31B1B !important;  /* teks berwarna merah #B31B1B */
    }
</style>
@endpush

@push('js')
<script>
  function modalAction(url = '') {
    $('#myModal').load(url, function() {
      $('#myModal').modal('show');
    });
  }

  var tablePengguna;

  $(document).ready(function() {
    // 1. Inisialisasi DataTable
     tablePengguna = $('#table-pengguna').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('pengguna.list') }}",
        data: function(d) {
          d.role_id = $('#filter-role').val();
        }
      },
      columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'username' },
        { data: 'nama' },
        { data: 'peran_nama' },
        { data: 'aksi', orderable: false, searchable: false }
      ],
      rowCallback: function(row, data) {
        if (data.peran_nama === 'Guest') {
          $(row).addClass('guest-row');
        }
      }
    });

    // 2. Reload saat filter Peran berubah
    $('#filter-role').on('change', function() {
      tablePengguna.ajax.reload(null, false);
    });

    // 3. Long-polling Guest Count
    var $alert      = $('#guest-count-info');
    var $countText  = $('#guest-count-text');
    var $lastUpdate = $('#last-update-text');
    var lastCount   = 0;

    function formatTime(date) {
      return date.toLocaleTimeString('id-ID', {
        hour:   '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
    }

    function listenGuestCount(count) {
      $.ajax({
        url: '{{ route("pengguna.guestCountStream") }}',
        data: { lastCount: count },
        dataType: 'json',
        timeout: 35000,
        success: function(data) {
          var oldCount = lastCount;
          var newCount = data.guestCount;
          lastCount = newCount;

          // Tampilkan alert sekali saja
          if (!$alert.is(':visible')) {
            $alert.slideDown(200);
          }

          // Update teks jika berubah
          if ($countText.text() !== 'Total Guest: ' + newCount) {
            $countText.text('Total Guest: ' + newCount);
            $lastUpdate.text('Last update: ' + formatTime(new Date()));
          }

          // Reload tabel jika count berubah
          if (newCount !== oldCount) {
            tablePengguna.ajax.reload(null, false);
          }

          // Rekursif long-poll
          listenGuestCount(lastCount);
        },
        error: function() {
          // Retry setelah 5 detik jika error
          setTimeout(function() {
            listenGuestCount(lastCount);
          }, 5000);
        }
      });
    }

    // Mulai long-polling
    listenGuestCount(0);
  });
</script>
@endpush

