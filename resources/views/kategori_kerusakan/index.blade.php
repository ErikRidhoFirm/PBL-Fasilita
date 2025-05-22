@extends('layouts.main')

@section('content')
    <div class="w-100 grid-margin stretch-card">
        <div class="card">
            <div class="card-body w-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title my-5 w-25">Data Kategori Kerusakan</h3>
                    <div>
                        <button class="btn btn-danger btn-sm mr-2" style="min-width: 80px; height: 40px;">
                            <a href="{{ route('kategori_kerusakan.export_pdf') }}"
                            class="text-white text-decoration-none d-flex align-items-center justify-content-center w-100 h-100"
                            target="_blank">
                                <i class="fa fa-file-pdf mr-1"></i> PDF
                            </a>
                        </button>
                        <button class="btn btn-primary btn-sm" 
                                onclick="modalAction('{{ url('kategori_kerusakan/create') }}')"
                                style="min-width: 120px; height: 40px;">
                            Tambah Kategori Kerusakan
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
                    <table class="table table-hover table-striped" id="table-kategori-kerusakan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Kategori Kerusakan</th>
                                <th>Nama Kategori Kerusakan</th>
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

        var tablePeran;
        $(document).ready(function() {
            tablePeran = $('#table-kategori-kerusakan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('kategori_kerusakan/list') }}",
                    "dataType": "json",
                    "type": "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_kerusakan',
                        name: 'kode_kerusakan',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'nama_kerusakan',
                        name: 'nama_kerusakan',
                        orderable: true,
                        searchable: true
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
