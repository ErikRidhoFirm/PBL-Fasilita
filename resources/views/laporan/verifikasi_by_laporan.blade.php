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
            <i class="fas fa-clipboard-check fa-lg me-2"></i> Verifikasi Laporan #{{ $laporan->id_laporan }}
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

        {{-- DETAIL FASILITAS --}}
        @foreach($laporan->laporanFasilitas as $idx => $det)
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
              <div class="d-flex align-items-center">
                <span class="badge bg-primary me-3 fs-6">{{ $idx+1 }}</span>
                <h6 class="mb-0 fw-bold fs-6 mx-2">
                  {{ $det->fasilitas->nama_fasilitas }}
                  <span class="badge bg-secondary ms-2">{{ $det->kategoriKerusakan->nama_kerusakan }}</span>
                </h6>
              </div>
              <span class="badge bg-{{ $det->status->color ?? 'secondary' }} fs-6">
                {{ $det->status->nama_status }}
              </span>
            </div>

            <div class="card-body">
              <div class="row g-3">
                {{-- FOTO --}}
                <div class="col-md-3">
                  <div class="position-relative">
                    <img src="{{ asset('storage/' . $det->path_foto) }}"
                         class="img-fluid rounded border w-100"
                         style="height:150px; object-fit: cover"
                         alt="Foto kerusakan">
                    <div class="position-absolute top-0 end-0 m-2">
                      <span class="badge bg-info fs-6">{{ $det->jumlah_rusak }} unit rusak</span>
                    </div>
                  </div>
                </div>

                {{-- DETAIL --}}
                <div class="col-md-9">
                  <div class="mb-3">
                    <p class="mb-2 d-flex align-items-center">
                      <i class="fas fa-align-left fa-lg text-muted me-3"></i>
                      <span>{{ $det->deskripsi }}</span>
                    </p>
                  </div>

                  <input type="hidden" name="details[{{ $det->id_laporan_fasilitas }}][id]" value="{{ $det->id_laporan_fasilitas }}">

                  {{-- FORM VERIFIKASI --}}
                  <div class="verifikasi-form bg-light p-3 rounded">
                    <div class="row g-3">
                      <div class="col-md-3">
                        <label class="form-label small fw-bold mb-2">Status Verifikasi</label>
                        <select name="details[{{ $det->id_laporan_fasilitas }}][verif_status]" class="form-select verif-status" required>
                            <option value="{{ Status::VALID }}">Valid</option>
                            <option value="{{ Status::TIDAK_VALID }}">
                                Tidak Valid
                            </option>
                            <option value="{{ Status::DITOLAK }}">
                                Ditolak
                            </option>
                        </select>
                      </div>
                      <div class="col-md-6 mx-5">
                        <label class="form-label small fw-bold mb-2">Catatan</label>
                        <input type="text"
                               name="details[{{ $det->id_laporan_fasilitas }}][catatan]"
                               class="form-control"
                               placeholder="Masukkan catatan (opsional)...">
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
                            <div class="col-md-4">
                              <div class="card criteria-card h-100 border-0 shadow-sm">
                                <div class="card-body p-3">
                                  <label class="form-label small mb-3 d-block">
                                    <span class="fw-bold text-primary">{{ $crit->kode_kriteria }}</span> —
                                    <span class="criteria-name">{{ $crit->nama_kriteria }}</span>
                                  </label>

                                  <div class="dropdown-container">
                                    <select name="details[{{ $det->id_laporan_fasilitas }}][skor][{{ $crit->kode_kriteria }}]"
                                            class="form-select scoring-input">
                                      <option value="" disabled selected>— Pilih skor —</option>
                                      @foreach($crit->skoringKriterias as $opt)
                                        <option value="{{ $opt->nilai_referensi }}">
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

<style>
  .modal-content {
    overflow: visible !important;
  }

  .modal-body {
    overflow: visible !important;
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

  .verifikasi-form {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
  }

  .info-laporan {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd;
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

<script>
$(function(){
  // Toggle scoring section
    $('.verif-status').each(function(){
        const sel  = $(this);
        const card = sel.closest('.card');

        sel.on('change', function(){
            const isOk = this.value == '{{ Status::VALID }}';
            // Tampilkan / sembunyikan panel scoring
            card.find('.scoring-section')[ isOk ? 'slideDown' : 'slideUp' ]();
            // Atur atribut required & disabled untuk setiap dropdown di dalam card
            card.find('.scoring-input').each(function(){
            $(this)
                .prop('required', isOk)    // wajib isi hanya kalau diverifikasi
                .prop('disabled', !isOk);  // matikan kalau tidak diverifikasi
            });
        }).trigger('change');
    });

  // Form submission
  $('#form-verifikasi').on('submit', function(e){
    e.preventDefault();
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');

    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin fa-lg me-2"></i> Menyimpan...');

    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: form.serialize(),
      success: function(res) {
        $('#myModal').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: res.message,
          timer: 2000
        });
        window.tableLaporan.ajax.reload();
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Terjadi kesalahan saat menyimpan data'
        });
      },
      complete: function() {
        submitBtn.prop('disabled', false).html('<i class="fas fa-save fa-lg me-2"></i> Simpan Semua');
      }
    });
  });
});
</script>
