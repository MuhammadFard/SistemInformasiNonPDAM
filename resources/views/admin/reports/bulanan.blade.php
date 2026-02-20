@extends('adminlte::page')

@section('title', 'Laporan Bulanan')

@if(!isset($isPdf))
    @section('content_header')
        <h1>Laporan Bulanan - {{ $month }}/{{ $year }}</h1>
    @stop
@endif

@section('content')
<div class="{{ isset($isPdf) ? 'd-none' : 'd-print-none' }}">
    <form method="GET" action="{{ route('admin.reports.bulanan') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label>Bulan</label>
                <select name="month" class="form-control">
                    <option value="">-- Semua Bulan --</option>
                    @php
                        $months = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                    @endphp
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Tahun</label>
                <input type="number" name="year" value="{{ $year }}" class="form-control">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-primary mr-2">Filter</button>
                <a href="{{ route('admin.reports.bulanan') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="mb-3">
        <button onclick="window.print()" class="btn btn-success">
            <i class="fa fa-print"></i> Print A4
        </button>

        <a href="{{ route('admin.reports.bulanan.pdf', request()->all()) }}" class="btn btn-danger">
            <i class="fa fa-file-pdf"></i> Export PDF
        </a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
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
                        <td class="text-center">{{ $invoice->customer->user->nama }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            {{ $invoice->tanggal_bayar ? \Carbon\Carbon::parse($invoice->tanggal_bayar)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center">Rp {{ number_format($invoice->ketetapan, 0, ',', '.') }}</td>
                        <td class="text-center text-success">Rp {{ number_format($invoice->realisasi, 0, ',', '.') }}</td>
                        <td class="text-center">Rp {{ number_format($invoice->target, 0, ',', '.') }}</td>
                        <td class="text-center {{ $invoice->tunggakan > 0 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                            Rp {{ number_format($invoice->tunggakan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>    
            </table>
        </div>
    </div>
</div>
<div class="{{ isset($isPdf) ? '' : 'd-none d-print-block' }} print-area">
    
    <div class="header-pdf">
        <p>LEMBAGA PENGELOLA SAMPAH</p>
        <p>LUBLIN BERSERI</p>
        <p class="title">LAPORAN RETRIBUSI PERSAMPAHAN RUMAH TANGGA NON PDAM</p>
        <p>LPS LUBLIN BERSERI</p>
        <p>KELURAHAN LUBUK LINTAH</p>
        <p>TAHUN {{ $year }}</p>
    </div>

    <table class="table-pdf">
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
                $p_totalKetetapan = 0; $p_totalRealisasi = 0; $p_totalTarget = 0; $p_totalTunggakan = 0;
            @endphp
            @foreach($invoices as $index => $invoice)
            @php
                $p_ketetapan = $invoice->total_tagihan;
                $p_realisasi = ($invoice->status === 'paid') ? $p_ketetapan : 0;
                $p_target    = $p_ketetapan; 
                $p_tunggakan = $p_target - $p_realisasi;

                $p_totalKetetapan += $p_ketetapan;
                $p_totalRealisasi += $p_realisasi;
                $p_totalTarget    += $p_target;
                $p_totalTunggakan += $p_tunggakan;
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
                <td class="text-center">{{ number_format($p_ketetapan, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($p_realisasi, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($p_target, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($p_tunggakan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="8" class="text-center">TOTAL</td>
                <td class="text-center">{{ number_format($p_totalKetetapan, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($p_totalRealisasi, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($p_totalTarget, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($p_totalTunggakan, 0, ',', '.') }}</td>
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
    .print-area {
        font-family: Arial, sans-serif;
        font-size: 10px;
        color: black;
        width: 100%;
    }
    .header-pdf {
        text-align: center;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .header-pdf p { margin: 2px 0; }
    .header-pdf .title { font-weight: bold; font-size: 14px; text-decoration: underline; }

    .table-pdf {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
    }
    .table-pdf th, .table-pdf td {
        border: 1px solid black;
        padding: 4px 2px;
        word-wrap: break-word;
    }
    .table-pdf th {
        background-color: #f2f2f2;
        text-align: center;
        vertical-align: middle;
    }
    
    .footer-pdf { margin-top: 30px; width: 100%; }

    .text-center { text-align: center; }
    .font-bold { font-weight: bold; }
    .col-no { width: 25px; }
    .col-skr { width: 70px; }
    .col-nama { width: 120px; }

    /* CSS KHUSUS SAAT MODE BROWSER PRINT (CTRL+P) */
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        .main-sidebar, .main-header, .content-header, .main-footer {
            display: none !important;
        }
        .content-wrapper, .content, body, html {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            width: 100% !important;
        }
        /* Paksa background color muncul */
        th {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush