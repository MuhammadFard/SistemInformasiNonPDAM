<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['customer_id','user_id','alamat','rt','rw','nomor_telepon','kwh_category_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function kwhCategory()
    {
        return $this->belongsTo(KwhCategory::class, 'kwh_category_id','kwh_category_id');
    }

    public function documents()
    {
        return $this->hasMany(CustomerDocument::class, 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }
}
