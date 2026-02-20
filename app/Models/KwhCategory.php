<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KwhCategory extends Model
{
    protected $primaryKey = 'kwh_category_id';

    // kalau auto increment
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['daya', 'tarif_bulanan'];
}
