<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    public $fillable = [
        'name','refinstrument_id','serialport','netport','ip'
    ];

    public function instrumentsMessage()
    {
        return $this->hasMany('App\Models\InstrumentsMessage');
    }

    public function refinstrument()
    {
        return $this->belongsTo('App\Models\Refinstrument');
    }

    public function samples()
    {
        return $this->hasMany('App\Models\result');
    }
}
