@php use App\Models\Status; @endphp

<div class="modal-dialog modal-lg">
  <div class="modal-content border-0 shadow-lg" style="overflow: visible">
    <form id="form-verifikasi" action="{{ route('laporan.verifikasi.store') }}" method="POST">
      @csrf
      <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">

      {{-- HEADER --}}
      <div class="modal-header bg-primary text-white">
        <div class="w-100">
          <h5 class="modal-title mb-1">
            <i class="fas fa-clipboard-check fa-lg me-2"></i>
            Verifikasi Laporan #{{ $laporan->id_laporan }}
          </h5>
          <small class="d-block opacity-75">Silahkan verifikasi laporan kerusakan fasilitas</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow: visible">
        {{-- INFO LAPORAN --}}
        <div class="info-laporan mb-4 p-3 bg-light rounded">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fa-lg me-3 text-primary"></i>
                <div>
                  <small class="text-muted d-block mx-2">Pelapor</small>
                  <strong class="mx-2">{{ $laporan->pengguna->nama }}</strong>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="d-flex align-items-center">
                <i class="far fa-calendar-alt fa-lg me-3 text-primary"></i>
                <div>
                  <small class="text-muted d-block mx-2">Tanggal</small>
                  <strong class="mx-2">{{ $laporan->created_at->format('d-m-Y H:i') }}</strong>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="d-flex align-items-center">
                <i class="fas fa-map-marker-alt fa-lg me-3 text-primary"></i>
                <div>
                  <small class="text-muted d-block mx-2">Lokasi</small>
                  <strong class="mx-2">
                    {{ $laporan->gedung->nama_gedung }} /
                    {{ $laporan->lantai->nomor_lantai }} /
                    {{ $laporan->ruangan->nama_ruangan }}
                  </strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Detail Fasilitas --}}
        @foreach($laporan->laporanFasilitas as $idx => $det)
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
              <div class="d-flex align-items-center">
                <span class="badge bg-primary me-3 fs-6">{{ $idx+1 }}</span>
                <h6 class="mb-0 fw-bold fs-6 mx-2">
                  {{ $det->fasilitas->nama_fasilitas }}
                  <span class="badge bg-warning text-dark ms-2">
                    {{ $det->tingkatKerusakan->parameter }}
                  </span>
                </h6>
              </div>
              <span class="badge bg-{{ $det->status->color ?? 'secondary' }} fs-6">
                {{ $det->status->nama_status }}
              </span>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                <div class="position-relative">
                    <img src="{{ asset('storage/'.$det->path_foto) }}"
                        class="img-fluid rounded border w-100"
                        style="height:150px; object-fit:cover"
                        alt="Foto kerusakan">
                </div>
                <div class="mt-3 impact-highlight p-3 rounded">
                    <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                    <strong>Dampak Pengguna</strong>
                    <p class="mb-0">{{ $det->dampakPengguna->parameter }}</p>
                </div>
                </div>

                <div class="col-md-9">
                <div class="description-highlight mb-4 p-4 rounded">
                    <i class="fas fa-align-left fa-lg text-primary me-2"></i>
                    <div>
                    <h6 class="fw-bold">Deskripsi Kerusakan</h6>
                    <p class="mb-0">{{ $det->deskripsi }}</p>
                    </div>
                </div>

                  <input type="hidden" name="details[{{ $det->id_laporan_fasilitas }}][id]" value="{{ $det->id_laporan_fasilitas }}">

                  {{-- FORM VERIFIKASI --}}
                  <div class="verifikasi-form bg-light p-3 rounded">
                    <div class="row g-3">
                      <div class="col-md-3">
                        <label class="form-label small fw-bold mb-2">Status Verifikasi</label>
                        <select name="details[{{ $det->id_laporan_fasilitas }}][verif_status]" class="form-select verif-status" required>
                          <option value="{{ Status::VALID }}">Valid</option>
                          <option value="{{ Status::TIDAK_VALID }}">Tidak Valid</option>
                          <option value="{{ Status::DITOLAK }}">Ditolak</option>
                        </select>
                      </div>
                      <div class="col-md-6 mx-5">
                        <label class="form-label small fw-bold mb-2">Catatan</label>
                        <input type="text" name="details[{{ $det->id_laporan_fasilitas }}][catatan]" class="form-control" placeholder="Masukkan catatan (opsional)...">
                      </div>
                    </div>

                    {{-- SCORING SECTION --}}
                    <div class="scoring-section mt-4">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 fs-6">
                          <i class="fas fa-star-half-alt fa-lg text-warning me-3"></i>Penilaian Kriteria
                        </h6>
                        <small class="text-muted">Pilih skor untuk setiap kriteria</small>
                      </div>
                      <hr class="mt-2 mb-4">

                      <div class="criteria-list">
                        <div class="row g-4">
                          @foreach($kriterias as $crit)
                            @php
                              // Default value: untuk C1 gunakan id_tingkat_kerusakan,
                              // untuk C4 gunakan id_dampak_pengguna
                              $default = '';
                              if($crit->kode_kriteria === 'C1') {
                                  $default = $det->tingkatKerusakan->nilai_referensi;
                              } elseif($crit->kode_kriteria === 'C4') {
                                  $default = $det->dampakPengguna->nilai_referensi;
                              }
                            @endphp

                            <div class="col-md-4">
                              <div class="card criteria-card h-100 border-0 shadow-sm">
                                <div class="card-body p-3">
                                  <label class="form-label small mb-3 d-block">
                                    <span class="fw-bold text-primary">{{ $crit->kode_kriteria }}</span> —
                                    <span class="criteria-name">{{ $crit->nama_kriteria }}</span>
                                  </label>

                                  <div class="dropdown-container">
                                    <select name="details[{{ $det->id_laporan_fasilitas }}][skor][{{ $crit->kode_kriteria }}]"
                                            class="form-select scoring-input"
                                            {{ $crit->kode_kriteria === 'C1' || $crit->kode_kriteria === 'C4' ? '' : 'required' }}>
                                      <option value="" disabled {{ $default === '' ? 'selected' : '' }}>— Pilih skor —</option>
                                      @foreach($crit->skoringKriterias as $opt)
                                        <option value="{{ $opt->nilai_referensi }}"
                                                {{ (string)$opt->nilai_referensi === (string)$default ? 'selected' : '' }}>
                                          {{ Str::limit($opt->parameter, 50) }} ({{ $opt->nilai_referensi }})
                                        </option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="modal-footer bg-light border-top py-3">
        <button type="button" class="btn btn-outline-secondary me-2" data-dismiss="modal">
          <i class="fas fa-times fa-lg me-2"></i> Batal
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save fa-lg me-2"></i> Simpan Semua
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Styles --}}
<style>
  .modal-content {
    overflow: visible !important;
  }

  .modal-body {
    overflow: visible !important;
  }

  .info-laporan {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
  }

  .verifikasi-form {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
  }

  .criteria-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    transition: all 0.2s ease;
  }

  .criteria-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .dropdown-container {
    position: relative;
    z-index: 1000;
  }

  .scoring-input {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 0.6rem 0.75rem;
    font-size: 0.9rem;
    width: 100%;
  }

  .scoring-section {
    margin-top: 1rem;
  }

  .verif-status {
    margin-bottom: 1rem;
  }

  /* letakkan di <style> di bawah Blade Anda */
