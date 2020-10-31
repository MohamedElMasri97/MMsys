<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class result extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function Instrument()
    {
        return $this->belongsTo('App\Models\Instrument','instrument_id');
    }

    public function sample()
    {
        return $this->belongsTo('App\Models\Sample', 'sample_id');
    }
}
