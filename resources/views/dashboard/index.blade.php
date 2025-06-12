@extends('layouts.main')

@section('content')
    {{-- status dari keseluruhan laporan --}}
    <div class="container-fluid">
        <h4 class="mb-4 fw-bold text-primary">Ringkasan Status Laporan</h4>

        <div class="row ">
            @php
                $statusList = [
                    'menunggu' => [
                        'label' => 'Menunggu',
                        'color1' => '#f0ad4e',
                        'color2' => '#f7d794',
                        'icon' => 'fa-hourglass-half',
                    ],
                    'valid' => [
                        'label' => 'Valid',
                        'color1' => '#5cb85c',
                        'color2' => '#a3d9a5',
                        'icon' => 'fa-check-circle',
                    ],
                    'tidak_valid' => [
                        'label' => 'Tidak Valid',
                        'color1' => '#d9534f',
                        'color2' => '#f5b7b1',
                        'icon' => 'fa-times-circle',
                    ],
                    'ditolak' => [
                        'label' => 'Ditolak',
                        'color1' => '#6c757d',
                        'color2' => '#adb5bd',
                        'icon' => 'fa-ban',
                    ],
                    'ditugaskan' => [
                        'label' => 'Ditugaskan',
                        'color1' => '#5bc0de',
                        'color2' => '#b3e5fc',
                        'icon' => 'fa-briefcase',
                    ],
                    'selesai' => [
                        'label' => 'Selesai',
                        'color1' => '#0275d8',
                        'color2' => '#90caf9',
                        'icon' => 'fa-clipboard-check',
                    ],
                ];
            @endphp

            @foreach ($statusList as $key => $info)
                <div class="col-md-4 mb-4">
                    <div class="card status-card border-0 shadow-sm"
                        style="--color1: {{ $info['color1'] }}; --color2: {{ $info['color2'] }};">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas {{ $info['icon'] }} fa-2x text-muted"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">{{ $info['label'] }}</h6>
                                <h4 class="mb-0 fw-bold">{{ $jumlahStatus[$key] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <style>
        .status-card {
            border-left: 5px solid var(--color1);
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .status-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            background: linear-gradient(270deg, var(--color2), var(--color1), var(--color2));
            background-size: 400% 400%;
            animation: gradientSlideLeft 4s ease infinite;
            color: #fff;
        }

        .status-card:hover i,
        .status-card:hover h6,
        .status-card:hover h4 {
            color: #fff !important;
        }

        @keyframes gradientSlideLeft {
            0% {
                background-position: 100% 50%;
            }

            50% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        .badge.rounded-circle {
            display: inline-block;
            vertical-align: middle;
        }
    </style>

    {{-- Line chart  --}}
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text text-primary">Diagram Garis</p>
                    <p class="font-weight-500">
                        Grafik jumlah laporan fasilitas berstatus “Menunggu” dan “Sudah selesai” dalam 6 bulan terakhir.
                    </p>
                    <div class="d-flex flex-wrap mb-5">
                    </div>
                    <canvas id="order-chart"></canvas>
                </div>
            </div>
        </div>

        {{-- Diagram Batang --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <p class="card-title text text-primary">Diagram Batang</p>
                    </div>
                    <p class="font-weight-500">Diagram yang berisikan data laporan yang telah diperbaiki (diganti atau
                        diperbaiki) </p>
                    <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                    <canvas id="sales-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            (function($) {
                if ($("#sales-chart").length) {
                    var ctx = $("#sales-chart").get(0).getContext("2d");
                    $.ajax({
                        url: "{{ route('dashboard.repair-data') }}",
                        method: 'GET',
                        dataType: 'json'
                    }).done(function(res) {
                        var chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: res.labels,
                                datasets: res.datasets
                            },
                            options: {
                                cornerRadius: 5,
                                responsive: true,
                                maintainAspectRatio: true,
                                layout: {
                                    padding: {
                                        top: 20
                                    }
                                },
                                scales: {
                                    yAxes: [{
                                        display: true,
                                        gridLines: {
                                            display: true,
                                            color: "#F2F2F2",
                                            drawBorder: false
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            callback: v => v + '',
                                            fontColor: "#6C7383"
                                        }
                                    }],
                                    xAxes: [{
                                        ticks: {
                                            fontColor: "#6C7383",
                                            beginAtZero: true
                                        },
                                        gridLines: {
                                            display: false
                                        },
                                        barPercentage: 1
                                    }]
                                },
                                legend: {
                                    display: false
                                },
                                elements: {
                                    point: {
                                        radius: 0
                                    }
                                }
                            }
                        });
                        document.getElementById('sales-legend').innerHTML = chart.generateLegend();
                    }).fail(function(e) {
                        console.error('Gagal ambil data:', e);
                    });
                }
            })(jQuery);

            // 
            (function($) {
                'use strict';

                if ($("#order-chart").length) {
                    var ctx = $("#order-chart").get(0).getContext("2d");

                    $.ajax({
                        url: "{{ route('dashboard.status-data') }}",
                        method: 'GET',
                        dataType: 'json'
                    }).done(function(res) {
                        var revenueChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: res.labels,
                                datasets: res.datasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    filler: {
                                        propagate: false
                                    },
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true
                                    }
                                },
                                scales: {
                                    x: [{
                                        display: true,
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            display: true,
                                            padding: 10,
                                            fontColor: "#6C7383"
                                        }
                                    }],
                                    y: [{
                                        display: true,
                                        gridLines: {
                                            display: true,
                                            color: "#f2f2f2",
                                            drawBorder: false
                                        },
                                        ticks: {
                                            display: true,
                                            autoSkip: false,
                                            stepSize: 1,
                                            beginAtZero: true,
                                            fontColor: "#6C7383"
                                        }
                                    }]
                                },
                                elements: {
                                    line: {
                                        tension: 0.35,
                                        borderWidth: 2
                                    },
                                    point: {
                                        radius: 0
                                    }
                                }
                            }
                        });
                    }).fail(function(err) {
                        console.error('Gagal memuat data status chart:', err);
                    });
                }
            })(jQuery);
        </script>
    @endpush

    <div class="container mt-5">
        <div class="card shadow-sm rounded mb-5">
            <div class="card-body">
                <h4 class="mb-4 fw-bold text-primary">Statistik Pelapor Fasilitas</h4>
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center justify-content-center" style="height: 300px;">
                        <canvas id="pelaporPieChart" width="250" height="250"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3 text-primary">Deskripsi Statistik Pelapor</h5>
                        <ul class="list-group">
                            @foreach ($laporanPerPeran as $kode => $jumlah)
                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                    style="
                                        background-color: {{ match ($kode) {
                                            'MHS' => '#cce5ff', // Pink muda
                                            'DSN' => '#e8f5e9', // Hijau muda
                                            'TDK' => '#fff8e1', // Kuning muda
                                        } }};
                                        color: {{ match ($kode) {
                                            'MHS' => '#004085', // Pink tua
                                            'DSN' => '#2e7d32', // Hijau tua
                                            'TDK' => '#f9a825', // Kuning tua
                                        } }};
                                        border: 1px solid {{ match ($kode) {
                                            'MHS' => '#b8daff',
                                            'DSN' => '#c8e6c9',
                                            'TDK' => '#ffecb3',
                                        } }};
                                        border-radius: 0.5rem;
                                    ">
                                    <div class="d-flex align-items-center">
                                        {{ match ($kode) {
                                            'MHS' => 'Mahasiswa',
                                            'DSN' => 'Dosen',
                                            'TDK' => 'Tendik',
                                            default => $kode,
                                        } }}
                                    </div>
                                    <span class="badge bg-white text-dark rounded-pill">{{ $jumlah }} laporan</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Statistika pie chart dari setiap laporan yang sering melapor --}}
    @php
        $colors = ['MHS' => '#4e73df', 'DSN' => '#1cc88a', 'TDK' => '#f6c23e'];
    @endphp

    {{-- Line Chart untuk laporan yang berisikan laporan yan gterverifikasi dan diperbaiki --}}
    <div class="container mt-4">
        <div class="card shadow-sm rounded">
            <div class="card-body">
                <h4 class="mb-4 fw-bold text-primary">Jumlah Laporan Terverifikasi dan Diperbaiki per Bulan</h4>
                <div style="height: 400px;">
                    <canvas id="verifikasiLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // PIE CHART
        const pieCtx = document.getElementById('pelaporPieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode(
                    array_map(
                        fn($kode) => match ($kode) {
                            'MHS' => 'Mahasiswa',
                            'DSN' => 'Dosen',
                            'TDK' => 'Tendik',
                        },
                        array_keys($laporanPerPeran),
                    ),
                ) !!},
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: {!! json_encode(array_values($laporanPerPeran)) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e'],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.chart._metasets[context.datasetIndex].total ?? context
                                    .dataset.data.reduce((a, b) => a + b, 0);
                                const percent = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} laporan (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });

        // LINE CHART
        const lineCtx = document.getElementById('verifikasiLineChart').getContext('2d');
        const verifikasiData = {!! json_encode($verifikasiData) !!};
        const perbaikanData = {!! json_encode($perbaikanData) !!};

        console.log("Verifikasi:", verifikasiData);
        console.log("Perbaikan:", perbaikanData);

        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                        label: 'Laporan Terverifikasi',
                        data: verifikasiData,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Sudah diperbaiki',
                        data: perbaikanData,
                        borderColor: 'deeppink',
                        backgroundColor: 'rgba(255, 105, 180, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Laporan'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
    </script>
    </div>
    </div>
@endsection
