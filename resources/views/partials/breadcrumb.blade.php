@if(isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0)
<nav aria-label="breadcrumb" class="mt-3 mb-4">
  <ol class="breadcrumb bg-white border px-3 py-2 rounded shadow-sm mb-0" style="--bs-breadcrumb-divider: '>';">
    @foreach($breadcrumbs as $breadcrumb)
      @if(!$loop->last && isset($breadcrumb['url']))
        <li class="breadcrumb-item">
          <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none text-primary fw-medium">
            @if($loop->first && (strtolower($breadcrumb['title']) === 'home' || strtolower($breadcrumb['title']) === 'dashboard'))
              <i class="fas fa-home me-1"></i>
            @endif
            {{ $breadcrumb['title'] }}
          </a>
        </li>
      @else
        <li class="breadcrumb-item active text-muted" aria-current="page">
          @if($loop->first && (strtolower($breadcrumb['title']) === 'home' || strtolower($breadcrumb['title']) === 'dashboard'))
            <i class="fas fa-home me-1"></i>
          @endif
          {{ $breadcrumb['title'] }}
        </li>
      @endif
    @endforeach
  </ol>
</nav>
@endif
