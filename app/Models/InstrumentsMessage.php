<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentsMessage extends Model
{
    use HasFactory;


    public function Instrument()
    {
        return $this->belongsTo('App\Models\Instrument');
    }
}
