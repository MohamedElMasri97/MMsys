<?php


use App\Models\Instrument;
use App\Models\Lims;


$inst = Instrument::find($id);
$lims = Lims::find(1);

exec('python ' . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->port . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
