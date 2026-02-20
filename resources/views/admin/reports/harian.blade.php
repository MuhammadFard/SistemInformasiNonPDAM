@extends('adminlte::page')

@section('title', 'Laporan Harian')

@section('content_header')
<h1>Laporan Harian - {{ \Carbon\Carbon::parse($today)->format('d-m-Y') }}</h1>
@stop

@section('content')
<div class="d-print-none mb-3">
    <form method="GET" action="{{ route('admin.reports.harian') }}">
        <div class="row">
            <div class="col-md-3">
                <label>Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.reports.harian') }}" class="btn btn-secondary mr-2">Reset</a>
                </div>
            </div>
    </form>
        <div class="mb-3 mt-3">
            <button onclick="window.print()" class="btn btn-success mr-2">
                <i class="fas fa-print"></i> Print A4
            </button>
            <a href="{{ route('admin.reports.harian.pdf', request()->all()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
</div>

<div class="card d-print-none">
    <div class="card-body p-0">
        <table class="table table-bordered table-striped mb-0">
            <thead class="bg-light">
                <tr class="text-center">
                    <th>No. SKR</th>
                    <th>Nama Pelanggan</th>
                    <th>Jatuh Tempo</th>
                    <th>Ketetapan</th>
                    <th>Realisasi</th>
                    <th>Tunggakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td class="text-center">{{ $invoice->nomor_invoice }}</td>
                    <td>{{ $invoice->customer->user->nama }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                    <td class="text-right">Rp {{ number_format($invoice->ketetapan, 0, ',', '.') }}</td>
                    <td class="text-right text-success">Rp {{ number_format($invoice->realisasi, 0, ',', '.') }}</td>
                    <td class="text-right {{ $invoice->tunggakan > 0 ? 'text-danger font-weight-bold' : '' }}">
                        Rp {{ number_format($invoice->tunggakan, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="print-area d-none d-print-block">
    <div class="header-pdf">
        <p>LEMBAGA PENGELOLA SAMPAH</p>
        <p>LUBLIN BERSERI</p>
        <p class="title">LAPORAN RETRIBUSI PERSAMPAHAN RUMAH TANGGA</p>
        <p>PERIODE: {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d/m/Y') : 'AWAL' }} - {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d/m/Y') : 'SEKARANG' }}</p>
    </div>

    <table class="table-pdf">
        <thead>
            <tr>
                <th>NO</th>
                <th>NO. SKR</th>
                <th>NAMA PELANGGAN</th>
                <th>JATUH TEMPO</th>
                <th>TGL BAYAR</th>
                <th>KETETAPAN</th>
                <th>REALISASI</th>
                <th>TARGET</th>
                <th>TUNGGAKAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $index => $invoice)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $invoice->nomor_invoice }}</td>
                <td>{{ strtoupper($invoice->customer->user->nama) }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $invoice->tanggal_bayar ? \Carbon\Carbon::parse($invoice->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                <td class="text-right">{{ number_format($invoice->ketetapan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($invoice->realisasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($invoice->target, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($invoice->tunggakan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="5" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totalKetetapan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalTarget, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalTunggakan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-pdf">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 33%; text-align: center;">
                    Mengetahui,<br>Ketua LPS Lublin Berseri
                    <br><br><br><br>
                    <strong>MUSRA HIDAYATI</strong>
                </td>
                <td style="border: none; width: 33%;"></td>
                <td style="border: none; width: 33%; text-align: center;">
                    Lubuk Lintah, {{ now()->translatedFormat('d F Y') }}<br>Bendahara
                    <br><br><br><br>
                    <strong>(..........................)</strong>
                </td>
            </tr>
        </table>
    </div>
</div>
@stop

@push('css')
<style>
    /* Styling khusus Area Cetak */
    .header-pdf { text-align: center; margin-bottom: 20px; }
    .header-pdf p { margin: 2px 0; font-size: 12pt; }
    .header-pdf .title { font-weight: bold; text-decoration: underline; font-size: 14pt; }

    .table-pdf { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table-pdf th, .table-pdf td { border: 1px solid black; padding: 5px; font-size: 10pt; }
    .table-pdf th { background-color: #f2f2f2 !important; text-align: center; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-bold { font-weight: bold; }
    .footer-pdf { margin-top: 30px; width: 100%; }

    /* CSS Tambahan: Pastikan tabel footer tidak punya border secara spesifik */
    .footer-pdf table,
    .footer-pdf tr,
    .footer-pdf td {
        border: none !important;
    }

    /* Pengaturan CSS untuk Print Browser */
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        /* Sembunyikan elemen bawaan AdminLTE */
        .main-sidebar, .main-header, .content-header, .main-footer, .card-header {
            display: none !important;
        }
        .content-wrapper { margin-left: 0 !important; border: none !important; }
        body { background-color: white !important; }

        /* Paksa tabel agar garisnya muncul */
        table, th, td { border: 1px solid black !important; }

        /* Pastikan footer tetap bersih dari garis saat print */
        .footer-pdf table,
        .footer-pdf tr,
        .footer-pdf td {
            border: none !important;
        }
    }
</style>
@endpush
