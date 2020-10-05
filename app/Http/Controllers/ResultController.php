<?php

namespace App\Http\Controllers;
use App\Models\Instrument;
use App\Models\Refinstrument;
use App\Models\Sample;
use App\Models\result;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNan;

class ResultController extends Controller
{
    public function resultset(Request $request){
        if($request->has('sample')  and $request->has('id')){
            if(is_array($request['sample'])){
                $inst = Instrument::find($request['id']);
                if(!$inst){
                    return false;
                }
                if(Sample::where('barcode','=', $request['sample']['id'])->get()->count() == '0'){
                    $sample = new Sample();
                    $sample->barcode = $request['sample']['id'];
                    $sample->save();
                }else{
                    $sample = Sample::where('barcode', '=', $request['sample']['id'])->get()[0];
                }
                foreach ($request['sample']['result'] as $code => $value) {
                    if (is_numeric($value)) {
                        $result = new result();
                        $result->instrument_id = $request['id'];
                        $result->result = $value;
                        $result->testcode = $code;
                        $sample->results()->save($result);
                    }
                }
                $status = $inst['status'];
                return ['status'=>$status];
            }
        }
    }
}
