@extends('layouts.main')

@section('content')
    <div class="w-100 grid-margin stretch-card">
        <div class="card">
            <div class="card-body w-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title my-5 w-25">Data Peran</h3>
                    <div>
                        <button class="btn btn-danger btn-sm mr-2" style="min-width: 80px; height: 40px;">
                            <a href="{{ url('peran/export_pdf') }}"
                            class="text-white text-decoration-none d-flex align-items-center justify-content-center w-100 h-100"
                            target="_blank">
                                <i class="fa fa-file-pdf mr-1"></i> PDF
                            </a>
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="modalAction('{{ url('peran/create') }}')"
                                style="min-width: 120px; height: 40px;">
                            Tambah Peran
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
                    <table class="table table-hover table-striped" id="table-peran">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Peran</th>
                                <th>Nama Peran</th>
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
<style>
    .btn-edit,
    .btn-show,
    .btn-delete,
    .btn-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        will-change: transform;
    }

    .btn-edit:hover,
    .btn-show:hover,
    .btn-delete:hover,
    .btn-hover:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        z-index: 2;
        position: relative;
    }
</style>
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
            tablePeran = $('#table-peran').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('peran/list') }}",
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
                        data: 'kode_peran',
                        name: 'kode_peran'
                    },
                    {
                        data: 'nama_peran',
                        name: 'nama_peran'
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
