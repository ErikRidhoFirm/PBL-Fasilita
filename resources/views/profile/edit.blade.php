@extends('layouts.main')

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-8 offset-md-2 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Profil</h4>

          @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <!-- Edit Foto -->
          <form method="POST" action="{{ route('profile.update_photo') }}" enctype="multipart/form-data" class="mb-4">
              @csrf
              <div class="form-group">
                  <label>Foto saat ini:</label><br>
                  <!-- Menampilkan foto profil dengan fallback default jika NULL -->
                  <img src="{{ $user->foto_profile ? asset('storage/uploads/profiles/' . $user->foto_profile) : asset('foto/default.jpg') }}"
                       alt="Foto Profil"
                       class="rounded-circle img-fluid mb-2"
                       width="100"><br>
                  <input type="file" name="foto" class="form-control-file">
                  @error('foto')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>
              <button type="submit" class="btn btn-primary">Ganti Foto</button>
          </form>

          <hr>

          <!-- Edit Biodata -->
          <form method="POST" action="{{ route('profile.update_info') }}" class="mb-4">
              @csrf
              <div class="form-group">
                  <label>Username:</label>
                  <input type="text" name="username" value="{{ $user->username }}" class="form-control">
                  @error('username')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>

              <div class="form-group">
                  <label>Nama:</label>
                  <input type="text" name="nama" value="{{ $user->nama }}" class="form-control">
                  @error('nama')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>

              <div class="form-group">
                  <label>Password Lama:</label>
                  <input type="password" class="form-control" value="*******" readonly>
              </div>

              <div class="form-group position-relative">
                  <label>Password Baru:</label>
                  <input type="password" name="new_password" id="new_password" class="form-control" >
                  <span toggle="#new_password" class="fa fa-fw fa-eye field-icon toggle-password" style="position:absolute; top:35px; right:10px; cursor:pointer;"></span>
                  @error('new_password')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>

              <div class="form-group position-relative">
                  <label>Konfirmasi Password Baru:</label>
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" \>
                  <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password" style="position:absolute; top:35px; right:10px; cursor:pointer;"></span>
              </div>

              <button type="submit" class="btn btn-success">Simpan Biodata</button>
          </form>

          <hr>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function(element) {
        element.addEventListener('click', function() {
            let input = document.querySelector(this.getAttribute('toggle'));
            if (input.getAttribute('type') === 'password') {
                input.setAttribute('type', 'text');
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.setAttribute('type', 'password');
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush
