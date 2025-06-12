@extends('layouts.main')

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
          <div class="col-12 col-md-6 text-md-right">
            <h3 class="mb-0">Silakan lengkapi form di bawah ini dengan jelas dan detail.</h3>
          </div>
        </div>

        <p class="mb-4">
          Data yang Anda isi akan membantu tim sarana dan prasarana kampus dalam menindaklanjuti laporan secara
          cepat dan tepat. Pastikan Anda menyertakan lokasi, jenis fasilitas, serta deskripsi kerusakan atau
          masalah yang ditemukan.
        </p>

        <div class="row">
          <div class="col-12 col-lg-9">
            <form id="form-tambah" class="my-4" method="post">
              @csrf
              <input type="hidden" name="id_pengguna" value="{{ $authUser->id_pengguna }}">

              {{-- Gedung --}}
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

              {{-- Lantai & Ruangan --}}
              <div class="form-row">
                <div class="form-group col-12 col-md-6">
                  <label>Pilih Lantai</label>
                  <select class="form-control" id="inputLantai" name="id_lantai" disabled>
                    <option value="">-- Pilih Lantai --</option>
                  </select>
                  <small id="error-id_lantai" class="text-danger"></small>
                </div>
                <div class="form-group col-12 col-md-6">
                  <label>Pilih Ruangan</label>
                  <select class="form-control" id="inputRuangan" name="id_ruangan" disabled>
                    <option value="">-- Pilih Ruangan --</option>
                  </select>
                  <small id="error-id_ruangan" class="text-danger"></small>
                </div>
              </div>

              {{-- Container Pelaporan --}}
              <div class="border border-primary rounded-lg p-3 mb-3" id="container-fasilitas">
                <div id="laporan-fasilitas"></div>
                <div class="text-center mt-3">
                  <button
                    type="button"
                    class="btn btn-outline-primary w-100 w-sm-auto"
                    id="btn-tambah-row"
                    onclick="modalAction()"
                  >
                    <i class="mdi mdi-plus"></i> Tambah Pelaporan
                  </button>
                </div>
              </div>
              <small id="error-fasilitas-row" class="text-danger"></small>

              {{-- Submit --}}
              <button type="submit" class="btn btn-primary btn-lg btn-block">
                Simpan
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
    {{-- Modal --}}
    <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title">Isi Laporan Fasilitas</h5>
                    <button type="button" class="btn close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <form id="form-tambah-modal">
                    <div class="modal-body py-3">
                        <p class="my-4">
                            Mohon isi form ini dengan sebenar-benarnya, jelas, dan detail, Informasi yang tidak akurat atau
                            dibuat-buat dapat menghambat proses perbaikan fasilitas. Pastikan laporan sesuai dengan kondisi
                            sebenarnya agar dapat segera ditindaklanjuti oleh tim kami.
                        </p>
                        <div class="form-group">
                            <select class="form-control text-dark border-primary" id="inputKategori"
                                name="id_kategori_kerusakan">
                                <option value="">Pilih Kategori Kerusakan</option>
                                @foreach ($kategoriKerusakan as $kk)
                                    <option value="{{ $kk->id_kategori_kerusakan }}">{{ $kk->nama_kerusakan }}</option>
                                @endforeach
                            </select>
                            <small id="error-id_kategori_kerusakan" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="my-2 d-flex justify-content-between " style="gap: 20px">
                            <div class="form-group w-100">
                                <select class="form-control text-dark" id="inputFasilitas" name="id_fasilitas">
                                    <option value="">Pilih Fasilitas</option>
                                </select>
                                <small id="error-id_fasilitas" class="error-text form-text text-danger"></small>
                            </div>
                            <div class="form-group w-100">
                                <input type="number" name="jumlah_kerusakan" id="jumlahKerusakan"
                                    class="form-control border-primary" placeholder="Jumlah Kerusakan" min="1"
                                    max="">
                                <small id="error-jumlah_kerusakan" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                        <div class="form-group w-100">
                            <h5 for="" class="mt-5">Bukti Foto</h5>
                            <input type="file" name="path_foto" id="inputFoto" class="form-control border-primary">
                            <small id="error-path_foto" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="form-group w-100">
                            <h5 for="" class="mt-5">Deskripsi</h5>
                            <textarea name="deskripsi" id="inputDeskripsi" cols="30" rows="5" class="form-control border-primary"
                                placeholder="Masukkan Deskripsi Kerusakan"></textarea>
                            <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                        </div>

                    </div>
                    <small id="error-lantai-ruangan" class="error-text form-text text-danger"></small>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="simpanPelaporan">Simpan</button>
                    </div>
                </form>
            </div>
    </div>
  </div>
@endsection
@push('css')
@endpush
@push('js')

    <script>
        // Modal
        function modalAction() {
            $('#myModal').modal('show');
        }

                    // Tentukan prefix path berdasarkan role:
            const laporanPrefix = "{{ $authUser->peran->kode_peran === 'ADM' ? 'laporan' : 'laporanPelapor' }}";
            // Base URL (pastikan meta[name=base-url] ada di <head>)
            const baseURL = $('meta[name="base-url"]').attr('content');
        let fileArray = [];
        // form select change
        $(function() {



            // Gedung
            $('#inputGedung').on('change', function() {
                const idGedung = $(this).val();
                const $inputLantai = $('#inputLantai');
                const $inputRuangan = $('#inputRuangan');

                $inputLantai.empty().append('<option value="">Pilih Lantai</option>').prop('disabled',
                    true);
                $inputRuangan.empty().append('<option value="">Pilih Ruangan</option>').prop('disabled',
                    true);

                if (idGedung) {
                    $.get(`${baseURL}/${laporanPrefix}/get-lantai/${idGedung}`, function(data) {
                        console.log(`${baseURL}/${laporanPrefix}/get-lantai/${idGedung}`);

                        if (Array.isArray(data) && data.length > 0) {
                            $inputLantai.prop('disabled', false);
                            $inputLantai.addClass('border-primary');
                            data.forEach(item => {
                                $inputLantai.append(
                                    `<option value="${item.id_lantai}">${item.nomor_lantai}</option>`
                                );
                            });
                        } else {
                            $inputLantai.prop('disabled', true);
                            $inputLantai.removeClass('border-primary');
                        }
                    });
                }
            });

            // Lantai
            $('#inputLantai').on('change', function() {
                const idLantai = $(this).val();
                const $inputRuangan = $('#inputRuangan');

                $inputRuangan.empty().append('<option value="">Pilih Ruangan</option>').prop('disabled',
                    true);

                if (idLantai) {
                    $.get(`${baseURL}/${laporanPrefix}/get-ruangan/${idLantai}`, function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            $inputRuangan.prop('disabled', false);
                            $inputRuangan.addClass('border-primary');
                            data.forEach(item => {
                                $inputRuangan.append(
                                    `<option value="${item.id_ruangan}">${item.nama_ruangan}</option>`
                                );
                            });
                        } else {
                            $inputRuangan.prop('disabled', true);
                            $inputRuangan.removeClass('border-primary');
                        }
                    });
                }
            });

            // Ruangan
            $('#inputRuangan').on('change', function() {
                const idRuangan = $(this).val();
                const $inputFasilitas = $('#inputFasilitas');
                const $jumlahKerusakan = $('#jumlahKerusakan');
                const simpanPelaporan = $('#simpanPelaporan');

                $inputFasilitas.empty().append('<option value="">Pilih Fasilitas</option>');
                $jumlahKerusakan.val('').removeAttr('max'); // Reset max saat ruangan diganti

                if (idRuangan) {
                    $.get(`${baseURL}/${laporanPrefix}/get-fasilitas/${idRuangan}`, function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            $inputFasilitas.prop('disabled', false);
                            simpanPelaporan.prop('disabled', false);
                            $inputFasilitas.addClass('border-primary');
                            data.forEach(item => {
                                // Misalnya item.jumlah berisi jumlah maksimal fasilitas
                                $inputFasilitas.append(
                                    `<option value="${item.id_fasilitas}" data-max="${item.jumlah_fasilitas}">${item.nama_fasilitas}</option>`
                                );
                            });
                        } else {
                            $inputFasilitas.prop('disabled', true);
                            simpanPelaporan.prop('disabled', true);
                            $inputFasilitas.removeClass('border-primary');
                        }
                    });
                }
            });

            // Atur max input ketika fasilitas dipilih
            $('#inputFasilitas').on('change', function() {
                const max = $('option:selected', this).data('max');
                const $jumlahKerusakan = $('#jumlahKerusakan');

                if (max) {
                    $jumlahKerusakan.attr('max', max);
                } else {
                    $jumlahKerusakan.removeAttr('max');
                }
            });
        });

        $('#laporan-fasilitas').css({
            display: 'flex',
            flexWrap: 'nowrap',
            overflowX: 'auto',
            gap: '1rem',
            paddingBottom: '0.5rem'
        });

        // validasi form
        $(function() {
            $("#form-tambah").validate({
                rules: {
                    id_gedung: {
                        required: true,
                    },
                    id_lantai: {
                        required: true,
                    },
                    id_ruangan: {
                        required: true,
                    }
                },
                messages: {
                  id_gedung: {
                    required: 'Gedung harus dipilih',
                  },
                  id_lantai: {
                    required: 'Lantai harus dipilih',
                  },
                  id_ruangan: {
                    required: 'Ruangan harus dipilih',
                  }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('border-danger');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('border-danger');
                }
            })
        })

        // validasi form modal
        $(function() {
            $('#form-tambah-modal').validate({
                rules: {
                    id_kategori_kerusakan: {
                        required: true
                    },
                    id_fasilitas: {
                        required: true
                    },
                    jumlah_kerusakan: {
                        required: true,
                        number: true,
                        min: 1,
                        max: function() {
                            return $('#inputFasilitas option:selected').data('max') || 999;
                        }
                    },
                    path_foto: {
                        required: true
                    },
                    deskripsi: {
                        required: true
                    }
                },
                messages: {
                    id_kategori_kerusakan: {
                        required: 'Kategori kerusakan harus dipilih'
                    },
                    id_fasilitas: {
                        required: 'Fasilitas harus dipilih'
                    },
                    jumlah_kerusakan: {
                        required: 'Jumlah kerusakan harus diisi',
                        number: 'Jumlah kerusakan harus angka',
                        min: 'Jumlah kerusakan minimal 1',
                        max: 'Jumlah kerusakan maksimal {0}'
                    },
                    path_foto: {
                        required: 'Foto harus diisi'
                    },
                    deskripsi: {
                        required: 'Deskripsi harus diisi'
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('border-danger');
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-danger');
                }
            });
        });

        // modal form
        $('#simpanPelaporan').on('click', (e) => {

            const isValidModal = $('#form-tambah-modal').valid();
            if (!isValidModal) return;

            e.preventDefault();
            let inputLaporanModal = {
                idKerusakan: $('#inputKategori').val(),
                textKerusakan: $('#inputKategori option:selected').text(),
                idFasilitas: $('#inputFasilitas').val(),
                textFasilitas: $('#inputFasilitas option:selected').text(),
                jumlahKerusakan: $('#jumlahKerusakan').val(),
                foto: $('#inputFoto')[0].files[0],
                deskripsi: $('#inputDeskripsi').val(),
            }

            fileArray.push(inputLaporanModal.foto);
            tambahRow(inputLaporanModal);
            $('#form-tambah-modal')[0].reset();
            $('#myModal').modal('hide');
        })

        // row laporan detail
        function tambahRow(item) {
            const fasilitas = item.idFasilitas;
            const textFasilitas = item.textFasilitas;
            const kategori = item.idKerusakan;
            const jumlahRusak = item.jumlahKerusakan
            const textKerusakan = item.textKerusakan;
            const deskripsi = item.deskripsi;
            const imageSrc = URL.createObjectURL(item.foto);
            let cardHtml = `
            <section class="border border-dark rounded-lg shadow-lg flex-shrink-0" style="min-width: 300px;">
            <div class="row no-gutters">
                <div class="col-4">
                <img src="${imageSrc}"
                    class="w-100 h-100 rounded-lg"
                    style="object-fit:cover"
                    alt="Foto">
                <input type="hidden" name="path_foto[]" value="${imageSrc}">
                </div>
                <div class="col-7 px-2">
                <table class="table table-sm mb-0">
                    <tr>
                    <th>Fasilitas</th><td>${textFasilitas}</td>
                    <input type="hidden" name="id_fasilitas[]" value="${fasilitas}">
                    </tr>
                    <tr>
                    <th>Kategori</th><td>${textKerusakan}</td>
                    <input type="hidden" name="id_kategori_kerusakan[]" value="${kategori}">
                    </tr>
                    <tr>
                    <th>Deskripsi</th><td>${deskripsi}</td>
                    <input type="hidden" name="deskripsi[]" value="${deskripsi}">
                    </tr>
                    <tr>
                    <th>Jumlah</th><td>${jumlahRusak}</td>
                    <input type="hidden" name="jumlah_rusak[]" value="${jumlahRusak}">
                    </tr>
                </table>
                </div>
                <div class="col-1 d-flex align-items-center justify-content-center">
                <button type="button" class="btn btn-danger btn-sm btnHapusRow">&times;</button>
                </div>
            </div>
            </section>
        `;
        $('#laporan-fasilitas').append(cardHtml);
        }

        // hapus row
        $('#laporan-fasilitas').on('click', '.btnHapusRow', function() {
            $(this).closest('section').remove();
        });

        // tambah laporan
       $('#form-tambah').on('submit', function(e) {
        e.preventDefault();

        const isValid = $('#form-tambah').valid();
        if (!isValid) return;

        if ($('#laporan-fasilitas').children().length === 0) {
            e.preventDefault();
            $('#container-fasilitas').removeClass('border-primary');
            $('#container-fasilitas').addClass('border-danger');
            $('#error-fasilitas-row').text('Buat laporan terlebih dahulu');
            $('html, body').animate({
                scrollTop: $('#error-fasilitas-row').offset().top - 100
            }, 500);
            return false;
        } else {
            $('#error-fasilitas-row').text('');
            $('#container-fasilitas').removeClass('border-danger');
            $('#container-fasilitas').addClass('border-primary');
        }

        const formData = new FormData(this);

        $('#laporan-fasilitas section').each(function(index) {
            formData.append('id_fasilitas[]', $(this).find('input[name="id_fasilitas[]"]').val());
            formData.append('id_kategori_kerusakan[]', $(this).find(
                'input[name="id_kategori_kerusakan[]"]').val());
            formData.append('jumlah_rusak[]', $(this).find('input[name="jumlah_rusak[]"]').val());
            formData.append('deskripsi[]', $(this).find('input[name="deskripsi[]"]').val());
            formData.append('path_foto[]', fileArray[index]);
        });

        submitUrl = `${baseURL}/${laporanPrefix}/store`;
        const indexUrl = `${baseURL}/${laporanPrefix}`;

        $.ajax({
            url: submitUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Laporan berhasil ditambahkan'
                }).then(function() {
                    window.location.href = indexUrl;
                })
            },
            error: function(err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: err.responseJSON?.message || 'Terjadi kesalahan saat menyimpan laporan'
                });
            }
        });
    });
    </script>
@endpush
