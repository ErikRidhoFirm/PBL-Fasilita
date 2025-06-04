{{-- resources/views/dashboard-user/index.blade.php --}}
@extends('layouts.main')

@section('content')
<div class="container-fluid px-4 py-4">
  {{-- Greeting --}}
  <div class="row mb-5">
    <div class="col-12">
      <h2 class="h3 mb-1 font-weight-bold">Selamat Datang, {{ Auth::user()->nama }}</h2>
      <small class="text-secondary">Ringkasan aktivitas laporan Anda</small>
    </div>
  </div>

  {{-- Cards: Total / In Process / Completed --}}
  <div class="row mb-4">
    {{-- Total Laporan --}}
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card shadow-sm border-0 h-100 hover-lift rounded-lg">
        <div class="card-body d-flex align-items-center">
          <div class="icon-wrapper bg-gradient-primary text-white mr-3">
            <i class="fas fa-clipboard-list fa-2x"></i>
          </div>
          <div>
            <small class="text-uppercase text-muted">Total Laporan</small>
            <h3 class="mb-0 font-weight-bold">{{ $totalLaporan }}</h3>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
          <small class="text-primary">Diperbarui: {{ now()->format('d M Y') }}</small>
        </div>
      </div>
    </div>

    {{-- Laporan In Process --}}
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card shadow-sm border-0 h-100 hover-lift rounded-lg">
        <div class="card-body d-flex align-items-center">
          <div class="icon-wrapper bg-gradient-warning text-white mr-3">
            <i class="fas fa-spinner fa-2x"></i>
          </div>
          <div>
            <small class="text-uppercase text-muted">Laporan In Process</small>
            <h3 class="mb-0 font-weight-bold">{{ $inProcess }}</h3>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
          <small class="text-warning">Sedang diproses</small>
        </div>
      </div>
    </div>

    {{-- Laporan Selesai --}}
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card shadow-sm border-0 h-100 hover-lift rounded-lg">
        <div class="card-body d-flex align-items-center">
          <div class="icon-wrapper bg-gradient-success text-white mr-3">
            <i class="fas fa-check-circle fa-2x"></i>
          </div>
          <div>
            <small class="text-uppercase text-muted">Laporan Selesai</small>
            <h3 class="mb-0 font-weight-bold">{{ $completed }}</h3>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
          <small class="text-success">Telah diperbaiki</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Horizontal Bar Chart: Top 5 Fasilitas Terbanyak Di-Vote User --}}
  <div class="row">
    <div class="col-lg-12 mb-4">
      <div class="card shadow-sm border-0 h-100 hover-lift rounded-lg">
        <div class="card-body">
          <h5 class="card-title mb-4 font-weight-bold">Top 5 Fasilitas Terbanyak Di-Vote User</h5>

          {{-- Canvas untuk Bar Chart Horizontal --}}
          <div class="chart-container position-relative mb-3" style="height: 350px; width: 100%; background: #ffffff; border-radius: 0.5rem; padding: 1rem; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
            <canvas id="barChart"></canvas>
          </div>

          <p class="text-muted small">
            Setiap batang menunjukkan jumlah vote untuk masing-masing fasilitas
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('css')
<style>
  /* Lift effect pada hover kartu */
  .hover-lift {
    transition: transform 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
  }
  .hover-lift:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
  }

  /* Border-radius yang elegan */
  .rounded-lg {
    border-radius: 0.75rem;
  }

  /* Gradien warna icon-wrapper */
  .bg-gradient-primary {
    background: linear-gradient(45deg, #5a3eeb, #3723fe);
  }
  .bg-gradient-warning {
    background: linear-gradient(45deg, #f6d365, #fda085);
  }
  .bg-gradient-success {
    background: linear-gradient(45deg, #43e97b, #38f9d7);
  }

  /* Styling judul dan teks */
  h2, h3, h5 {
    font-family: 'Poppins', sans-serif;
  }
  .text-secondary {
    color: #6c757d !important;
  }

  /* Membuat bar-chart batang melengkung di ujungnya */
  .chartjs-render-monitor {
    border-radius: 8px;
  }
</style>
@endpush

@push('js')
  {{-- Chart.js versi 2.x --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
  <script>
    let barChartInstance = null;

    $(document).ready(function() {
      const labels = @json($pieLabels);
      const votes  = @json($pieData);

      if (labels.length > 0 && votes.length > 0) {
        const ctx = $('#barChart')[0].getContext('2d');

        // Create vertical gradient for bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(75, 73, 172, 0.9)');
        gradient.addColorStop(1, 'rgba(75, 73, 172, 0.5)');

        // Destroy instance lama jika ada
        if (barChartInstance) {
          barChartInstance.destroy();
        }

        barChartInstance = new Chart(ctx, {
          type: 'horizontalBar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Jumlah Vote',
              data: votes,
              backgroundColor: gradient,
              borderColor: 'rgba(75, 73, 172, 1)',
              borderWidth: 2,
              barThickness: 24,
              hoverBackgroundColor: 'rgba(75, 73, 172, 1)',
              hoverBorderColor: 'rgba(75, 73, 172, 1)'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 10, bottom: 10, left: 10, right: 10 } },
            animation: { duration: 1200, easing: 'easeOutQuart' },
            legend: { display: false },
            scales: {
              xAxes: [{
                ticks: {
                  beginAtZero: true,
                  stepSize: 1,
                  fontColor: '#343a40',
                  fontStyle: '500'
                },
                gridLines: {
                  color: '#e9ecef',
                  zeroLineColor: '#ced4da'
                },
                scaleLabel: {
                  display: true,
                  labelString: 'Jumlah Vote',
                  fontColor: '#555',
                  fontStyle: '600'
                }
              }],
              yAxes: [{
                ticks: {
                  fontColor: '#343a40',
                  fontSize: 14,
                  fontStyle: '600'
                },
                gridLines: { display: false }
              }]
            },
            tooltips: {
              backgroundColor: '#4B49AC',
              titleFontColor: '#fff',
              bodyFontColor: '#fff',
              borderColor: '#3723fe',
              borderWidth: 1,
              xPadding: 10,
              yPadding: 8,
              displayColors: false,
              callbacks: {
                label: function(tooltipItem, chartData) {
                  const v = chartData.datasets[0].data[tooltipItem.index];
                  return chartData.labels[tooltipItem.index] + ' → ' + v + ' vote';
                }
              }
            }
          }
        });
      } else {
        console.warn('Tidak ada data vote untuk bar chart.');
      }
    });
  </script>
@endpush
