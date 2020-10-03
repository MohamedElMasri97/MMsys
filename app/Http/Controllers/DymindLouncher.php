<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Instrument;
    use App\Models\InstrumentsMessage;
    use App\Models\Refinstrument;
    use Illuminate\Support\Facades\DB;
    use PhpParser\Node\Expr\ErrorSuppress;
    use App\Models\Lims;


class InstrumentController extends Controller
{
    public function run($id){
        $inst = Instrument::find($id);
        $lims = Lims::find(1);
        echo('python ' . base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->port . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
        exec('python '. base_path('public\\') . $inst->Refinstrument->pythonpath . ' ' . $inst->ip . ' ' . $inst->port . ' ' . asset('api') . ' ' . $inst->id . ' ' . $lims->apigetter . '');
    }
}

$id = $argv[1];
$runner  = new  InstrumentController();
$runner->run($id);
