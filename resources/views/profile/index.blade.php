@extends('layouts.main')

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-8 offset-md-2 grid-margin stretch-card">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Profil Saya</h4>
        </div>
        <div class="card-body">
          <div class="text-center mb-4">
            <img src="{{ $user->foto_profile ? asset('storage/uploads/profiles/' . $user->foto_profile) : asset('foto/default.jpg') }}"
                 class="rounded-circle shadow-sm"
                 width="120" height="120"
                 style="object-fit: cover; border: 3px solid #007bff;"
                 alt="Foto Profil">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="info-item">
                <label class="text-muted small">Nomor Induk</label>
                <p class="mb-0 font-weight-bold">{{ $user->no_induk }}</p>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="info-item">
                <label class="text-muted small">Peran</label>
                <p class="mb-0 font-weight-bold">
                  <span class="badge badge-info">{{ $user->peran->nama_peran }}</span>
                </p>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="info-item">
                <label class="text-muted small">Username</label>
                <p class="mb-0 font-weight-bold">{{ $user->username }}</p>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="info-item">
                <label class="text-muted small">Nama Lengkap</label>
                <p class="mb-0 font-weight-bold">{{ $user->nama }}</p>
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-lg px-4">
              <i class="mdi mdi-pencil mr-2"></i>Edit Profil
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.info-item {
  background-color: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  border-left: 4px solid #007bff;
}

.info-item label {
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card {
  border: none;
  border-radius: 15px;
}

.card-header {
  border-radius: 15px 15px 0 0 !important;
}

.shadow-sm {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
</style>
@endsection
