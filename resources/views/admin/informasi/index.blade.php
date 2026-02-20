@extends('adminlte::page')

@section('title', 'Informasi Pembayaran')

@section('content')
<div class="container-fluid mb-3">
    <div class="row mb-3">
        <div class="col-md-8 mx-auto">
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Informasi Pembayaran Resmi</h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info mb-4">
                        <h5>Pemberitahuan Penting!</h5>
                        <p>Mohon melakukan pembayaran tagihan tepat waktu setiap bulannya melalui jalur resmi di bawah ini untuk menghindari denda atau pemutusan layanan.</p>
                    </div>

                    <div class="list-group">
                        <div class="list-group-item">
                            <h6 class="font-weight-bold text-primary">1. Bendahara di Kantor LPS</h6>
                            <p class="mb-0 text-muted small">Pembayaran tunai langsung melalui loket pembayaran di kantor.</p>
                        </div>
                        <div class="list-group-item">
                            <h6 class="font-weight-bold text-primary">2. Operator Pemungut Sampah</h6>
                            <p class="mb-2">Pembayaran bisa dititipkan ke petugas lapangan.</p>
                            <div class="alert alert-warning mb-0 py-1 px-2">
                                <small><strong>Catatan:</strong> Jika sudah dibayar, harap Bapak/Ibu meminta <strong>stempel</strong> pada bukti setoran dan kirimkan melalui aplikasi.</small>
                            </div>
                        </div>
                        <div class="list-group-item text-center bg-light">
                            <h6 class="font-weight-bold text-primary">3. Transfer Bank Nagari</h6>
                            <h4 class="font-weight-bold mb-0 text-dark">1006.0210.18679.5</h4>
                            <p class="mb-0">an. <strong>LPS Lublin Berseri</strong></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <span class="text-muted small">Lembaga Pengelola Sampah (LPS) Lublin Berseri</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection