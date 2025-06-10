@extends('layouts.main')

@section('content')

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">
        <i class="fas fa-cogs mr-2"></i> Analisis TOPSIS untuk Prioritas Perbaikan Fasilitas
      </h4>

      {{-- Status Perhitungan --}}
      <div id="calculation-status" class="alert alert-info" style="display: none;">
        <i class="fas fa-spinner fa-spin mr-2"></i>
        <span>Perhitungan TOPSIS sedang berjalan otomatis...</span>
      </div>

      {{-- Tombol Hitung --}}
      <form action="{{ route('spk.hitung') }}" method="POST" class="mb-4">
        @csrf
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="fas fa-calculator mr-2"></i> Hitung TOPSIS
        </button>
      </form>

      {{-- Tabel Alternatif & Skor Awal --}}
      <h5 class="mt-4"><i class="fas fa-table mr-2"></i> Data Alternatif & Skor Awal</h5>
      <p class="card-description">
        Berikut adalah data alternatif yang akan dievaluasi beserta skor awal berdasarkan kriteria yang ada.
        Anda dapat mengubah skor melalui tombol "Edit".
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

      {{-- Container untuk Hasil Perhitungan --}}
      <div id="calculation-results-container">
        @if(isset($Ci))
          {{-- Tombol Toggle Langkah Perhitungan --}}
          <button
            id="btnToggleSteps"
            class="btn btn-primary mb-4"
            type="button"
          >
            <i class="fas fa-chevron-down mr-2"></i> Tampilkan Langkah Perhitungan
          </button>

          {{-- Container Langkah Perhitungan --}}
          <div id="calculationSteps" style="display: none;">
            @include('perhitungan.partials.calculation-steps')
          </div>

          {{-- Container Hasil Akhir --}}
          <div id="finalResults">
            @include('perhitungan.partials.final-results')
          </div>
        @else
          <div id="no-results-message" class="alert alert-info mt-4">
            <i class="fas fa-info-circle mr-2"></i>
            Belum ada hasil perhitungan TOPSIS. Klik tombol "Hitung TOPSIS" untuk memulai perhitungan.
          </div>
        @endif
      </div>

      {{-- Modal Container untuk AJAX Edit --}}
      <div id="modalContainer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <!-- Konten modal akan di-load via AJAX -->
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
    let lastRunId = @json(optional($runId)->toString()) || null;
    let isPollingActive = true;
    let pollingInterval;

    // Fungsi untuk memuat hasil perhitungan terbaru
    function loadCalculationResults(runId) {
      console.log('Loading calculation results for runId:', runId);

      $.ajax({
        url: "{{ route('spk.results') }}",
        method: 'GET',
        data: { runId: runId },
        success: function(response) {
          console.log('Calculation results response:', response);

          if (response.success) {
            // Update container hasil
            const container = $('#calculation-results-container');

            // Buat konten baru
            let newContent = `
              <button
                id="btnToggleSteps"
                class="btn btn-primary mb-4"
                type="button"
              >
                <i class="fas fa-chevron-down mr-2"></i> Tampilkan Langkah Perhitungan
              </button>

              <div id="calculationSteps" style="display: none;">
                ${response.calculationStepsHtml}
              </div>

              <div id="finalResults">
                ${response.finalResultsHtml}
              </div>
            `;

            // Animasi fade out -> update -> fade in
            container.fadeOut(300, function() {
              container.html(newContent);
              container.fadeIn(300);

              // Re-bind event handler untuk tombol toggle
              bindToggleStepsEvent();
            });

            // Update runId
            lastRunId = response.runId;

            // Sembunyikan status perhitungan dan pesan no-results
            $('#calculation-status').hide();
            $('#no-results-message').hide();

            // Reload tabel alternatif
            if (typeof table !== 'undefined') {
              table.ajax.reload(null, false);
            }
          } else {
            console.error('Failed to load calculation results:', response.message);
          }
        },
        error: function(xhr) {
          console.error('Error loading calculation results:', xhr);
          $('#calculation-status').hide();
        }
      });
    }

    // Fungsi untuk cek status perhitungan terbaru
    function checkCalculationStatus() {
      if (!isPollingActive) return;

      $.ajax({
        url: "{{ route('spk.status') }}",
        method: 'GET',
        success: function(data) {
          console.log('Status check response:', data);

          if (data.id && data.id !== lastRunId) {
            // Ada perhitungan baru
            console.log('New calculation detected, loading results...');
            $('#calculation-status').show();
            loadCalculationResults(data.id);
          }
        },
        error: function(xhr) {
          console.error('Error checking calculation status:', xhr);
        }
      });
    }

    // Fungsi untuk bind event toggle steps
    function bindToggleStepsEvent() {
      $('#btnToggleSteps').off('click').on('click', function() {
        const container = $('#calculationSteps');
        const button = $(this);

        if (container.is(':visible')) {
          container.slideUp();
          button.html('<i class="fas fa-chevron-down mr-2"></i> Tampilkan Langkah Perhitungan');
        } else {
          container.slideDown();
          button.html('<i class="fas fa-chevron-up mr-2"></i> Sembunyikan Langkah Perhitungan');
        }
      });
    }

    // Inisialisasi polling
    function startPolling() {
      pollingInterval = setInterval(checkCalculationStatus, 5000); // Check setiap 5 detik
    }

    function stopPolling() {
      if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
      }
      isPollingActive = false;
    }

    // Load hasil saat pertama kali jika ada runId
    if (lastRunId) {
      console.log('Initial runId detected:', lastRunId);

      // Cek apakah sudah ada hasil di halaman
      if ($('#finalResults').length === 0 && $('#no-results-message').is(':visible')) {
        console.log('Loading initial calculation results...');
        loadCalculationResults(lastRunId);
      }
    }

    // Mulai polling
    startPolling();

    // Stop polling ketika halaman tidak aktif
    document.addEventListener('visibilitychange', function() {
      if (document.hidden) {
        stopPolling();
      } else {
        isPollingActive = true;
        startPolling();
      }
    });

    // Cleanup saat window unload
    $(window).on('beforeunload', function() {
      stopPolling();
    });

    // Bind event toggle steps untuk hasil yang sudah ada
    bindToggleStepsEvent();

    // Inisialisasi DataTable untuk tabel 'Alternatif & Skor Awal'
    let cols = [
      { data: 'DT_RowIndex', orderable:false, searchable:false },
      { data: 'alternatif', orderable:false, searchable:false },
      { data: 'pelapor', orderable:false, searchable:false },
      @foreach($kriterias as $k)
        { data: '{{ $k->kode_kriteria }}', orderable:false, searchable:false },
      @endforeach
      { data: 'aksi', orderable:false, searchable:false }
    ];

    let table = $('#tbl-alternatif').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{!! route('spk.alternatif.list') !!}",
        error: function(xhr, error, code) {
          console.error('DataTable error:', xhr, error, code);
        }
      },
      columns: cols,
      language: {
        processing: "Memuat data...",
        loadingRecords: "Memuat...",
        zeroRecords: "Tidak ada data yang cocok",
        emptyTable: "Tidak ada data yang tersedia",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
        infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
        search: "Cari:",
        paginate: {
          first: "Pertama",
          previous: "Sebelumnya",
          next: "Selanjutnya",
          last: "Terakhir"
        }
      }
    });

    // Handle tombol edit tiap baris
    $(document).on('click', '.btn-edit', function() {
      let url = $(this).data('url');
      modalAction(url);
    });

    // Handle submit form edit di modal
    $(document).on('submit', '#form-edit', function(e) {
      e.preventDefault();
      let form = $(this);

      // Disable submit button to prevent double submission
      let submitBtn = form.find('button[type="submit"]');
      submitBtn.prop('disabled', true);

      $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: form.serialize(),
        success: function(response) {
          if (response.success) {
            $('#modalContainer').modal('hide');
            table.ajax.reload();

            // Show success message
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message || 'Data berhasil diperbarui'
              });
            } else {
              alert(response.message || 'Data berhasil diperbarui');
            }
          } else {
            // Tampilkan error validasi di modal
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
