@extends('layouts.main')

@push('css')
<style>
  :root {
    --card-spacing: 1rem;
    --primary-color: #4F46E5;
  }

  .role-card {
    background: white;
    border: 2px solid #f1f5f9;
    border-radius: 20px;
    padding: var(--card-spacing);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .role-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
    border-color: var(--primary-color);
  }

  .role-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .role-card:hover::before {
    opacity: 1;
  }

  .role-card .card-body {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
</style>
@endpush

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Laporan Fasilitas Yang Sudah Ada</h3>
    @if (auth()->user()->peran->kode_peran !== 'GST')
        <a href="{{ url('laporanPelapor/create') }}" class="btn btn-primary">Buat Laporan</a>
    @endif
  </div>

  {{-- Bungkus dengan div scrollable --}}
  <div id="scroll-container"
       style="height: 85vh; overflow-y: auto; position: relative;">
    {{-- Container utama: height total (akan di‐set dinamis) --}}
    <div id="inner-container" style="position: relative; width: 100%;"></div>
  </div>

  {{-- Modal untuk detail --}}
  <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
@endsection

@push('js')
<script>
  $(function(){
    // Endpoint paginated JSON
    const urlList          = "{{ route('laporanPelapor.list') }}";
    const perPage          = 8;      // per halaman di backend
    const rowGap           = 24;     // jarak vertikal antar kartu (px)
    const cardVisualHeight = 350;    // tinggi “isi” kartu (px)
    const itemHeight       = cardVisualHeight + rowGap;

    let cacheData    = [];     // buffer data menurut index global
    let totalItems   = null;
    let lastPage     = null;
    let fetchedPages = new Set();
    let isFetching   = false;

    const scrollContainer = document.getElementById('scroll-container');
    const innerContainer  = document.getElementById('inner-container');

    // Tentukan jumlah kolom berdasarkan lebar viewport
    function getColumns() {
      const w = window.innerWidth;
      if (w < 576)   return 1;  // xs
      if (w < 768)   return 2;  // sm
      if (w < 992)   return 3;  // md
                     return 4;  // lg ke atas
    }

    function fetchPage(page) {
      if (isFetching) return Promise.resolve();
      if (lastPage !== null && page > lastPage) return Promise.resolve();
      if (fetchedPages.has(page)) return Promise.resolve();

      isFetching = true;
      return $.ajax({
        url: urlList,
        data: { page: page },
        method: 'GET',
        dataType: 'json'
      })
      .done(res => {
        lastPage = res.last_page;
        fetchedPages.add(page);

        totalItems = (res.total !== undefined)
          ? res.total
          : res.last_page * perPage;

        const startIndex = (res.current_page - 1) * perPage;
        res.data.forEach((item, idx) => {
          cacheData[startIndex + idx] = item;
        });

        updateVisible();
      })
      .fail(() => {
        console.error('Error memuat halaman ' + page);
      })
      .always(() => {
        isFetching = false;
      });
    }

    function updateVisible() {
      if (totalItems === null) return;

      const columns     = getColumns();
      const totalRows   = Math.ceil(totalItems / columns);
      const totalHeight = totalRows * itemHeight;
      innerContainer.style.height = totalHeight + 'px';

      const scrollTop      = scrollContainer.scrollTop;
      const viewportHeight = scrollContainer.clientHeight;
      const firstRow       = Math.floor(scrollTop / itemHeight);
      const lastRow        = Math.floor((scrollTop + viewportHeight) / itemHeight);

      const startRow = Math.max(0, firstRow - 2);
      const endRow   = Math.min(totalRows - 1, lastRow + 2);
      const startIdx = startRow * columns;
      const endIdx   = Math.min(totalItems, (endRow + 1) * columns) - 1;

      // Request halaman–halaman yang diperlukan
      const needed = new Set();
      for (let i = startIdx; i <= endIdx; i++) {
        const pg = Math.floor(i / perPage) + 1;
        if (!fetchedPages.has(pg)) needed.add(pg);
      }
      needed.forEach(pg => fetchPage(pg));

      // Render kartu
      innerContainer.innerHTML = '';
      const frag = document.createDocumentFragment();
      const itemWidth = 100 / columns;

      for (let i = startIdx; i <= endIdx; i++) {
        const d = cacheData[i];
        if (!d) continue;

        const row = Math.floor(i / columns);
        const col = i % columns;

        const wrapper = document.createElement('div');
        Object.assign(wrapper.style, {
          position: 'absolute',
          top:    (row * itemHeight) + 'px',
          left:   (col  * itemWidth)  + '%',
          width:  itemWidth            + '%',
          height: cardVisualHeight     + 'px',
          padding: '0 .5rem',
          boxSizing: 'border-box'
        });

        // Path foto & vote icon
        const foto = d.path_foto
          ? `{{ asset('storage') }}/${d.path_foto}`
          : '{{ asset("img/placeholder.png") }}';
        const voted = d.voted_by_me ? 'true' : 'false';
        const ic    = d.voted_by_me ? 'mdi-thumb-up text-success' : 'mdi-thumb-up-outline text-muted';

        // Isi kartu
        wrapper.innerHTML = `
          <div class="role-card h-100 d-flex flex-column">
            <img src="${foto}"
                 class="card-img-top"
                 style="height:150px; object-fit:cover"
                 alt="${ d.nama_fasilitas || 'Fasilitas' }">
            <div class="card-body d-flex flex-column flex-grow-1">
              <h6 class="card-title">${ d.nama_fasilitas || '–' }</h6>
              <p class="card-text small mb-2">
                <strong>Gedung:</strong> ${ d.nama_gedung || '-' }<br>
                <strong>Lantai:</strong> ${ d.nomor_lantai || '-' }<br>
                <strong>Ruangan:</strong> ${ d.nama_ruangan || '-' }
              </p>
              <div class="mt-auto pt-2 d-flex align-items-center justify-content-between">
                @if(auth()->user()->peran->kode_peran !== 'GST')
                  <button type="button"
                          class="btn btn-transparent p-0 d-flex align-items-center btn-vote-icon"
                          data-voted="${voted}"
                          style="font-size:1rem;">
                    <i class="mdi ${ic} fs-5 me-1"></i>
                    <span class="vote-count">${ d.votes_count }</span>
                  </button>
                @else
                  <div class="d-flex align-items-center text-muted">
                    <i class="mdi mdi-thumb-up-outline fs-5 me-1"></i>
                    <span class="vote-count">${ d.votes_count }</span>
                  </div>
                @endif
                <button type="button"
                        class="btn btn-outline-primary btn-sm btn-detail"
                        data-detail-url="{{ route('laporanPelapor.show', ['id' => ':id']) }}">
                  <i class="mdi mdi-file-document-outline me-1"></i>Detail
                </button>
              </div>
            </div>
          </div>
        `.replace(/:id/g, d.id_laporan_fasilitas);

        // Event vote
        const $w = $(wrapper);
        if ("{{ auth()->user()->peran->kode_peran }}" !== 'GST') {
          $w.on('click', '.btn-vote-icon', function(){
            const btn = $(this);
            const cnt = btn.find('.vote-count');
            const icn = btn.find('i');
            const isV = btn.data('voted') === true;
            const url = isV
              ? '{{ route("vote.destroy", ":id") }}'
              : '{{ route("vote.store",   ":id") }}';
            const method = isV ? 'DELETE' : 'POST';
            $.ajax({
              url: url.replace(':id', d.id_laporan_fasilitas),
              method: method,
              data: { _token: '{{ csrf_token() }}' },
              dataType: 'json'
            })
            .done(res => {
              if (res.status === 'success') {
                cnt.text(res.votes_count);
                btn.data('voted', !isV);
                icn.toggleClass('mdi-thumb-up-outline mdi-thumb-up')
                   .toggleClass('text-muted text-success');
              }
            });
          });
        }

        // Event detail
        $w.on('click', '.btn-detail', function(){
          const url = $(this).data('detail-url');
          $('#myModal').load(url, function(){
            new bootstrap.Modal(this).show();
          });
        });

        frag.appendChild(wrapper);
      }

      innerContainer.appendChild(frag);
    }

    // Inisialisasi
    fetchPage(1);
    scrollContainer.addEventListener('scroll', updateVisible);
    window.addEventListener('resize', updateVisible);
  });
</script>
@endpush
