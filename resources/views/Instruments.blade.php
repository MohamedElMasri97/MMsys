@extends('layouts.master')

@section('title')
    Instruments
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('css/inst.css')}}">
@endsection

@section('content')
    <div class="container mx-auto ">
        <div class="row p-3">
            <div class="col-md-3 p-1">
                @foreach ($instruments as $inst)
                    <a href="{{route('instrumentDetails',['id'=>$inst->id])}}" style="text-decoration:none" class="row border rounded p-1 mt-2 m-1
                        {{$inst->status =='on' ? 'instbubbleon' : 'instbubbleoff' }} instbubble shadow  mr-auto">
                        <div class="container">
                            <div class="row">
                                <div class="col-12" style="color: #eeee;text-align:center;">{{$inst->name}}</div>
                                <div class="col-5" style="color: #cccc;text-align:center;font-size:0.6em;">{{$inst->Refinstrument->name}}</div>
                                <div class="col-1" style="color: #cccc;text-align:center;font-size:0.6em;">-</div>
                                <div class="col-5" style="color: #cccc;text-align:center;font-size:0.6em;">{{$inst->Refinstrument->company}}</div>
                            </div>
                            <hr  class="m-0">
                            <div class="row">
                                @if ($inst->Refinstrument->commtype == 'NET')
                                    <div class="col-4" style="color: #cccc;font-size:0.6em;">{{$inst->netport}}</div>
                                    <div class="col-4" style="color: #cccc;font-size:0.6em;">{{$inst->ip}}</div>
                                    <div class="col-4" style="color: #cccc;font-size:0.6em;">{{$inst->status}}</div>
                                @else
                                    <div class="col-5" style="color: #cccc;font-size:0.6em;">{{$inst->serialport}}</div>
                                    <div class="col-1" style="color: #cccc;font-size:0.6em;">-</div>
                                    <div class="col-5" style="color: #cccc;font-size:0.6em;">{{$inst->status}}</div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
                <a href="{{route('newInst')}}" style="text-decoration: none" class="row border rounded p-1 mt-2 m-1 addinst shadow mr-auto">
                    <div class="container">
                        <div class="row">
                            <div class="col-12" style="color: rgba(255, 255, 255, 0.748); text-align:center;">+</div>
                        </div>
                    </div>
                </a>
                <p  style="text-decoration: none"
                    class="row  p-1 mt-2 m-1 mr-auto">
                    <div class="container">
                        <div class="row">
                            <div class="col-12" style="color: rgba(0, 0, 0, 0.205); text-align:center; font-size:0.7em">In order to create new instrumetn please follow the link on the blue button above, and then fill the form</div>
                        </div>
                    </div>
                </p>
            </div>
            <div class="col-md-9 p-1">
                <div class="row">
                    <div class="container mx-auto">
                        <div class="row mx-auto">
                            <div class=" col-md-6 col-sm-12 mx-auto p-4 m-2 mt-4" style="text-align:center;">
                                <i class="fas fa-mail-bulk " style="color: rgb(0,0,0,0.1);font-size:5em;"></i>
                                <p class="" style="color: rgba(160, 144, 144, 0.527);font-size:2em;">
                                    Medical Instruments
                                </p>
                                <p style="color: rgba(160, 144, 144, 0.527);font-size:0.7em;">
                                    The instruments shown in the list are the one defined by the system administrator.
                                    Each instrument is linked to a referance instrument that defines a statis parameters.
                                    These parameters are defined by the primary communication script and manufacturer of the instrument.
                                    When creating an instrument you will define the name, type of the instrument, and accordingly you will define the connection parameters.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
