{{-- resources/views/laporan/create.blade.php --}}
@extends('layouts.main')

@push('css')
<style>
  @keyframes pop {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.3); }
    100% { transform: scale(1); }
  }
  .btn-vote-icon {
    transition: color 0.2s ease;
  }
  .animate-pop {
    animation: pop 0.3s ease;
  }
</style>
@endpush

@section('content')
<div class="w-100 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <div class="row mb-3 align-items-center">
        <div class="col-12 col-md-6">
          @if($authUser->peran->kode_peran === 'ADM')
            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-primary mb-2 mb-md-0">
              <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
          @else
            <a href="{{ route('laporanPelapor.index') }}" class="btn btn-sm btn-outline-primary mb-2 mb-md-0">
              <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
          @endif
        </div>
      </div>

      <h3 class="mb-0">Silakan lengkapi form di bawah ini dengan jelas dan detail.</h3>
      <p class="mb-4">
        Data yang Anda isi akan membantu tim sarana & prasarana kampus menindaklanjuti laporan dengan cepat dan tepat.
      </p>

      <div class="row">
        <div class="col-12 col-lg-9">
          <form id="form-tambah" class="my-4" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_pengguna" value="{{ $authUser->id_pengguna }}">

            {{-- Pilih Gedung --}}
            <div class="form-group">
              <label>Pilih Gedung</label>
              <select class="form-control border-primary" id="inputGedung" name="id_gedung">
                <option value="">-- Pilih Gedung --</option>
                @foreach ($gedung as $g)
                  <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                @endforeach
              </select>
              <small id="error-id_gedung" class="text-danger"></small>
            </div>

            {{-- Pilih Lantai & Ruangan --}}
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Pilih Lantai</label>
                <select class="form-control" id="inputLantai" name="id_lantai" disabled>
                  <option value="">-- Pilih Lantai --</option>
                </select>
                <small id="error-id_lantai" class="text-danger"></small>
              </div>
              <div class="form-group col-md-6">
                <label>Pilih Ruangan</label>
                <select class="form-control" id="inputRuangan" name="id_ruangan" disabled>
                  <option value="">-- Pilih Ruangan --</option>
                </select>
                <small id="error-id_ruangan" class="text-danger"></small>
              </div>
            </div>

            {{-- Container Pelaporan --}}
            <div class="border border-primary rounded-lg p-3 mb-3" id="container-fasilitas">
              <div id="laporan-fasilitas" class="d-flex flex-wrap"></div>
              <div class="text-center mt-3">
                <button type="button" class="btn btn-outline-primary w-100 w-sm-auto" onclick="modalAction()">
                  <i class="mdi mdi-plus"></i> Tambah Pelaporan
                </button>
              </div>
            </div>
            <small id="error-fasilitas-row" class="text-danger"></small>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary btn-lg btn-block">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah Detail --}}
<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h5 class="modal-title">Isi Laporan Fasilitas</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-tambah-modal">
        <div class="modal-body py-3">
          <p class="my-4">
            Mohon isi dengan sebenar-benarnya. Informasi akurat membantu proses perbaikan lebih cepat.
          </p>

          {{-- Pilih Fasilitas --}}
          <div class="form-group">
            <label>Pilih Fasilitas</label>
            <select class="form-control" id="inputFasilitas" name="id_fasilitas">
              <option value="">-- Pilih Fasilitas --</option>
            </select>
            <small id="error-id_fasilitas" class="text-danger"></small>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Tingkat Kerusakan</label>
              <select class="form-control" id="inputTingkatKerusakan" name="id_tingkat_kerusakan">
                <option value="">-- Pilih Tingkat Kerusakan --</option>
                @foreach ($kriteriaC1 as $k1)
                  <option value="{{ $k1->id_skoring_kriteria }}">{{ $k1->parameter }}</option>
                @endforeach
              </select>
              <small id="error-id_tingkat_kerusakan" class="text-danger"></small>
            </div>
            <div class="form-group col-md-6">
              <label>Dampak Pengguna</label>
              <select class="form-control" id="inputDampakPengguna" name="id_dampak_pengguna">
                <option value="">-- Pilih Dampak Pengguna --</option>
                @foreach ($kriteriaC4 as $k4)
                  <option value="{{ $k4->id_skoring_kriteria }}">{{ $k4->parameter }}</option>
                @endforeach
              </select>
              <small id="error-id_dampak_pengguna" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group">
            <label>Bukti Foto</label>
            <input type="file" class="form-control" id="inputFoto" name="path_foto">
            <small id="error-path_foto" class="text-danger"></small>
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea class="form-control" id="inputDeskripsi" name="deskripsi" rows="4"></textarea>
            <small id="error-deskripsi" class="text-danger"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" id="simpanPelaporan">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Duplicate Check --}}
