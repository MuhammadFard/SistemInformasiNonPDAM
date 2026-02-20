<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['customer_id','nomor_invoice','total_tagihan','tanggal_bayar','tanggal_jatuh_tempo','catatan','status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
    public function paymentProof()
    {
        return $this->hasOne(
            PaymentProof::class,
            'invoice_id',   // foreign key di payment_proofs
            'invoice_id'    // primary key di invoices
        );
    }
}
