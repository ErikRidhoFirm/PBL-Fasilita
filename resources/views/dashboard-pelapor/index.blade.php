{{-- resources/views/dashboard-user/index.blade.php --}}
@extends('layouts.main')

@section('content')
<div class="container-fluid px-4 py-4">
  {{-- Greeting --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h2 class="h3 mb-1">Selamat Datang, {{ Auth::user()->nama }}</h2>
          <small class="text-muted">Ringkasan aktivitas laporan Anda</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Cards --}}
  <div class="row">
    {{-- Total Laporan --}}
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-0 shadow-sm h-100 hover-shadow">
        <div class="card-body d-flex align-items-center">
          <div class="icon-wrapper bg-primary text-white mr-3">
            <i class="fas fa-clipboard-list fa-lg"></i>
          </div>
          <div>
            <small class="text-uppercase text-muted">Total Laporan</small>
            <h4 class="mb-0">{{ $totalLaporan }}</h4>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
          <small class="text-primary">Diperbarui: {{ now()->format('d M Y') }}</small>
        </div>
      </div>
    </div>

    {{-- Laporan In Process --}}
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-0 shadow-sm h-100 hover-shadow">
        <div class="card-body d-flex align-items-center">
          <div class="icon-wrapper bg-warning text-white mr-3">
            <i class="fas fa-spinner fa-lg"></i>
          </div>
          <div>
            <small class="text-uppercase text-muted">Laporan In Process</small>
            <h4 class="mb-0">{{ $inProcess }}</h4>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
          <small class="text-warning">Sedang diproses</small>
        </div>
      </div>
    </div>

    {{-- Laporan Selesai --}}
    <div class="col-xl-4 col-md-12 mb-4">
      <div class="card border-0 shadow-sm h-100 hover-shadow">
        <div class="card-body d-flex align-items-center">
          <div class="icon-wrapper bg-success text-white mr-3">
            <i class="fas fa-check-circle fa-lg"></i>
          </div>
          <div>
            <small class="text-uppercase text-muted">Laporan Selesai</small>
            <h4 class="mb-0">{{ $completed }}</h4>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
          <small class="text-success">Telah diperbaiki</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Main Content: Pie Chart + Legend --}}
  <div class="row">
    {{-- Pie Chart --}}
    <div class="col-lg-7 mb-4">
      <div class="card border-0 shadow-sm h-100 hover-shadow">
        <div class="card-body">
          <h5 class="card-title mb-3">Top 5 Fasilitas Terbanyak Dilaporkan</h5>
          <div class="chart-container position-relative" style="height:300px;">
            <canvas id="pieChart"></canvas>
          </div>
          <p class="text-muted mt-3 mb-0">
            Distribusi laporan berdasarkan fasilitas
          </p>
        </div>
      </div>
    </div>

    {{-- Legend & Ringkasan --}}
    <div class="col-lg-5 mb-4">
      <div class="card border-0 shadow-sm h-100 hover-shadow">
        <div class="card-body">
          <h5 class="card-title mb-3">Detail Fasilitas</h5>
          <ul class="list-unstyled">
            @php
              // Palet warna yang sama dengan chart
              $colors = ['#4B49AC', '#FF6384', '#36A2EB', '#FFCE56', '#00A86B'];
            @endphp

            @foreach($pieLabels as $idx => $label)
              <li class="d-flex align-items-center mb-2">
                <span class="legend-color mr-2" style="background-color: {{ $colors[$idx] }};"></span>
                <span class="flex-grow-1">{{ $label }}</span>
                <span class="font-weight-bold">{{ $pieData[$idx] }}</span>
              </li>
            @endforeach

            @if(count($pieLabels) < 1)
              <li class="text-center text-muted py-3">Belum ada data fasilitas</li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@push('css')
<style>
  /* Card hover effect */
  .hover-shadow:hover {
    transform: translateY(-5px);
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
  }
  /* Icon wrapper di card */
  .icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  /* Legend bullet */
  .legend-color {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 4px;
  }
  /* Typography */
  h2, h4, h5 {
    font-weight: 500;
  }
  /* Responsif untuk chart */
  .chart-container {
    width: 100%;
  }
  /* Atur padding card footers */
  .card-footer {
    font-size: 0.85rem;
  }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const labels = @json($pieLabels);
    const data   = @json($pieData);

    // Jika tidak ada data, tidak perlu render chart
    if(labels.length === 0 || data.length === 0) {
      document.getElementById('pieChart').getContext('2d').font = "16px Arial";
      return;
    }

    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: [
            '#4B49AC',
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#00A86B'
          ],
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, chartData) {
              const value = chartData.datasets[0].data[tooltipItem.index];
              return chartData.labels[tooltipItem.index] + ': ' + value + ' laporan';
            }
          }
        }
      }
    });
  });
</script>
@endpush
