<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Models\InstrumentsMessage;
use App\Models\Refinstrument;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\ErrorSuppress;
use App\Models\Lims;

use function GuzzleHttp\Promise\queue;

class InstrumentController extends Controller
{
    // returnst the list of available instrument
    public function index(Request $request)
    {
        $instruments = Instrument::all();
        return view('Instruments', [
            'instruments' => $instruments,
        ]);
    }

    // returns the view for creating new inst
    public function viewNewInstrument()
    {
        $refInstruments = RefInstrument::all();
        return view('newInst', [
            'RefInstruments' => $refInstruments,
        ]);
    }

    // validation for the port number
    public function isPort($s){
        if(is_numeric($s)){
            if((int)$s <1 or (int)$s> 65536){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    //v validate and save the new instrumetn data
    public function newinst(Request $request){
        $errors = [];
        if ($request->has('name')){
            if(!strlen($request->input('name'))>=3){
                $errors[] = 'name should be longer then 3 letters';
            } else if (count(Instrument::where('name', '=', $request->name)->get()) != 0){
                $errors[] = 'name should be unique value this value already exists';
            }
        }else{
            $errors[] = 'name is required';
        }
        if(Refinstrument::find(explode(' ', $request->input('type'))[0])){
            if (explode(' ', $request->input('type'))[1] == 'NET') {
                if(!$request->has('autoip')){
                    if (ip2long($request->ip) == false) {
                        $errors[] = 'IP is not valid';
                    }
                }
                if (!$this->isPort($request->netport)) {
                    $error[] = 'port number is not valid';
                }
            } else if(explode(' ', $request->input('type'))[1] == 'SER'){
                if(!is_numeric($request->serialport)){
                    if($request->serialport<1 or $request->serialport > 11){
                        $errors[] = 'wrong comport number';
                    }
                }
            }else{
                $errors[] = 'wrong commtype';
            }
        }else{
            $errors[] = 'the instruement should be one of the already given on the list';
        }
        if(count($errors) == 0){
            $inst = new Instrument();
            $inst->name = $request->name;
            if(explode(' ', $request->type)[1] == "NET"){
                if($request->has('autoip')){
                    $inst->ip= 'auto';
                }else{
                    $inst->ip = $request->ip;
                }
                $inst->netport = $request->netport;
            }else{
                $inst->serialport = $request->serialport;
            }
            $inst->refinstrument_id = explode(' ',$request->type)[0];
            $inst->save();
            return redirect()->route('Instrumentsview');
        }else{
            return redirect()->back()->withInput()->with('errors',$errors);
        }
    }

    // return the view of the instrument details and control
    public function instdetails($id){
        $inst = Instrument::find($id);
        $messages = $inst->InstrumentsMessage()->orderBy('created_at', 'DESC')->limit(100)->select('message','created_at')->get();
        return view('instdetails',['inst'=>$inst,'messages'=>$messages]);
    }

    // first kills the running script using given pid if exists
    // and then check if the status is on or not, if on then
    // just make the status off, if any other state then tries to
    // start a new script
    public function flipconnection($id){
        $inst = Instrument::find($id);
        if($inst)
            {
                if($inst->pid !=0){
                    $x = exec('taskkill /PID ' . $inst->pid . ' /F');
                    $inst->pid = 0;
                    $inst->save();
                }
                if($inst->status!='on'){
                    dispatch(function( ) use ($inst,$id){
                        $inst->status = 'on';
                        $inst->save();
                        Artisan::call($inst->Refinstrument->command, ['id' => $id]);
                    });
                }else{
                    $inst->status = 'OFF';
                    $inst->save();
                }
                return redirect()->route('instrumentDetails',['id'=>$id]);
            }
    }

    // returns the status of a given instrument
    public function status($id)
    {

        $inst = Instrument::find($id);
        if ($inst) {
            return ['status'=>$inst->status];
        }
        return '';
    }

    // storing new messages coming from the python script
    public function show(Request $request)
    {
        if($request->has('message'))
        {
            if($request->has('id')){
                $inst = Instrument::find($request->id);
                $message = new InstrumentsMessage();
                $message->message = $request->message;
                $inst->InstrumentsMessage()->save($message);
                return ['status' => $inst->status];
            }
        }
        return ['error'=>'ERR'];
    }

    // returns the first hundres messages of the instrument coming from the script
    public function getmessages($id)
    {
        if ($id) {
            $inst = Instrument::find($id);
            return $inst->InstrumentsMessage()->orderBy('created_at', 'DESC')->limit(100)->select('message','created_at')->get();
        }
        return ['error' => 'ERR'];
    }

    // updates teh status of the instruement based on the request of an insturment
    public function updatestatus(Request $request)
    {
        if ($request->has('status')) {
            if ($request->has('id')) {
                $inst = Instrument::find($request->id);
                $inst->status = $request->status;
                $inst->save();
                return ['status'=>$inst->status];
            }
        }
        return ['error' => 'ERR'];
    }

    // updates teh status of the instruement based on the request of an insturment
    public function pid(Request $request)
    {
        if ($request->has('pid')) {
            if ($request->has('id')) {
                $inst = Instrument::find($request->id);
                $inst->pid = $request->pid;
                $inst->save();
                return ['status' => $inst->status];
            }
        }
        return ['error' => 'ERR'];
    }
}
