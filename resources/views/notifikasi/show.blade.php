<div class="modal fade" id="modalDetail{{ $item->id_notifikasi }}" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel{{ $item->id_notifikasi }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 800px;"> <!-- Sesuaikan max-width sesuai kebutuhan -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{ $item->id_notifikasi }}">Detail Notifikasi</h5>
                <button type="button" class="btn close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @empty($item)
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                        Data notifikasi tidak ditemukan.
                    </div>
                    <a href="{{ route('notifikasi.index') }}" class="btn btn-warning">Kembali</a>
                </div>
            @else
                <div class="modal-body" style="overflow-x: auto;"> <!-- Tambahkan overflow untuk konten panjang -->
                    <div class="table-responsive"> <!-- Tambahkan wrapper responsive -->
                        <table class="table table-bordered table-striped" style="min-width: 100%;"> <!-- Pastikan tabel mengambil lebar penuh -->
                            <tr>
                                <th style="width: 20%;">Judul</th> <!-- Atur lebar kolom -->
                                <td style="width: 80%; word-break: break-word;">{{ $item->judul }}</td> <!-- Tambahkan word-break -->
                            </tr>
                            @if($item->laporanFasilitas)
                                <tr>
                                    <th>Fasilitas</th>
                                    <td style="word-break: break-word;">{{ optional($item->laporanFasilitas->fasilitas)->nama_fasilitas }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td style="word-break: break-word;">{{ optional($item->laporanFasilitas->status)->nama_status }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Pesan</th>
                                <td style="word-break: break-word;">{{ $item->pesan }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Dibuat</th>
                                <td>
                                    <i class="ti-time mr-1"></i>
                                    {{ $item->created_at->format('l, d F Y \p\u\k\u\l H:i') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    @if(!$item->is_read)
                        <form action="{{ route('notifikasi.markAsRead', $item->id_notifikasi) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-check mr-1"></i>
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            @endempty
        </div>
    </div>
</div>

<style>
    .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .table td, .table th {
        vertical-align: middle;
    }
</style>
