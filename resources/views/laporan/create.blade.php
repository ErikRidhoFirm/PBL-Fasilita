@extends('layouts.main')

@section('content')
    <div class="w-100 grid-margin stretch-card">
        <div class="card">
            <div class="card-body w-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title my-2 w-25"></h3>
                </div>
                @if($authUser->peran->kode_peran === 'ADM')
                    <a href="{{ route('laporan.index') }}" class="btn"><i class="mdi mdi-arrow-left"> Kembali</i></a>
                @else
                    <a href="{{ route('laporanPelapor.index') }}" class="btn"><i class="mdi mdi-arrow-left"> Kembali</i></a>
                @endif
                <h3 class="my-2 mx-4">Silakan lengkapi form di bawah ini dengan jelas dan detail.</h3>
                <p class="my-3 mx-4">Data yang Anda isi akan membantu tim sarana dan prasarana kampus dalam menindaklanjuti
                    laporan secara
                    cepat dan tepat. Pastikan Anda menyertakan lokasi, jenis fasilitas, serta deskripsi kerusakan atau
                    masalah yang ditemukan.</p>
                <form class="my-5 col-9 w-100" id="form-tambah" method="post">
                    @csrf
                    <input type="hidden" name="id_pengguna" value="{{ $authUser->id_pengguna }}">
                    <div class="form-group">
                        <select class="form-control text-dark border-primary" id="inputGedung" name="id_gedung">
                            <option value="">Pilih Gedung</option>
                            @foreach ($gedung as $g)
                                <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_gedung" class="error-text form-text text-danger"></small>
                    </div>

                    {{-- Lantai & Ruangan --}}
                    <div class="my-5 d-flex justify-content-between gap-5" style="gap: 20px">
                        <div class="form-group w-100">
                            <select class="form-control text-dark " id="inputLantai" name="id_lantai" disabled>
                                <option value="">Pilih Lantai</option>
                            </select>
                            <small id="error-id_lantai" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group w-100">
                            <select class="form-control text-dark " id="inputRuangan" name="id_ruangan" disabled>
                                <option value="">Pilih Ruangan</option>
                            </select>
                            <small id="error-id_ruangan" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                    {{-- Container --}}
                    <div class="px-4 py-2 border border-primary rounded-lg d-flex flex-column justify-content-center"
                        style="min-height: 200px" id="container-fasilitas">
                        <div id="laporan-fasilitas">

                        </div>
                        <div class="w-100 d-flex justify-content-center align-items-center">
                            <button type="button" class="btn py-2 w-100 btn-primary my-5" id="btn-tambah-row"
                                onclick="modalAction()">
                                <span class="bg-light p-1 rounded-lg text-primary p-1 btn mx-3">
                                    <i class="mdi mdi-plus"></i>
                                </span>
                                Tambah Pelaporan
                            </button>
                        </div>
                    </div>
                    <small id="error-fasilitas-row" class="error-text form-text text-danger"></small>

                    <button type="submit" class="btn btn-lg w-100 my-5 btn-primary">
                        <h4 class="my-0">Simpan</h4>
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
        <div class="modal-dialog modal-lg w-50">
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

                $inputFasilitas.empty().append('<option value="">Pilih Fasilitas</option>');
                $jumlahKerusakan.val('').removeAttr('max'); // Reset max saat ruangan diganti

                if (idRuangan) {
                    $.get(`${baseURL}/${laporanPrefix}/get-fasilitas/${idRuangan}`, function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            $inputFasilitas.prop('disabled', false);
                            $inputFasilitas.addClass('border-primary');
                            data.forEach(item => {
                                // Misalnya item.jumlah berisi jumlah maksimal fasilitas
                                $inputFasilitas.append(
                                    `<option value="${item.id_fasilitas}" data-max="${item.jumlah_fasilitas}">${item.nama_fasilitas}</option>`
                                );
                            });
                        } else {
                            $inputFasilitas.prop('disabled', true);
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
                <section class="row my-2 mx-1 py-3 border border-dark rounded-lg shadow-lg">
                    <div class="col-3">
                        <img src="${imageSrc}" class="w-100 h-100 border rounded-lg" alt=""
                        style="overflow: hidden; object-fit: cover; object-position: center">
                    <input type="hidden" name="path_foto[]" class="border-0" value="${imageSrc}"></input>
                    </div>
                    <div class="col-8">
                        <table>
                            <tr>
                                <th>Nama Fasilitas</th>
                                <th class="px-4">:</th>
                                <td>${textFasilitas}</td>
                                <td><input type="hidden" name="id_fasilitas[]" class="border-0" value="${fasilitas}"></td>
                            </tr>
                            <tr>
                                <th>Kategori Kerusakan</th>
                                <th class="px-4">:</th>
                                <td>${textKerusakan}</td>
                                <td><input type="hidden" name="id_kategori_kerusakan[]" class="border-0" value="${kategori}"></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <th class="px-4">:</th>
                                <td>${deskripsi}</td>
                                <td><input type="hidden" name="deskripsi[]" class="border-0" value="${deskripsi}"></td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <th class="px-4">:</th>
                                <td>${jumlahRusak}</td>
                                <td><input type="hidden" name="jumlah_rusak[]" class="border-0" value="${jumlahRusak}"></td>
                            </tr>
                        </table>
                        </div>
                        <div class="col-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm h-25 btnHapusRow" style="height: 30px">
                                &times;
                            </button>
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
            $('#error-fasilitas-row').text('At least one report must be added.');
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
