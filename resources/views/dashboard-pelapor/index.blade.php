{{-- resources/views/dashboard-user/index.blade.php --}}
@extends('layouts.main')

@section('content')
  <div class="container-fluid">
    {{-- 1) Judul --}}
    <div class="row mb-4">
      <div class="col-12">
        <h2 class="mb-0">Dashboard Pelaporan</h2>
        <p class="text-muted">Ringkasan laporan Anda</p>
      </div>
    </div>

    {{-- 2) Tiga Card Utama --}}
    <div class="row">
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="card-title text-secondary">Total Laporan</h6>
            <div class="d-flex align-items-center">
              <i class="fas fa-clipboard-list fa-2x text-primary mr-3"></i>
              <h3 class="mb-0">{{ $totalLaporan }}</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="card-title text-secondary">Laporan In Process</h6>
            <div class="d-flex align-items-center">
              <i class="fas fa-spinner fa-2x text-warning mr-3"></i>
              <h3 class="mb-0">{{ $inProcess }}</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="card-title text-secondary">Laporan Selesai</h6>
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
              <h3 class="mb-0">{{ $completed }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- 3) Pie Chart: Top 5 Fasilitas --}}
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="card-title text-secondary mb-3">Top 5 Fasilitas Terbanyak Dilaporkan</h6>
            <canvas id="pieChart"></canvas>
            <p class="text-muted mt-3 mb-0">
              Distribusi laporan berdasarkan fasilitas
            </p>
          </div>
        </div>
      </div>
      {{-- Sisakan kolom kosong atau isi dengan konten lain --}}
      <div class="col-lg-6 mb-4">
        {{-- Anda bisa menambahkan ringkasan atau grafik lain di sini --}}
      </div>
    </div>
  </div>
@endsection

@push('css')
<style>
  .card-title {
    font-weight: 500;
  }
</style>
@endpush

@push('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Data dari Controller
    const labels = @json($pieLabels);
    const data   = @json($pieData);

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
          ]
        }]
      },
      options: {
        responsive: true,
        legend: {
          position: 'bottom'
        }
      }
    });
  });
</script>
@endpush
