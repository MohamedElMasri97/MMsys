<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class result extends Model
{
    use HasFactory;

    public function Instrument()
    {
        return $this->belongsTo('App\Models\Instrument','instrument_id');
    }

    public function sample()
    {
        return $this->belongsTo('App\Models\Sample', 'sample_id');
    }
}
