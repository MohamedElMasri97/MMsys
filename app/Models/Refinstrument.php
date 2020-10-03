<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refinstrument extends Model
{
    use HasFactory;

    public $fillable = ['name','protocol','commtype','pythonpath','imagepath','bitlength','parity','stopbit', 'boudrate','company'];
    public $guarded = [];
    public function instruments(){
        return $this->hasMany('App\Models\Instrument');
    }
}
