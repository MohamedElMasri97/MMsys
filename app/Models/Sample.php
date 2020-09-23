<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    public function results(){
        return $this->hasMany('App\Models\result');
    }


    public function Instrument()
    {
        return $this->belongsTo('App\Models\Instrument');
    }
}
