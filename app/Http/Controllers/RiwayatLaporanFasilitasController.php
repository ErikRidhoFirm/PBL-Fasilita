<?php

namespace App\Http\Controllers;


use App\Models\Status;
use App\Models\Pengguna;
use App\Models\PenilaianPengguna;
use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use App\Models\Perbaikan;
use App\Models\RiwayatLaporanFasilitas;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class RiwayatLaporanFasilitasController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard',          'url' => route('dashboard')],
            ['title' => 'Riwayat Laporan Fasilitas', 'url' => route('riwayat.index')],
        ];
        $activeMenu = 'laporanFasilitas';


        $statuses = Status::select('id_status', 'nama_status')->get();

        $petugas  = Pengguna::whereHas('peran', fn($q) =>
        $q->whereIn('kode_peran', ['ADM', 'SPR']))
            ->select('id_pengguna', 'nama')
            ->get();

        return view('riwayat.index', compact(
            'breadcrumbs',
            'activeMenu',
            'statuses',
            'petugas'
        ));
    }

    public function list(Request $request)
    {
        $latest = RiwayatLaporanFasilitas::selectRaw('MAX(id_riwayat_laporan_fasilitas) as id')
            ->groupBy('id_laporan_fasilitas');

        $query = RiwayatLaporanFasilitas::with([
            'laporanFasilitas.fasilitas',
            'laporanFasilitas.laporan.pengguna',
            'status',
            'pengguna'
        ])
            ->whereIn('id_riwayat_laporan_fasilitas', function ($q) use ($latest) {
                $q->fromSub($latest, 'x')->select('x.id');
            });


        if ($request->filled('status_id')) {
            $query->where('id_status', $request->status_id);
        }


        if ($request->filled('petugas_id')) {
            $query->where('id_pengguna', $request->petugas_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pelapor', fn($r) => $r->laporanFasilitas->laporan->pengguna->nama)
            ->addColumn('fasilitas', fn($r) => $r->laporanFasilitas->fasilitas->nama_fasilitas)
            ->addColumn('status', fn($r) => $r->status->nama_status)
            ->addColumn('petugas', fn($r) => $r->pengguna->nama)
            ->addColumn('waktu', fn($r) => $r->created_at->format('d-m-Y H:i'))
            ->addColumn('aksi', function ($r) {
                // Gunakan id_laporan_fasilitas untuk route
                $lapfasId = $r->id_laporan_fasilitas;

                $showUrl = route('riwayat.show', $lapfasId);
                $delUrl  = route('riwayat.destroy', $lapfasId);

                return
                    "<a href='$showUrl'
                    class='btn btn-sm btn-outline-primary'>
                    <i class='mdi mdi-file-document-box'></i>
                </a> ";
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($lapfasId)
    {
        $lapfas = LaporanFasilitas::with([
            'fasilitas',
            'penilaianPengguna',
            'kategoriKerusakan',
            'laporan.pengguna',
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
        ])->findOrFail($lapfasId);

        $riwayats = RiwayatLaporanFasilitas::with('status', 'pengguna')
            ->where('id_laporan_fasilitas', $lapfasId)
            ->orderBy('created_at')
            ->get();

        return view('riwayat.show', compact('lapfas', 'riwayats'));
    }


    public function detailModal($riwayatId)
    {
        $riwayat = RiwayatLaporanFasilitas::with([
            'laporanFasilitas.fasilitas',
            'laporanFasilitas.laporan.pengguna',
            'status',
            'pengguna.peran',
            'laporanFasilitas.penilaian.skorKriteriaLaporan.kriteria',
            'laporanFasilitas.penugasan.perbaikan',
            'laporanFasilitas.penugasan.teknisi',
        ])->findOrFail($riwayatId);

        // also find the “Valid” timestamp and “Ditugaskan” timestamp for durasi
        $all = RiwayatLaporanFasilitas::where('id_laporan_fasilitas', $riwayat->id_laporan_fasilitas)
            ->orderBy('created_at')
            ->get();
        $t_valid = optional($all->firstWhere(fn($r) => $r->status->nama_status === 'Valid'))->created_at;
        $t_tug   = optional($all->firstWhere(fn($r) => $r->status->nama_status === 'Ditugaskan'))->created_at;

        return view('riwayat.detail_modal', compact('riwayat', 't_valid', 't_tug'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($lapfasId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $lapfasId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lapfasId) {}

    public function exportRekap()
    {
        // Ambil data barang yang akan dieksport
        $LaporanFasilitas = LaporanFasilitas::with([
            'fasilitas',
            'laporan',
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
            'laporan.pengguna',
            'kategoriKerusakan',
            'status',
            'skorTopsis',
            'penugasan',
            'penugasan.teknisi',
            'penilaianPengguna',
        ])->get();


        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();  // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Laporan Fasilitas');
        $sheet->setCellValue('C1', 'Nama Fasilitas');
        $sheet->setCellValue('D1', 'Ruangan');
        $sheet->setCellValue('E1', 'Lantai');
        $sheet->setCellValue('F1', 'Gedung');
        $sheet->setCellValue('G1', 'Kategori Kerusakan');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Jumlah Rusak');
        $sheet->setCellValue('J1', 'Deskripsi');
        $sheet->setCellValue('K1', 'Teknisi');
        $sheet->setCellValue('L1', 'Skor Topsis');
        $sheet->setCellValue('M1', 'Pelapor');
        $sheet->setCellValue('N1', 'Nilai Umpan Balik');
        $sheet->setCellValue('O1', 'Komentar Umpan Balik');


        $sheet->getStyle('A1:O1')->getFont()->setBold(true);  // bold header

        $no = 1;                  // nomor data dimulai dari 1
        $baris = 2;               // baris data dimulai dari baris ke 2
        foreach ($LaporanFasilitas as $key => $value) {
            if ($value->status->nama_status === 'Selesai') {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->laporan->id_laporan);
                $sheet->setCellValue('C' . $baris, $value->fasilitas->nama_fasilitas);
                $sheet->setCellValue('D' . $baris, $value->laporan->ruangan->nama_ruangan);
                $sheet->setCellValue('E' . $baris, $value->laporan->lantai->nomor_lantai);
                $sheet->setCellValue('F' . $baris, $value->laporan->gedung->nama_gedung);
                $sheet->setCellValue('G' . $baris, $value->kategoriKerusakan->nama_kerusakan);
                $sheet->setCellValue('H' . $baris, $value->status->nama_status);
                $sheet->setCellValue('I' . $baris, $value->jumlah_rusak);
                $sheet->setCellValue('J' . $baris, $value->deskripsi);
                $sheet->setCellValue('K' . $baris, optional($value->penugasan?->teknisi)->nama ?? '-');
                $sheet->setCellValue('L' . $baris, $value->skorTopsis->first()?->skor ?? '-');
                $sheet->setCellValue('M' . $baris, $value->laporan->pengguna->nama);
                $sheet->setCellValue('N' . $baris, $value->penilaianPengguna->nilai ?? '-');
                $sheet->setCellValue('O' . $baris, $value->penilaianPengguna->komentar ?? '-');

                $baris++;
                $no++;
            }
        }

        foreach (range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Rekap Laporan'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Rekap Laporan ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $data = LaporanFasilitas::with([
            'fasilitas',
            'laporan',
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
            'laporan.pengguna',
            'kategoriKerusakan'
        ])->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('riwayat.exportPdfChart', ['data' => $data]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Tren Kerusakan ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function chartTren()
    {

        // Line chart tahunan
        $labelsTahun = collect(range(0, 11))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('m-Y');
        })->reverse()->values();

        $countsTahun = $labelsTahun->map(function ($month) {
            return LaporanFasilitas::whereYear('created_at', substr($month, 3, 4))
                ->whereMonth('created_at', substr($month, 0, 2))
                ->count();
        });

        if ($countsTahun->sum() == 0) {
            $chartTahun = [
                'type' => 'bar',
                'data' => [
                    'labels' => ['Tidak ada data'],
                    'datasets' => [[
                        'label' => 'Jumlah Kerusakan',
                        'data' => [0],
                        'backgroundColor' => 'rgb(220, 53, 69)',
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Tidak Ada Data Tahunan'
                        ]
                    ]
                ]
            ];
        } else {
            $chartTahun = [
                'type' => 'line',
                'data' => [
                    'labels' => $labelsTahun,
                    'datasets' => [[
                        'label' => 'Jumlah Kerusakan',
                        'data' => $countsTahun,
                        'fill' => false,
                        'borderColor' => 'rgb(0, 255, 72)',
                        'tension' => 0.3,
                    ]]
                ],
                'options' => [
                    'scales' => [
                        'y' => [
                            'title' => ['display' => true, 'text' => 'Laporan Kerusakan'],
                            'ticks' => ['stepSize' => 1, 'beginAtZero' => true, 'precision' => 0]
                        ],
                        'x' => [
                            'title' => ['display' => true, 'text' => 'Bulan']
                        ]
                    ]
                ]
            ];
        }

        // Line chart bulanan
        $labelsBulanan = collect(range(0, 29))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('d-m-Y');
        })->reverse()->values();

        $countsBulanan = $labelsBulanan->map(function ($date) {
            return LaporanFasilitas::whereDate('created_at', Carbon::createFromFormat('d-m-Y', $date))->count();
        });

        if ($countsBulanan->sum() == 0) {
            $chartBulan = [
                'type' => 'bar',
                'data' => [
                    'labels' => ['Tidak ada data'],
                    'datasets' => [[
                        'label' => 'Jumlah Kerusakan',
                        'data' => [0],
                        'backgroundColor' => 'rgb(220, 53, 69)',
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Tidak Ada Data Bulanan'
                        ]
                    ]
                ]
            ];
        } else {
            $chartBulan = [
                'type' => 'line',
                'data' => [
                    'labels' => $labelsBulanan,
                    'datasets' => [[
                        'label' => 'Jumlah Kerusakan',
                        'data' => $countsBulanan,
                        'fill' => false,
                        'borderColor' => 'rgb(75, 192, 192)',
                        'tension' => 0.3,
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Grafik Kerusakan Fasilitas Satu Bulan'
                        ]
                    ],
                    'scales' => [
                        'y' => [
                            'ticks' => ['stepSize' => 1, 'beginAtZero' => true, 'precision' => 0]
                        ]
                    ]
                ]
            ];
        }

        // Pie chart perbaikan/penggantian
        $sum = LaporanFasilitas::count();
        if ($sum == 0) {
            $configPerbaikan = [
                'type' => 'pie',
                'data' => [
                    'labels' => ['Tidak ada data'],
                    'datasets' => [[
                        'data' => [100],
                        'backgroundColor' => ['rgb(201, 203, 207)']
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Data Tidak Tersedia'
                        ]
                    ]
                ]
            ];
        } else {
            $persenPerbaikan = Perbaikan::where('jenis_perbaikan', 'perbaikan')->count() / $sum * 100;
            $persenPenggantian = Perbaikan::where('jenis_perbaikan', 'penggantian')->count() / $sum * 100;

            $configPerbaikan = [
                'type' => 'pie',
                'data' => [
                    'labels' => ['Perbaikan (%)', 'Penggantian (%)'],
                    'datasets' => [[
                        'label' => 'Jumlah Kerusakan',
                        'data' => [$persenPerbaikan, $persenPenggantian],
                        'backgroundColor' => [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)'
                        ]
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'legend' => [
                            'labels' => [
                                'color' => '#ffffff',
                                'font' => ['size' => 30, 'weight' => 'bold']
                            ]
                        ],
                        'tooltip' => [
                            'bodyFont' => ['size' => 30, 'weight' => 'bold'],
                            'titleFont' => ['size' => 30, 'weight' => 'bold']
                        ]
                    ]
                ]
            ];
        }

        // Doughnut chart penilaian pengguna
        $dataRespon = PenilaianPengguna::select('nilai')
            ->groupBy('nilai')
            ->selectRaw('nilai, COUNT(*) as total')
            ->pluck('total', 'nilai');

        $nilaiRange = range(0, 5);
        $chartData = [];
        $labels = [];

        foreach ($nilaiRange as $nilai) {
            $chartData[] = $dataRespon->get($nilai, 0);
            $labels[] = "Bintang $nilai";
        }

        $totalRespon = array_sum($chartData);

        if ($totalRespon == 0) {
            $chartConfig = [
                "type" => "doughnut",
                "data" => [
                    "labels" => ["Tidak ada data"],
                    "datasets" => [[
                        "data" => [100],
                        "backgroundColor" => ["#CCCCCC"]
                    ]]
                ],
                "options" => [
                    "plugins" => [
                        "doughnutlabel" => [
                            "labels" => [
                                ["text" => "0", "font" => ["size" => 50]],
                                ["text" => "Total Ulasan"]
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $chartConfig = [
                "type" => "doughnut",
                "data" => [
                    "labels" => $labels,
                    "datasets" => [[
                        "data" => $chartData,
                        "backgroundColor" => [
                            "#FF6384",
                            "#00FF48",
                            "#FFCE56",
                            "#4BC0C0",
                            "#9966FF",
                            "#FF9F40"
                        ]
                    ]]
                ],
                "options" => [
                    "plugins" => [
                        "doughnutlabel" => [
                            "labels" => [
                                ["text" => "$totalRespon", "font" => ["size" => 50]],
                                ["text" => "Total Ulasan"]
                            ]
                        ]
                    ]
                ]
            ];
        }

        // $chartBulanan = "https://quickchart.io/chart?c=" . urlencode(json_encode($chartBulan));
        // $chartTahunan = "https://quickchart.io/chart?c=" . urlencode(json_encode($chartTahun));
        // $chartPerbaikan = "https://quickchart.io/chart?c=" . urlencode(json_encode($configPerbaikan));
        // $chartRespon = "https://quickchart.io/chart?c=" . urlencode(json_encode($chartConfig));

        // Line Chart Bulanan
        $hasDataBulanan = $countsBulanan->sum() > 0;
        $chartBulanan = $hasDataBulanan
            ? "https://quickchart.io/chart?c=" . urlencode(json_encode($chartBulan))
            : null;

        // Line Chart Tahunan
        $hasDataTahunan = $countsTahun->sum() > 0;
        $chartTahunan = $hasDataTahunan
            ? "https://quickchart.io/chart?c=" . urlencode(json_encode($chartTahun))
            : null;

        // Pie Chart Perbaikan
        $sum = perbaikan::count();
        $chartPerbaikan = null;
        if ($sum > 0) {
            $persenPerbaikan = Perbaikan::where('jenis_perbaikan', 'perbaikan')->count() / $sum * 100;
            $persenPenggantian = Perbaikan::where('jenis_perbaikan', 'penggantian')->count() / $sum * 100;

            $chartPerbaikan = "https://quickchart.io/chart?c=" . urlencode(json_encode($configPerbaikan));
        }

        // Doughnut Chart Respon
        $hasRespon = $totalRespon > 0;
        $chartRespon = $hasRespon
            ? "https://quickchart.io/chart?c=" . urlencode(json_encode($chartConfig))
            : null;


        $pdf = Pdf::loadView('riwayat.exportPdfChart', [
            'chartBulanan' => $chartBulanan,
            'chartTahunan' => $chartTahunan,
            'chartPerbaikan' => $chartPerbaikan,
            'chartRespon' => $chartRespon,
            'tanggal' => now()->format('d-m-Y'),
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->download('Grafik Kerusakan - ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
