@extends('layouts.main')

@push('css')
<style>
  /* Style untuk menempatkan ikon di dalam input password */
  .password-wrapper {
    position: relative;
  }

  .password-wrapper .field-icon {
    position: absolute;
    top: 68%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #888;
    z-index: 100;
  }

  /* Style untuk toggle buttons foto */
  .photo-toggle-container {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    border: 1px solid #e9ecef;
  }

  .photo-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
  }

  .btn-toggle {
    flex: 1;
    padding: 10px 20px;
    border: 2px solid;
    border-radius: 8px;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: transparent;
    position: relative;
    overflow: hidden;
  }

  .btn-toggle.btn-primary-outline {
    color: #0d6efd;
    border-color: #0d6efd;
  }

  .btn-toggle.btn-primary-outline:hover {
    background: #0d6efd;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
  }

  .btn-toggle.btn-danger-outline {
    color: #dc3545;
    border-color: #dc3545;
  }

  .btn-toggle.btn-danger-outline:hover {
    background: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
  }

  .photo-section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 1.1rem;
  }

  /* Responsive design */
  @media (max-width: 576px) {
    .photo-actions {
      flex-direction: column;
    }

    .btn-toggle {
      flex: none;
    }
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
      <div class="card shadow-sm rounded-2xl">
        <div class="card-body">
          <h4 class="card-title mb-4">Edit Profil</h4>

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          {{-- Section Foto Profil --}}
          <div class="photo-toggle-container">
            <div class="photo-section-title">
              <i class="fas fa-camera me-2"></i>Kelola Foto Profil
            </div>

            <div class="d-flex align-items-center mb-3">
              <div>
                <img src="{{ $user->foto_profile ? asset('storage/uploads/profiles/' . $user->foto_profile) : asset('foto/default.jpg') }}"
                     alt="Foto Profil"
                     class="rounded-circle shadow-sm me-3"
                     width="100" height="100">
              </div>
              <div class="flex-grow-1">
                <form method="POST" action="{{ route('profile.update_photo') }}" enctype="multipart/form-data" id="photoForm">
                  @csrf
                  <label class="form-label">Unggah Foto Baru</label>
                  <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" id="photoInput">
                  @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </form>
              </div>
            </div>

            <div class="photo-actions">
              <button type="button" class="btn-toggle btn-primary-outline" onclick="document.getElementById('photoForm').submit();">
                <i class="fas fa-upload me-2"></i>Ganti Foto
              </button>

              <form method="POST" action="{{ route('profile.delete_photo') }}" style="flex: 1;" id="deletePhotoForm">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-toggle btn-danger-outline w-100" onclick="confirmDeletePhoto()">
                  <i class="fas fa-trash me-2"></i>Hapus Foto
                </button>
              </form>
            </div>
          </div>

          <hr>

          {{-- Form Edit Biodata --}}
          <form method="POST" action="{{ route('profile.update_info') }}">
            @csrf

            {{-- Bagian Biodata --}}
            <div class="mb-3">
              <label class="form-label">Nomor Induk</label>
              <input type="text" class="form-control-plaintext" value="{{ $user->no_induk }}" readonly>
            </div>

            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror">
              @error('username')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control @error('nama') is-invalid @enderror">
              @error('nama')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            {{-- Bagian Ubah Password --}}
            <h5 class="mb-3">Ubah Password (opsional)</h5>

            <div class="mb-3 password-wrapper">
              <label for="old_password" class="form-label">Password Lama</label>
              <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror">
              <span class="fa fa-eye field-icon" data-target="#old_password"></span>
              @error('old_password')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3 password-wrapper">
              <label for="new_password" class="form-label">Password Baru</label>
              <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
              <span class="fa fa-eye field-icon" data-target="#new_password"></span>
              @error('new_password')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4 password-wrapper">
              <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
              <input type="password" name="new_password_confirmation" id="confirm_password" class="form-control">
              <span class="fa fa-eye field-icon" data-target="#confirm_password"></span>
            </div>

            {{-- Tombol Aksi Utama --}}
            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-success px-4">Simpan Perubahan</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  // Toggle password visibility
  document.querySelectorAll('.field-icon').forEach(icon => {
    icon.addEventListener('click', () => {
      const input = document.querySelector(icon.getAttribute('data-target'));
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  });

  // Validasi file input sebelum submit
  document.getElementById('photoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const fileSize = file.size / 1024 / 1024; // MB
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

      if (!allowedTypes.includes(file.type)) {
        Swal.fire({
          icon: 'error',
          title: 'Format File Tidak Didukung',
          text: 'Gunakan format JPG, JPEG, atau PNG.',
          confirmButtonColor: '#dc3545'
        });
        this.value = '';
        return;
      }

      if (fileSize > 2) {
        Swal.fire({
          icon: 'error',
          title: 'Ukuran File Terlalu Besar',
          text: 'Ukuran file maksimal 2MB.',
          confirmButtonColor: '#dc3545'
        });
        this.value = '';
        return;
      }
    }
  });

  // Konfirmasi hapus foto dengan SweetAlert
  function confirmDeletePhoto() {
    Swal.fire({
      title: 'Hapus Foto Profil?',
      text: "Foto profil Anda akan dihapus dan diganti dengan foto default.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('deletePhotoForm').submit();
      }
    });
  }
</script>
@endpush