.impact-highlight {
  background-color: #fff3cd; /* kuning muda */
  border-left: 4px solid #ffc107; /* kuning keemasan */
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.impact-highlight h6,
.impact-highlight strong {
  color: #856404;
}
.impact-highlight p {
  font-size: 0.95rem;
  margin-top: 0.25rem;
}

.description-highlight {
  background-color: #e2e3e5; /* abu lembut */
  border-left: 4px solid #0d6efd; /* biru cerah */
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.description-highlight h6 {
  color: #0d6efd;
  margin-bottom: 0.5rem;
}
.description-highlight p {
  font-size: 1rem;
  line-height: 1.5;
}


  @media (max-width: 768px) {
    .card-body {
      padding: 1.25rem !important;
    }

    .col-md-3 {
      margin-bottom: 1.25rem;
    }

    .modal-body {
      padding: 1.25rem !important;
    }

    .fa-lg {
      font-size: 1.1em !important;
    }
  }
</style>

{{-- JS --}}
<script>
$(function(){
  // Toggle scoring section
  $('.verif-status').each(function(){
    const sel  = $(this);
    const card = sel.closest('.card');
    sel.on('change', function(){
      const isOk = this.value == '{{ Status::VALID }}';
      card.find('.scoring-section')[isOk ? 'slideDown' : 'slideUp']();
      card.find('.scoring-input').prop('required', isOk).prop('disabled', !isOk);
    }).trigger('change');
  });

  // Form submission
  $('#form-verifikasi').on('submit', function(e){
    e.preventDefault();
    const form      = $(this);
    const submitBtn = form.find('button[type="submit"]');

    submitBtn.prop('disabled', true)
             .html('<i class="fas fa-spinner fa-spin fa-lg me-2"></i> Menyimpan...');

    $.ajax({
      url:    form.attr('action'),
      method: 'POST',
      data:   form.serialize(),
      success: function(res) {
        // Tutup modal
        form.closest('.modal').modal('hide');
        // Hapus backdrop yang tersisa
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');

        // Notifikasi sukses
        Swal.fire({
          icon:  'success',
          title: 'Berhasil',
          text:  res.message,
          timer: 2000
        });
        // Reload datatable bila diperlukan
        if (window.tableLaporan) {
          window.tableLaporan.ajax.reload();
        }
      },
      error: function() {
        Swal.fire({
          icon:  'error',
          title: 'Gagal',
          text:  'Terjadi kesalahan saat menyimpan data'
        });
      },
      complete: function() {
        submitBtn.prop('disabled', false)
                 .html('<i class="fas fa-save fa-lg me-2"></i> Simpan Semua');
      }
    });
  });
});
</script>
