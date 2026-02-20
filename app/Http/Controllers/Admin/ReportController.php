<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class ReportController extends Controller
{
    // public function harian(Request $request)
    // {
    //     $today = Carbon::today()->format('Y-m-d');
    //     $query = Invoice::with(['customer.user'])
    //         ->whereNotNull('tanggal_jatuh_tempo');
    //     if ($request->from && $request->to) {
    //         $query->whereBetween('tanggal_jatuh_tempo', [
    //             $request->from,
    //             $request->to
    //         ]);
    //     }
    //     $invoices = $query->get();
    //     $total = $invoices->sum('total_tagihan');
    //     return view('admin.reports.harian', compact('invoices','total','today'));
    // }

    // public function harianPdf(Request $request)
    // {
    //     $today = Carbon::today()->format('Y-m-d');

    //     $query = Invoice::with(['customer.user'])
    //         ->whereNotNull('tanggal_jatuh_tempo');

    //     if ($request->from && $request->to) {
    //         $query->whereBetween('tanggal_jatuh_tempo', [
    //             $request->from,
    //             $request->to
    //         ]);
    //     } else {
    //         $query->whereDate('tanggal_jatuh_tempo', $today);
    //     }

    //     $invoices = $query->get();
    //     $total = $invoices->sum('total_tagihan');

    //     $pdf = Pdf::loadView('admin.reports.harian_pdf',
    //             compact('invoices','total','today'))
    //             ->setPaper('A4','landscape');

    //     return $pdf->download('laporan-harian.pdf');
    // }

    public function harian(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');

        // Default query: Ambil semua yang punya tanggal jatuh tempo
        $query = Invoice::with(['customer.user'])
            ->whereNotNull('tanggal_jatuh_tempo');

        // Jika user memilih range tanggal, baru kita filter
        if ($request->from && $request->to) {
            $query->whereBetween('tanggal_jatuh_tempo', [
                $request->from,
                $request->to
            ]);
        }
        // Jika tidak ada request from/to, program akan melewati kondisi ini
        // dan mengambil semua data dari database.

        $invoices = $query->get()->map(function ($invoice) {
            $invoice->ketetapan = $invoice->total_tagihan;
            $invoice->target = $invoice->ketetapan;
            $invoice->realisasi = ($invoice->status === 'paid') ? $invoice->ketetapan : 0;
            $invoice->tunggakan = $invoice->target - $invoice->realisasi;
            return $invoice;
        });

        $totalKetetapan = $invoices->sum('ketetapan');
        $totalRealisasi = $invoices->sum('realisasi');
        $totalTarget    = $invoices->sum('target');
        $totalTunggakan = $invoices->sum('tunggakan');

        return view('admin.reports.harian', compact(
            'invoices', 'totalKetetapan', 'totalRealisasi', 'totalTarget', 'totalTunggakan', 'today'
        ));
    }
    public function harianPdf(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        $query = Invoice::with(['customer.user'])
            ->whereNotNull('tanggal_jatuh_tempo');

        if ($request->from && $request->to) {
            $query->whereBetween('tanggal_jatuh_tempo', [
                $request->from,
                $request->to
            ]);
        }
        // Bagian else { $query->whereDate(..., $today); } DIHAPUS agar tampil semua data

        $invoices = $query->get()->map(function ($invoice) {
            $invoice->ketetapan = $invoice->total_tagihan;
            $invoice->target = $invoice->ketetapan;
            $invoice->realisasi = ($invoice->status === 'paid') ? $invoice->ketetapan : 0;
            $invoice->tunggakan = $invoice->target - $invoice->realisasi;
            return $invoice;
        });

        $totalKetetapan = $invoices->sum('ketetapan');
        $totalRealisasi = $invoices->sum('realisasi');
        $totalTarget    = $invoices->sum('target');
        $totalTunggakan = $invoices->sum('tunggakan');

        $pdf = Pdf::loadView('admin.reports.harian_pdf',
            compact('invoices', 'totalKetetapan', 'totalRealisasi', 'totalTarget', 'totalTunggakan', 'today'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan-semua-harian.pdf');
    }
    public function bulanan(Request $request)
    {
        if(!in_array(Auth::user()->role, ['superadmin','viewer'])) {
            abort(403);
        }

        $month = $request->month;
        $year  = $request->year ?? now()->year;

        $query = Invoice::with(['customer.user'])
            ->whereNotNull('tanggal_jatuh_tempo');

        if ($month) {
            $query->whereMonth('tanggal_jatuh_tempo', $month);
        }

        $query->whereYear('tanggal_jatuh_tempo', $year);

        $invoices = $query->get()->map(function ($invoice) {
            $invoice->ketetapan = $invoice->total_tagihan;

            $invoice->target = $invoice->ketetapan;
            $invoice->realisasi = ($invoice->status === 'paid') ? $invoice->ketetapan : 0;
            $invoice->tunggakan = $invoice->target - $invoice->realisasi;

            return $invoice;
        });

        $totalKetetapan = $invoices->sum('ketetapan');
        $totalRealisasi = $invoices->sum('realisasi');
        $totalTarget    = $invoices->sum('target');
        $totalTunggakan = $invoices->sum('tunggakan');

        return view('admin.reports.bulanan', compact(
            'invoices', 'totalKetetapan', 'totalRealisasi', 'totalTarget', 'totalTunggakan', 'month', 'year'
        ));
    }
    public function bulananPdf(Request $request)
    {
        $month = $request->month ? (int)$request->month : now()->month;
        $year  = $request->year ? (int)$request->year : now()->year;

        $query = Invoice::with(['customer.user', 'customer.kwhCategory'])
            ->whereNotNull('tanggal_jatuh_tempo');

        if ($month) {
            $query->whereMonth('tanggal_jatuh_tempo', $month);

            if ($year) {
                $query->whereYear('tanggal_jatuh_tempo', $year);
            }
        }

        if (!$month && $year) {
            $query->whereYear('tanggal_jatuh_tempo', $year);
        }

        $invoices = $query->get()->map(function ($invoice) {
            $invoice->ketetapan = $invoice->total_tagihan;
            $invoice->target = $invoice->ketetapan;
            $invoice->realisasi = ($invoice->status === 'paid') ? $invoice->ketetapan : 0;
            $invoice->tunggakan = $invoice->target - $invoice->realisasi;

            return $invoice;
        });

        $totalKetetapan = $invoices->sum('ketetapan');
        $totalRealisasi = $invoices->sum('realisasi');
        $totalTarget    = $invoices->sum('target');
        $totalTunggakan = $invoices->sum('tunggakan');

        $namaBulan = \Carbon\Carbon::create()->month($month)->translatedFormat('F');

        $isPdf = true;

        $pdf = Pdf::loadView('admin.reports.bulanan_pdf', compact(
                'invoices',
                'totalKetetapan',
                'totalRealisasi',
                'totalTarget',
                'totalTunggakan',
                'month',
                'namaBulan',
                'year',
                'isPdf'
            ))
            ->setPaper('A4', 'landscape'); // Pastikan Landscape agar muat banyak kolom

        return $pdf->download('laporan-bulanan-' . strtolower($namaBulan) . '-' . $year . '.pdf');
    }
}
