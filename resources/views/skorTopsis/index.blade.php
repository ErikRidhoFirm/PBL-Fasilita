@extends('layouts.main')

@section('content')
<div class="w-100 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label for="filter-role">Filter Peran:</label>
                <select id="filter-role" class="form-control w-25">
                    <option value="">Semua Peran</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id_peran }}">{{ $role->nama_peran }}</option>
                    @endforeach
                </select>
            </div>

            <div class="table-responsive">
                <table id="tbl-prioritas" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alternatif</th>
                            <th>Pelapor</th>
                            <th>Peran</th>
                            <th>Skor Ci</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal container --}}
<div id="modalContainer" class="modal fade" tabindex="-1" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    let table;
    $(function() {
        // Inisialisasi DataTables
        table = $('#tbl-prioritas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('skorTopsis.list') }}",
                data: function(d) {
                    d.role_id = $('#filter-role').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'alternatif' },
                { data: 'pelapor' },
                { data: 'peran' },
                { data: 'skor' },
                { data: 'aksi', orderable: false, searchable: false }
            ],
            order: [[4, 'desc']],
            language: {
                processing: "Memproses...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari total _MAX_ data)",
                loadingRecords: "Memuat...",
                zeroRecords: "Tidak ada data yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Event handler untuk filter peran
        $('#filter-role').on('change', function() {
            table.ajax.reload();
        });

        // tombol Tugaskan: load form + inisialisasi modal
         $('#tbl-prioritas').on('click','.assign-form', function(){
            let url = $(this).data('url');
            $('#modalContainer').load(url, function(){
            // this = <div id="modalContainer" class="modal fade">
            assignModal = new bootstrap.Modal(this);
            assignModal.show();
            });
        });
    });
</script>
@endpush