<div id="modalDuplicates" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">
          <i class="mdi mdi-alert-circle-outline me-2"></i>
          Laporan Serupa Ditemukan
        </h5>
        <button type="button" class="btn-close" data-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="duplicatesList" class="row gy-3"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  const laporanPrefix = "{{ $authUser->peran->kode_peran === 'ADM' ? 'laporan' : 'laporanPelapor' }}";
  const baseURL       = $('meta[name="base-url"]').attr('content');
  const vote = "{{ $authUser->peran->kode_peran === 'ADM' ? 'vote' : 'vote-pelapor' }}"
  const unVote = "{{ $authUser->peran->kode_peran === 'ADM' ? 'unvote' : 'unvote-pelapor' }}"
  const checkDuplicates = "{{ $authUser->peran->kode_peran === 'ADM' ? 'check-duplicates' : 'check-duplicates-pelapor' }}"
  let fileArray       = [];

  function modalAction() {
    $('#form-tambah-modal')[0].reset();
    $('.text-danger').text('');
    $('.form-control').removeClass('border-danger');
    $('#myModal').modal('show');
  }

  $(function(){
    // Dynamic selects
    $('#inputGedung').change(function(){
      $('#inputLantai,#inputRuangan').prop('disabled',true).empty().append('<option value="">-- Pilih --</option>');
      if(this.value){
        $.get(`${baseURL}/${laporanPrefix}/get-lantai/${this.value}`, data=>{
          $('#inputLantai').prop('disabled',false).html('<option value="">-- Pilih Lantai --</option>');
          data.forEach(i=> $('#inputLantai').append(`<option value="${i.id_lantai}">${i.nomor_lantai}</option>`));
        });
      }
    });
    $('#inputLantai').change(function(){
      $('#inputRuangan').prop('disabled',true).empty().append('<option value="">-- Pilih --</option>');
      if(this.value){
        $.get(`${baseURL}/${laporanPrefix}/get-ruangan/${this.value}`, data=>{
          $('#inputRuangan').prop('disabled',false).html('<option value="">-- Pilih Ruangan --</option>');
          data.forEach(i=> $('#inputRuangan').append(`<option value="${i.id_ruangan}">${i.nama_ruangan}</option>`));
        });
      }
    });
    $('#inputRuangan').change(function(){
      $('#inputFasilitas').prop('disabled',true).empty().append('<option value="">-- Pilih --</option>');
      if(this.value){
        $.get(`${baseURL}/${laporanPrefix}/get-fasilitas/${this.value}`, data=>{
          $('#inputFasilitas').prop('disabled',false).html('<option value="">-- Pilih Fasilitas --</option>');
          data.forEach(i=> $('#inputFasilitas').append(`<option value="${i.id_fasilitas}">${i.nama_fasilitas}</option>`));
        });
      }
    });

    // Simpan detail & cek duplikat
    $('#simpanPelaporan').click(function(e){
      e.preventDefault();
      $('.text-danger').text('');
      $('.form-control').removeClass('border-danger');

      const idGedung    = $('#inputGedung').val();
      const idLantai    = $('#inputLantai').val();
      const idRuangan   = $('#inputRuangan').val();
      const idFasilitas = $('#inputFasilitas').val();

      if(idGedung && idLantai && idRuangan && idFasilitas){
        $.getJSON(`${baseURL}/${laporanPrefix}/${checkDuplicates}`, {
          id_gedung: idGedung,
          id_lantai: idLantai,
          id_ruangan: idRuangan,
          id_fasilitas: idFasilitas
        }, function(res){
          if(res.data.length){
            let html = '';
            res.data.forEach(item => {
              const isVoted = item.voted_by_me;
              html += `
<div class="col-md-6">
  <div class="card">
    <img src="${item.foto_url}" class="card-img-top" style="height:180px;object-fit:cover">
    <div class="card-body">
      <p class="mb-1"><small>${item.created_at} oleh ${item.user_name}</small></p>
      <p class="mb-2">${item.deskripsi}</p>
      <button class="btn btn-sm btn-outline-primary btnVote"
              data-id="${item.id}"
              data-voted="${isVoted}">
        <i class="mdi ${isVoted ? 'mdi-thumb-up' : 'mdi-thumb-up-outline'} btn-vote-icon ${isVoted ? 'text-success' : 'text-dark'} me-1 fs-5"></i>
        <span class="vote-count">${item.votes_count}</span>
      </button>
    </div>
  </div>
</div>`;
            });
            $('#duplicatesList').html(html);
            $('#modalDuplicates').modal('show');
            return;
          }
          // tidak ada duplikat → langsung tambahkan row
          addRow({
            idFasilitas: idFasilitas,
            tingkat:     $('#inputTingkatKerusakan').val(),
            dampak:      $('#inputDampakPengguna').val(),
            foto:        $('#inputFoto')[0].files[0],
            deskripsi:   $('#inputDeskripsi').val()
          });
          $('#myModal').modal('hide');
        });
      } else {
        addRow({
          idFasilitas: idFasilitas,
          tingkat:     $('#inputTingkatKerusakan').val(),
          dampak:      $('#inputDampakPengguna').val(),
          foto:        $('#inputFoto')[0].files[0],
          deskripsi:   $('#inputDeskripsi').val()
        });
        $('#myModal').modal('hide');
      }
    });

    // Vote / Unvote handler
    $(document).on('click', '.btnVote', function(e){
      e.preventDefault();
      const btn = $(this),
            id  = btn.data('id'),
            isV = btn.data('voted') === true,
            icn = btn.find('i.btn-vote-icon'),
            cnt = btn.find('.vote-count'),
            url = isV
              ? `${baseURL}/${laporanPrefix}/${id}/${unVote}`
              : `${baseURL}/${laporanPrefix}/${id}/${vote}`,
            mtd = isV ? 'DELETE' : 'POST';

      $.ajax({
        url: url,
        method: mtd,
        data: { _token: '{{ csrf_token() }}' },
        dataType: 'json',
      })
      .done(res=>{
        if(res.status==='success'){
          cnt.text(res.votes_count);
          btn.data('voted', !isV);
          icn.toggleClass('mdi-thumb-up-outline mdi-thumb-up')
             .toggleClass('text-dark text-success')
             .removeClass('animate-pop')[0].offsetWidth
             && icn.addClass('animate-pop');
        }
      })
      .fail(err=> console.error(err.responseJSON?.message||err.statusText));
    });

    // addRow & submit utama
    function addRow(item){
      fileArray.push(item.foto);
      const imgUrl = URL.createObjectURL(item.foto);
      const html = `
<section class="border border-dark rounded-lg shadow-lg m-2" style="min-width:280px;">
  <div class="row no-gutters">
    <div class="col-4">
      <img src="${imgUrl}" class="w-100 h-100" style="object-fit:cover">
    </div>
    <div class="col-7 px-2">
      <table class="table table-sm mb-0">
        <tr><th>Fasilitas</th><td>${$('#inputFasilitas option:selected').text()}</td></tr>
        <tr><th>Kerusakan</th><td>${$('#inputTingkatKerusakan option:selected').text()}</td></tr>
        <tr><th>Dampak</th><td>${$('#inputDampakPengguna option:selected').text()}</td></tr>
        <tr><th>Deskripsi</th><td>${item.deskripsi}</td></tr>
      </table>
      <input type="hidden" name="id_fasilitas[]" value="${item.idFasilitas}">
      <input type="hidden" name="id_tingkat_kerusakan[]" value="${item.tingkat}">
      <input type="hidden" name="id_dampak_pengguna[]" value="${item.dampak}">
      <input type="hidden" name="deskripsi[]" value="${item.deskripsi}">
    </div>
    <div class="col-1 d-flex align-items-center justify-content-center">
      <button type="button" class="btn btn-danger btn-sm btnHapusRow">&times;</button>
    </div>
  </div>
</section>`;
      $('#laporan-fasilitas').append(html);
    }

    $('#laporan-fasilitas').on('click','.btnHapusRow',function(){
      const idx = $(this).closest('section').index();
      fileArray.splice(idx,1);
      $(this).closest('section').remove();
    });

    $('#form-tambah').submit(function(e){
      e.preventDefault();
      if($('#laporan-fasilitas section').length===0){
        $('#container-fasilitas').addClass('border-danger');
        $('#error-fasilitas-row').text('Buat laporan terlebih dahulu');
        return;
      }
      const fd = new FormData(this);
      fileArray.forEach(f=>fd.append('path_foto[]',f));
      $.ajax({
        url: `${baseURL}/${laporanPrefix}/store`,
        method: 'POST',
        data: fd,
        processData: false, contentType: false,
        success(){
          Swal.fire('Berhasil','Laporan berhasil ditambahkan','success')
            .then(()=> location.href=`${baseURL}/${laporanPrefix}`);
        },
        error(err){
          Swal.fire('Error',err.responseJSON?.message||'Gagal simpan','error');
        }
      });
    });

  });
</script>
@endpush
