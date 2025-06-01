@extends('layouts.main')

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-8 offset-md-2 grid-margin stretch-card">
      <div class="card">
        <div class="card-body text-center">
          <h4 class="card-title">Profil Saya</h4>

          <img src="{{ $user->foto_profile ? asset('storage/uploads/profiles/' . $user->foto_profile) : asset('foto/default.jpg') }}"
               class="rounded-circle mb-3"
               width="100"
               alt="Foto Profil">

          <p><strong>Username:</strong> {{ $user->username }}</p>
          <p><strong>Nama:</strong> {{ $user->nama }}</p>

          <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
