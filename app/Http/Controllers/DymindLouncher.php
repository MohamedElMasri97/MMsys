<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Instrument;
    use App\Models\InstrumentsMessage;
    use App\Models\Refinstrument;
    use Illuminate\Support\Facades\DB;
    use PhpParser\Node\Expr\ErrorSuppress;
    use App\Models\Lims;


class DymindLouncher extends Controller
{
    public function run($id){
        $inst = Instrument::find($id);
        $lims = Lims::find(1);
        // echo('python ' . base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->netport . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
        exec('python '. base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->neetport . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
    }
}
