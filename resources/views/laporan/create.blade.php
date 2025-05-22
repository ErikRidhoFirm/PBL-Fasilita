@extends('layouts.main')

@section('content')
    <div class="w-100 grid-margin stretch-card">
        <div class="card">
            <div class="card-body w-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title my-2 w-25"></h3>
                </div>
                <a href="{{ route('laporan.index') }}" class="btn"><i class="mdi mdi-arrow-left"> Kembali</i></a>

                <h3 class="my-2 mx-4">Silakan lengkapi form di bawah ini dengan jelas dan detail.</h3>
                <p class="my-3 mx-4">Data yang Anda isi akan membantu tim sarana dan prasarana kampus dalam menindaklanjuti
                    laporan secara
                    cepat dan tepat. Pastikan Anda menyertakan lokasi, jenis fasilitas, serta deskripsi kerusakan atau
                    masalah yang ditemukan.</p>
                <form class="my-5 col-9 w-100" id="form-tambah" method="post">
                    @csrf
                    <input type="hidden" name="id_pengguna" value="{{ $authUser->id_pengguna }}">
                    <select class="form-control text-dark" id="inputGedung" name="id_gedung" required>
                        <option value="">Pilih Gedung</option>
                        @foreach ($gedung as $g)
                            <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                        @endforeach
                    </select>
                    {{-- Lantai & Ruangan --}}
                    <div class="my-5 d-flex justify-content-between gap-5" style="gap: 20px">
                        <select class="form-control text-dark" id="inputLantai" name="id_lantai" disabled required>
                            <option value="">Pilih Lantai</option>
                        </select>

                        <select class="form-control text-dark" id="inputRuangan" name="id_ruangan" disabled required>
                            <option value="">Pilih Ruangan</option>
                        </select>
                    </div>
                    {{-- Container --}}
                    <div class="px-4 py-2 border border-secondary rounded-lg d-flex flex-column justify-content-center"
                        style="min-height: 200px">
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
                        <select class="form-control text-dark" id="inputKategori" name="idKerusakan" required>
                            <option value="">Pilih Kategori Kerusakan</option>
                            @foreach ($kategoriKerusakan as $kk)
                                <option value="{{ $kk->id_kategori_kerusakan }}">{{ $kk->nama_kerusakan }}</option>
                            @endforeach
                        </select>
                        <div class="my-2 d-flex justify-content-between " style="gap: 20px">
                            <select class="form-control text-dark" id="inputFasilitas" name="idFasilitas" required>
                                <option value="">Pilih Fasilitas</option>
                            </select>
                            <input type="number" name="jumlah_kerusakan" id="jumlahKerusakan" class="form-control" placeholder="Jumlah Kerusakan"
                                min="1" max="" required>
                        </div>
                        <h5 for="" class="mt-5">Bukti Foto</h5>
                        <input type="file" name="" id="inputFoto" class="form-control" required>
                        <h5 for="" class="mt-5">Deskripsi</h5>
                        <textarea name="deskripsi" id="inputDeskripsi" cols="30" rows="5" class="form-control"
                            placeholder="Masukkan Deskripsi Kerusakan" required></textarea>
                    </div>
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
        let fileArray = [];
        // form select change
        $(function() {
            $('#inputGedung').on('change', function() {
                const idGedung = $(this).val();
                const $inputLantai = $('#inputLantai');
                const $inputRuangan = $('#inputRuangan');

                $inputLantai.empty().append('<option value="">Pilih Lantai</option>').prop('disabled',
                    true);
                $inputRuangan.empty().append('<option value="">Pilih Ruangan</option>').prop('disabled',
                    true);

                if (idGedung) {
                    $.get(`/laporan/get-lantai/${idGedung}`, function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            $inputLantai.prop('disabled', false);
                            data.forEach(item => {
                                $inputLantai.append(
                                    `<option value="${item.id_lantai}">${item.nomor_lantai}</option>`
                                );
                            });
                        }
                    });
                }
            });

            $('#inputLantai').on('change', function() {
                const idLantai = $(this).val();
                const $inputRuangan = $('#inputRuangan');

                $inputRuangan.empty().append('<option value="">Pilih Ruangan</option>').prop('disabled',
                    true);

                if (idLantai) {
                    $.get(`/laporan/get-ruangan/${idLantai}`, function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            $inputRuangan.prop('disabled', false);
                            data.forEach(item => {
                                $inputRuangan.append(
                                    `<option value="${item.id_ruangan}">${item.nama_ruangan}</option>`
                                );
                            });
                        }
                    });
                }
            });


            $('#inputRuangan').on('change', function() {
                const idRuangan = $(this).val();
                const $inputFasilitas = $('#inputFasilitas');
                const $jumlahKerusakan = $('#jumlahKerusakan');

                $inputFasilitas.empty().append('<option value="">Pilih Fasilitas</option>');
                $jumlahKerusakan.val('').removeAttr('max'); // Reset max saat ruangan diganti

                if (idRuangan) {
                    $.get(`/laporan/get-fasilitas/${idRuangan}`, function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(item => {
                                // Misalnya item.jumlah berisi jumlah maksimal fasilitas
                                $inputFasilitas.append(
                                    `<option value="${item.id_fasilitas}" data-max="${item.jumlah_fasilitas}">${item.nama_fasilitas}</option>`
                                );
                            });
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

        // modal form
        $('#simpanPelaporan').on('click', (e) => {
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

            if (!inputLaporanModal.idKerusakan || !inputLaporanModal.idFasilitas || !inputLaporanModal.deskripsi || !inputLaporanModal.foto || !inputLaporanModal.jumlahKerusakan) {
                alert("Semua field wajib diisi.");
                return;
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

        $('#laporan-fasilitas').on('click', '.btnHapusRow', function() {
            $(this).closest('section').remove();
        });

        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $('#laporan-fasilitas section').each(function(index) {
                formData.append('id_fasilitas[]', $(this).find('input[name="id_fasilitas[]"]').val());
                formData.append('id_kategori_kerusakan[]', $(this).find('input[name="id_kategori_kerusakan[]"]').val());
                formData.append('jumlah_rusak[]', $(this).find('input[name="jumlah_rusak[]"]').val());
                formData.append('deskripsi[]', $(this).find('input[name="deskripsi[]"]').val());
                // file object harus disimpan dari JS array yang kamu pegang sendiri
                formData.append('path_foto[]', fileArray[index]);
            });

            $.ajax({
                url: '/laporan/store',
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
                        window.location.href = '/laporan';
                    })
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: err.message
                    });
                }
            });
        });
    </script>
@endpush
