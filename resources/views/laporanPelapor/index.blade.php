@extends('layouts.main')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Laporan Fasilitas Yang Sudah Ada</h3>
    <a href="{{ url('laporanPelapor/create') }}" class="btn btn-primary">Buat Laporan</a>
  </div>

  {{-- Bungkus dengan div scrollable --}}
  <div id="scroll-container"
       style="height: 85vh; overflow-y: auto; position: relative;">
    {{-- Container utama: height total (akan di‐set dinamis) --}}
    <div id="inner-container" style="position: relative; width: 100%;">
      {{-- Di sini nanti posisi kartu‐kartu akan di‐render via JS --}}
    </div>
  </div>

  {{-- Modal untuk detail --}}
  <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
@endsection

@push('js')
<script>
$(function(){
  // URL endpoint JSON (paginated)
  const urlList = "{{ route('laporanPelapor.list') }}";

  // Konstanta (SEO RESIZE!)
  const perPage          = 8;      // sama dengan backend
  const columns          = 4;      // 4 kolom per baris
  const rowGap           = 24;     // jarak vertikal antar kartu (px). Misal 1.5rem ≈ 24px.
  // ========== PENTING ==========
  // cardVisualHeight harus cukup untuk gambar (150px), teks (sekitar 50-60px), dan tombol (sekitar 36px)
  // Contoh: 150 + 60 + 36 + padding ≈ 270–300px. Kita isi 300px supaya pasti muat.
  const cardVisualHeight = 350;
  // ==============================
  const itemHeight       = cardVisualHeight + rowGap; // total tinggi slot item

  let cacheData    = [];     // akan menampung data klien per index
  let totalItems   = null;   // nantinya = last_page * perPage (atau res.total)
  let lastPage     = null;   // jumlah total halaman
  let fetchedPages = new Set();
  let isFetching   = false;

  const scrollContainer = document.getElementById('scroll-container');
  const innerContainer  = document.getElementById('inner-container');

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

      if (res.total !== undefined) {
        totalItems = res.total;
      } else {
        totalItems = res.last_page * perPage;
      }

      const startIndex = (res.current_page - 1) * perPage;
      res.data.forEach((item, idx) => {
        cacheData[startIndex + idx] = item;
      });

      updateVisible();
    })
    .fail(() => {
      console.error('Error memuat data halaman ' + page);
    })
    .always(() => {
      isFetching = false;
    });
  }

  function updateVisible() {
    if (totalItems === null) return;

    const totalRows   = Math.ceil(totalItems / columns);
    const totalHeight = totalRows * itemHeight;
    innerContainer.style.height = totalHeight + 'px';

    const scrollTop      = scrollContainer.scrollTop;
    const viewportHeight = scrollContainer.clientHeight;

    const firstVisibleRow = Math.floor(scrollTop / itemHeight);
    const lastVisibleRow  = Math.floor((scrollTop + viewportHeight) / itemHeight);

    let startRow = Math.max(0, firstVisibleRow - 2);
    let endRow   = Math.min(totalRows - 1, lastVisibleRow + 2);

    const startIndex = startRow * columns;
    const endIndex   = Math.min(totalItems, (endRow + 1) * columns) - 1;

    // Pastikan halaman‐halaman yang diperlukan sudah di‐fetch
    const neededPages = new Set();
    for (let i = startIndex; i <= endIndex; i++) {
      const pageReq = Math.floor(i / perPage) + 1;
      if (!fetchedPages.has(pageReq)) neededPages.add(pageReq);
    }
    neededPages.forEach(pg => fetchPage(pg));

    // Kumpulkan item yang siap dirender
    const itemsToRender = [];
    for (let i = startIndex; i <= endIndex; i++) {
      if (cacheData[i]) {
        itemsToRender.push({ index: i, data: cacheData[i] });
      }
    }

    innerContainer.innerHTML = '';
    const frag = document.createDocumentFragment();

    itemsToRender.forEach(itemObj => {
      const i = itemObj.index;
      const d = itemObj.data;

      const row = Math.floor(i / columns);
      const col = i % columns;

      const cardWrapper = document.createElement('div');
      cardWrapper.style.position    = 'absolute';
      cardWrapper.style.top         = (row * itemHeight) + 'px';
      cardWrapper.style.left        = (col * 25) + '%';
      cardWrapper.style.width       = '25%';
      cardWrapper.style.height      = cardVisualHeight + 'px';
      cardWrapper.style.padding     = '0 .5rem';
      cardWrapper.style.boxSizing   = 'border-box';

      // Susun inner HTML kartu. Pastikan tombol di dalam card-body.
      const foto = d.path_foto
                   ? `{{ asset('storage') }}/${d.path_foto}`
                   : '{{ asset("img/placeholder.png") }}';
      const votedClass = d.voted_by_me ? 'mdi-thumb-up' : 'mdi-thumb-up-outline';
      const votedData  = d.voted_by_me ? 'true' : 'false';
      const iconColor  = d.voted_by_me ? 'text-success' : 'text-muted';

      cardWrapper.innerHTML = `
        <div class="card h-100 shadow-sm d-flex flex-column">
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
              <button type="button"
                      class="btn btn-transparent p-0 d-flex align-items-center btn-vote-icon"
                      data-vote-url="{{ route('vote.store', ['id' => ':id']) }}"
                      data-unvote-url="{{ route('vote.destroy', ['id' => ':id']) }}"
                      data-voted="${votedData}"
                      style="font-size:1rem;">
                <i class="mdi ${votedClass} fs-5 me-1 ${iconColor}"></i>
                <span class="vote-count">${ d.votes_count }</span>
              </button>
              <button type="button"
                      class="btn btn-outline-primary btn-sm btn-detail"
                      data-detail-url="{{ route('laporanPelapor.show', ['id' => ':id']) }}">
                <i class="mdi mdi-file-document-outline me-1"></i>Detail
              </button>
            </div>
          </div>
        </div>
      `.replace(/:id/g, d.id_laporan_fasilitas);

      // Pasang event listener untuk vote & detail (masih di dalam wrapper yang sama)
      const $tmp = $(cardWrapper);
      $tmp.find('.btn-vote-icon').hover(
        function(){
          if ($(this).data('voted') === false) {
            $(this).find('i').removeClass('text-muted').addClass('text-success');
          }
        },
        function(){
          if ($(this).data('voted') === false) {
            $(this).find('i').removeClass('text-success').addClass('text-muted');
          }
        }
      );
      $tmp.find('.btn-vote-icon').on('click', function(){
        const btn       = $(this);
        const countElem = btn.find('.vote-count');
        const icon      = btn.find('i');
        const isVoted   = btn.data('voted') === true;
        const url       = isVoted ? btn.data('unvote-url') : btn.data('vote-url');
        const method    = isVoted ? 'DELETE' : 'POST';

        $.ajax({
          url: url,
          method: method,
          data: { _token: '{{ csrf_token() }}' },
          dataType: 'json'
        })
        .done(res => {
          if (res.status === 'success') {
            countElem.text(res.votes_count);
            if (isVoted) {
              icon.removeClass('mdi-thumb-up text-success')
                  .addClass('mdi-thumb-up-outline text-muted');
              btn.data('voted', false);
            } else {
              icon.removeClass('mdi-thumb-up-outline text-muted')
                  .addClass('mdi-thumb-up text-success');
              btn.data('voted', true);
            }
            // Update cache agar state vote konsisten saat re-render
            const itemInCache = cacheData.find(item => item.id_laporan_fasilitas == d.id_laporan_fasilitas);
            if(itemInCache) {
              itemInCache.voted_by_me = !isVoted;
              itemInCache.votes_count = res.votes_count;
            }
          } else {
            Swal.fire('Gagal', res.message, 'error');
          }
        })
        .fail(() => {
          Swal.fire('Error', 'Gagal memproses aksi.', 'error');
        });
      });
      $tmp.find('.btn-detail').on('click', function(){
        const url = $(this).data('detail-url');
        $('#myModal').load(url, function(){
          new bootstrap.Modal(this).show();
        });
      });

      frag.appendChild(cardWrapper);
    });

    innerContainer.appendChild(frag);
  }

  // Inisialisasi: muat page 1 pertama kali
  fetchPage(1);

  // Pasang listener scroll
  scrollContainer.addEventListener('scroll', function(){
    updateVisible();
  });
});
</script>

@endpush
