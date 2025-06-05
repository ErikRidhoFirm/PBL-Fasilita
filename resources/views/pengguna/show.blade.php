<div class="modal-dialog modal-lg w-50">
    <div class="modal-content">
      <!-- Header Modal -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fas fa-user mr-2"></i>Detail Pengguna
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body Modal -->
      <div class="modal-body p-4">
        <div class="row align-items-center">
          <!-- Kolom Foto Profil -->
          <div class="col-md-4 text-center mb-4 mb-md-0">
            <div class="position-relative d-inline-block">
              <img src="{{ $pengguna->foto_profile ?? asset('foto/default.jpg') }}"
                   class="img-thumbnail rounded-circle border-primary"
                   style="width: 150px; height: 150px; object-fit: cover; border-width: 3px !important;"
                   alt="Foto Profil">
              <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white"
                    style="width: 20px; height: 20px;"></span>
            </div>
            <h4 class="mt-3 mb-1 text-break">{{ $pengguna->nama }}</h4>
            <span class="badge badge-pill badge-info py-2 px-3">{{ $pengguna->peran->nama_peran }}</span>
          </div>

          <!-- Kolom Detail -->
          <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-3">
                <!-- List Detail -->
                <div class="list-group list-group-flush">
                  <!-- Username -->
                  <div class="list-group-item border-0 py-2 px-0">
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-user-tag text-primary"></i>
                      </div>
                      <div>
                        <small class="text-muted d-block">Username</small>
                        <span class="font-weight-bold text-break">{{ $pengguna->username }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Nama Lengkap -->
                  <div class="list-group-item border-0 py-2 px-0">
                    <div class="d-flex align-items-center">
                      <div class=" rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-id-card text-info"></i>
                      </div>
                      <div>
                        <small class="text-muted d-block">Nama Lengkap</small>
                        <span class="font-weight-bold text-break">{{ $pengguna->nama }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Peran -->
                  <div class="list-group-item border-0 py-2 px-0">
                    <div class="d-flex align-items-center">
                      <div class=" rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-user-shield text-warning"></i>
                      </div>
                      <div>
                        <small class="text-muted d-block">Peran</small>
                        <span class="font-weight-bold">{{ $pengguna->peran->nama_peran }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Dibuat Pada -->
                  <div class="list-group-item border-0 py-2 px-0">
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="far fa-calendar-plus text-success"></i>
                      </div>
                      <div>
                        <small class="text-muted d-block">Dibuat Pada</small>
                        <span class="font-weight-bold">{{ $pengguna->created_at->format('d M Y H:i') }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Terakhir Diubah -->
                  <div class="list-group-item border-0 py-2 px-0">
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-history text-purple"></i>
                      </div>
                      <div>
                        <small class="text-muted d-block">Terakhir Diubah</small>
                        <span class="font-weight-bold">{{ $pengguna->updated_at->format('d M Y H:i') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Modal -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-warning" data-dismiss="modal">
            Tutup
        </button>
      </div>
    </div>
  </div>
