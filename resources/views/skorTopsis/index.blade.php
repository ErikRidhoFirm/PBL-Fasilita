@extends('layouts.main')

@section('content')
    <div class="w-100 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label for="filter-role">Filter Peran:</label>
                    <select id="filter-role" class="form-control w-25">
                        <option value="">Semua</option>
                        @foreach ($roles as $role)
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
    <div id="modalContainer" class="modal fade" tabindex="-1"></div>
@endsection

@push('js')
    <script>
        let table;
        $(function() {
            table = $('#tbl-prioritas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('skorTopsis.list') !!}",
                    data: d => d.role_id = $('#filter-role').val()
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'alternatif',
                        name: 'alternatif'
                    },
                    {
                        data: 'pelapor',
                        name: 'pelapor'
                    },
                    {
                        data: 'peran',
                        name: 'peran'
                    },
                    {
                        data: 'skor',
                        name: 'skor',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [4, 'desc']
                ] // urut berdasarkan kolom ke-4 (skor) desc
            });

            $('#filter-role').on('change', () => table.ajax.reload());

            $('#tbl-prioritas').on('click', '.assign', function() {
                let url = $(this).data('url');
                $.post(url, {
                    _token: '{{ csrf_token() }}'
                }).done(res => {
                    alert(res.message);
                    table.ajax.reload();
                });
            });
        });
    </script>
@endpush
