<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    public function instrumentsMessages()
    {
        return $this->hasMany('App\Models\InstrumentsMessage');
    }


    public function samples()
    {
        return $this->hasMany('App\Models\Sample');
    }
}
