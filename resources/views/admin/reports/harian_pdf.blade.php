<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .tanggal {
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            background-color: #f2f2f2;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .ttd {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<h2>LAPORAN HARIAN PEMBAYARAN</h2>
<div class="tanggal">
    Tanggal: {{ \Carbon\Carbon::parse($today)->format('d-m-Y') }}
</div>

<table class="table-pdf">
    <thead>
        <tr>
            <th>No. SKR</th>
            <th>Nama Pelanggan</th>
            <th>Jatuh Tempo</th>
            <th>Tgl Bayar</th>
            <th>Ketetapan</th>
            <th>Realisasi</th>
            <th>Target</th>
            <th>Tunggakan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
        <tr>
            <td class="text-center">{{ $invoice->nomor_invoice }}</td>
            <td>{{ $invoice->customer->user->nama }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
            <td class="text-center">
                {{ $invoice->tanggal_bayar ? \Carbon\Carbon::parse($invoice->tanggal_bayar)->format('d/m/Y') : '-' }}
            </td>
            <td class="text-right">Rp {{ number_format($invoice->ketetapan, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($invoice->realisasi, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($invoice->target, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($invoice->tunggakan, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="font-bold">
            <td colspan="4" class="text-center">TOTAL</td>
            <td class="text-right">Rp {{ number_format($totalKetetapan, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($totalTarget, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
<div class="ttd">
    <p>Mengetahui,</p>
    <p>Ketua LPS</p>
    <br><br><br>
    <p><b>Musra Hidayati</b></p>
</div>

</body>
</html>


