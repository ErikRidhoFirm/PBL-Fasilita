<div class="modal-dialog modal-lg" style="width: 75%;">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h4 class="modal-title text-white">Detail Data Laporan Fasilitas</h4>
            <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @empty($laporan && $laporanFasilitas)
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h4><i class="icon fas fa-ban"></i> Kesalahan!!!</h4>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/laporan') }}" class="btn btn-warning">Kembali</a>
            </div>
        @else
            <div class="modal-body">
                <table class="w-50">
                    <tr>
                        <th>Nama Pelapor</th>
                        <th>:</th>
                        <td>{{ $laporan->pengguna->username }}</td>
                    </tr>
                    <tr>
                        <th>Gedung</th>
                        <th>:</th>
                        <td>{{ $laporan->gedung->nama_gedung }}</td>
                    </tr>
                    <tr>
                        <th>Lantai</th>
                        <th>:</th>
                        <td>{{ $laporan->lantai->nomor_lantai }}</td>
                    </tr>
                    <tr>
                        <th>Ruangan</th>
                        <th>:</th>
                        <td>{{ $laporan->ruangan->nama_ruangan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <th>:</th>
                        <td>{{ $laporan->created_at }}</td>
                    </tr>

                </table>
                <h3 class="my-4">Detail Laporan Fasilitas</h3>
                <div class="px-4 py-2 border border-secondary rounded-lg" style="overflow-y: scroll; height: 300px">
                    @foreach ($laporan->laporanFasilitas as $lf)
                        <div class="row my-2 py-3 border border-dark rounded-lg shadow-lg">
                            <div class="col-3"><img src="{{ asset('storage/' . $lf->path_foto) }}"
                                    class="w-100 h-100 border rounded-lg" alt=""
                                    style="overflow: hidden; object-fit: cover; object-position: center">
                                </div>
                            <div class="col-9">
                                <table class="">
                                    <tr>
                                        <th>Nama Fasilitas</th>
                                        <th class="px-4">: </th>
                                        <td>{{ $lf->fasilitas->nama_fasilitas }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori Kerusakan</th>
                                        <th class="px-4">: </th>
                                        <td>{{ $lf->kategoriKerusakan->nama_kerusakan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Kerusakan</th>
                                        <th class="px-4">: </th>
                                        <td>{{ $lf->jumlah_rusak }}</td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <th class="px-4">: </th>
                                        <td>{{ $lf->deskripsi }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <th class="px-4">: </th>
                                        <td>{{ $lf->status->nama_status }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        @endempty
    </div>
</div>
