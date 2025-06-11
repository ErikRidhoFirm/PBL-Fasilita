<!DOCTYPE html>
<html>

<head>
    <title>Export PDF Grafik</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
        }

        img {
            width: 100%;
            max-width: 600px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        h3 {
            margin-top: 2em
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: 80px;
            height: 80px;
            /* max-width: 150px;
            max-height: 150px; */
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }

        .img-chart {
            width: 400px;
        }

        .chart {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .axis-label {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: grey
        }

        .chart-container {
            /* page-break-inside: avoid; */
            margin-bottom: 2em;
        }

        p em {
            color: gray;
            font-size: 12pt;
        }
    </style>
</head>

<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img src="{{ asset('assets/images/polinema-bw.jpeg') }}"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN
                    PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI
                    MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang
                    65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101
                    105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>
    <h2>Grafik Kerusakan Fasilitas</h2>
    <p>Tanggal Cetak: {{ $tanggal }}</p>

    <div class="chart-container">
        <h3>Grafik Dalam 30 Hari</h3>
        @if ($chartBulanan)
            <p class="axis-label" style="text-align: left; margin-bottom: -300px; margin-left: 8em">Laporan<br>masuk
            </p>
            <img class="img-chart" src="{{ $chartBulanan }}" alt="Grafik Kerusakan">
            <p class="axis-label">Hari</p>
        @else
            <p><em>Data belum tersedia untuk grafik bulanan.</em></p>
        @endif

        <br><br>

        <h3>Grafik Dalam 12 Bulan</h3>
        @if ($chartTahunan)
            <p class="axis-label" style="text-align: left; margin-bottom: -300px; margin-left: 8em">Laporan<br>masuk
            </p>
            <img class="img-chart" src="{{ $chartTahunan }}" alt="Grafik Kerusakan">
            <p class="axis-label">Bulan</p>
        @else
            <p><em>Data belum tersedia untuk grafik tahunan.</em></p>
        @endif

        <br><br>

        <h3 style="margin-top: 4em">Frekuensi Perbaikan</h3>
        @if ($chartPerbaikan)
            <img class="img-chart" src="{{ $chartPerbaikan }}" alt="Grafik Perbaikan">
        @else
            <p><em>Data belum tersedia untuk diagram perbaikan.</em></p>
        @endif

        <br><br>

        <h3>Umpan Balik Pengguna</h3>
        @if ($chartRespon)
            <img class="img-chart" src="{{ $chartRespon }}" alt="Grafik Respon Pengguna">
        @else
            <p><em>Data belum ada untuk umpan balik pengguna.</em></p>
        @endif
    </div>
</body>

</html>
