<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Retribusi Persampahan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Font diperkecil agar muat banyak kolom */
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .header p {
            margin: 2px 0;
        }

        .header .title {
            font-weight: bold;
            font-size: 14px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid black;
            padding: 4px 2px;
            word-wrap: break-word;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 9px;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        /* Mengatur lebar kolom agar proporsional */
        .col-no { width: 25px; }
        .col-skr { width: 70px; }
        .col-nama { width: 120px; }
    </style>
</head>
<body>

<div class="header">
    <p>LEMBAGA PENGELOLA SAMPAH</p>
    <p>LUBLIN BERSERI</p>
    <p class="title">LAPORAN RETRIBUSI PERSAMPAHAN RUMAH TANGGA NON PDAM</p>
    <p>LPS LUBLIN BERSERI</p>
    <p>KELURAHAN LUBUK LINTAH</p>
    <p>TAHUN {{ $year }}</p>
</div>

<table>
    <thead>
        <tr>
            <th class="col-no">NO</th>
            <th class="col-skr">NO. SKR</th>
            <th style="width: 30px;">RT</th>
            <th style="width: 30px;">RW</th>
            <th>ALAMAT</th>
            <th class="col-nama">NAMA PELANGGAN</th>
            <th>JATUH TEMPO</th>
            <th>TGL BAYAR</th>
            <th>KETETAPAN RETRIBUSI</th>
            <th>REALISASI</th>
            <th>TARGET</th>
            <th>TUNGGAKAN</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $totalKetetapan = 0;
            $totalRealisasi = 0;
            $totalTarget = 0;
            $totalTunggakan = 0;
        @endphp
        @foreach($invoices as $index => $invoice)
        @php
            $ketetapan = $invoice->total_tagihan;
            $realisasi = ($invoice->status === 'paid') ? $ketetapan : 0;
            $target = $ketetapan;
            $tunggakan = $target - $realisasi;

            $totalKetetapan += $ketetapan;
            $totalRealisasi += $realisasi;
            $totalTarget += $target;
            $totalTunggakan += $tunggakan;
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ $invoice->nomor_invoice }}</td>
            <td class="text-center">{{ $invoice->customer->rt ?? '-' }}</td>
            <td class="text-center">{{ $invoice->customer->rw ?? '-' }}</td>
            <td>{{ $invoice->customer->alamat ?? 'Lubuk Lintah' }}</td>
            <td>{{ strtoupper($invoice->customer->user->nama) }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
            <td class="text-center">{{ $invoice->tanggal_bayar ? \Carbon\Carbon::parse($invoice->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
            <td class="text-center">{{ number_format($ketetapan, 0, ',', '.') }}</td>
            <td class="text-center">{{ number_format($realisasi, 0, ',', '.') }}</td>
            <td class="text-center">{{ number_format($target, 0, ',', '.') }}</td>
            <td class="text-center">{{ number_format($tunggakan, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="font-bold">
            <td colspan="8" class="text-center">TOTAL</td>
            <td class="text-center">{{ number_format($totalKetetapan, 0, ',', '.') }}</td>
            <td class="text-center">{{ number_format($totalRealisasi, 0, ',', '.') }}</td>
            <td class="text-center">{{ number_format($totalTarget, 0, ',', '.') }}</td>
            <td class="text-center">{{ number_format($totalTunggakan, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

</body>
</html>