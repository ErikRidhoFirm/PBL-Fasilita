@extends('layouts.main')

@section('content')
    <div class="w-100 grid-margin stretch-card">
        <div class="card">
            <div class="card-body w-auto">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title my-5 w-25">Data Kategori Fasilitas</h3>
                <div>
                <button class="btn btn-danger btn-sm mr-2" style="min-width: 80px; height: 40px;">
                    <a href="{{ route('kategoriF.export_pdf') }}"
                    class="text-white text-decoration-none d-flex align-items-center justify-content-center w-100 h-100"
                    target="_blank">
                        <i class="fa fa-file-pdf mr-1"></i> PDF
                    </a>
                </button>
                <button class="btn btn-success btn-sm mr-2"
                        onclick="modalAction('{{ route('kategoriF.import') }}')"
                        style="min-width: 100px; height: 40px;">
                    <i class="fa fa-file-import mr-1"></i> Import
                </button>
                <button class="btn btn-primary btn-sm"
                        onclick="modalAction('{{ route('kategoriF.create') }}')"
                        style="min-width: 120px; height: 40px;">
                    Tambah Kategori Fasilitas
                </button>
            </div>
            </div>
                <div class="table-responsive">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <table class="table table-hover table-striped" id="table-kategori">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Kategori</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
@endsection

@push('css')

@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var tableKategori;
        $(document).ready(function() {
            tableKategori = $('#table-kategori').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('kategori-fasilitas/list') }}",
                    "dataType": "json",
                    "type": "GET"
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_kategori',
                        name: 'kode_kategori'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ]
            })
        })
    </script>
@endpush
