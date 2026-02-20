<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    protected $primaryKey = 'payment_proof_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'invoice_id',
        'file_bukti',
        'status'
    ];
    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id',
            'invoice_id'
        );
    }
}
