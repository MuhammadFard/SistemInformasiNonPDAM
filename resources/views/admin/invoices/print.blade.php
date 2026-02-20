@php
    // Fungsi simpel untuk mengubah angka menjadi teks terbilang
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai / 10)." puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
        }
        return $temp;
    }

    function terbilang($nilai) {
        if($nilai < 0) { $hasil = "minus ". trim(penyebut($nilai)); }
        else { $hasil = trim(penyebut($nilai)); }
        return ucwords($hasil) . " Rupiah";
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'serif'; font-size: 12pt; padding: 10px; line-height: 1.2; }
        .kuitansi-wrapper { width: 100%; border: 1px solid #000; padding: 15px; box-sizing: border-box; }

        /* Header */
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 15px; }
        .title { font-size: 16pt; font-weight: bold; }

        /* Content */
        .row { display: flex; margin-bottom: 10px; }
        .label { width: 150px; }
        .dot { width: 20px; }
        .val { flex: 1; }

        .box-money { border: 1px solid #000; padding: 5px 10px; font-weight: bold; display: inline-block; background: #eee; }
        .italic { font-style: italic; }

        /* Signature area */
        .footer-table { width: 100%; margin-top: 20px; }
        .footer-table td { width: 50%; text-align: center; vertical-align: top; }
        .space { height: 60px; }
    </style>
</head>
<body>
    <div class="kuitansi-wrapper">
        <div class="header">
            <div class="title">KUITANSI PEMBAYARAN</div>
            <div style="font-size: 10pt; text-align: right;">
                No. Bukti: {{ $invoice->nomor_invoice }} <br>
                Tahun: {{ date('Y') }}
            </div>
        </div>

        <div class="row">
            <div class="label">Telah Terima Dari</div>
            <div class="dot">:</div>
            <div class="val"><strong>{{ strtoupper($invoice->customer->user->nama) }}</strong></div>
        </div>

        <div class="row">
            <div class="label">Uang Sebesar</div>
            <div class="dot">:</div>
            <div class="val"><span class="box-money">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span></div>
        </div>

        <div class="row">
            <div class="label">Untuk Pembayaran</div>
            <div class="dot">:</div>
            <div class="val">Retribusi Sampah Bulanan ({{ $invoice->customer->kwhCategory->nama_kategori }}). {{ $invoice->catatan }}</div>
        </div>

        <div class="row">
            <div class="label">Terbilang</div>
            <div class="dot">:</div>
            <div class="val italic">({{ terbilang($invoice->total_tagihan) }})</div>
        </div>

        <table class="footer-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Ketua LPS Lublin Berseri
                    <div class="space"></div>
                    <strong>MUSRA HIDAYATI</strong>
                </td>
                <td>
                    Padang, {{ date('d F Y') }}<br>
                    Bendahara / Penerima
                    <div class="space"></div>
                    <strong>( _________________ )</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
