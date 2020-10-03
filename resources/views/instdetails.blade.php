@extends('layouts.master')

@section('title')
    Details
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('css/inst.css')}}">
@endsection

@section('content')
    <div class="container ">
        <div class="row  p-2">
            <div class="col-md-2 col-sm-12 p-2" style="text-align:center;">
                <img src="{{asset($inst->Refinstrument->imagepath)}}" class="border rounded image shadow" alt="dyming" style="width: 150px;height:175px; opacity:0.9;">
            </div>
            <div class="col-md-10 col-sm-12 border rounded information p-2">
                <div class="container">
                    <div class="row">
                        <div class="col-4 infofont p-2">{{$inst->name}}</div>
                        <div class="col-4 infofont p-2">{{$inst->Refinstrument->company}}</div>
                        <div class="col-4 infofont p-2">{{$inst->Refinstrument->name}}</div>
                    </div>
                    <div class="row">
                        <div class=" col-12 infofont xxsmall p-2">
                            {{$inst->Refinstrument->information}}
                        </div>
                    </div>
                    @if ($inst->Refinstrument->commtype == 'NET')
                        <div class="row">
                            <div class="col-1 infofont p-2">{{$inst->Refinstrument->commtype}}</div>
                            <div class="col-1 infofont p-2">{{$inst->Refinstrument->protocol}}</div>
                            <div class="col-2 infofont p-2">{{$inst->ip}}</div>
                            <div class="col-2 infofont p-2">:{{$inst->netport}}</div>
                            <div class="col-2 infofont p-2">{{$inst->pid? $inst->pid:'0'}}</div>
                            <div class="col-1 infofont ml-auto p-2 pr-4">
                                <button class="btn btn-sm white {{$inst->status=='on'?'instbubbleon':'instbubbleoff'}} ">{{$inst->status}}</button>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-2 infofont p-2">{{$inst->Refinstrument->commtype}}</div>
                            <div class="col-2 infofont p-2">{{$inst->Refinstrument->protocol}}</div>
                            <div class="col-2 infofont p-2">:{{$inst->serialport}}</div>
                            <div class="col-2 infofont p-2">{{$inst->pid? $inst->pid:'0'}}</div>
                            <div class="col-2 infofont p-2">
                                <a href="#" class="btn-sm white {{$inst->status=='on'?'instbubbleon':'instbubbleoff'}}">{{$inst->status}}</a>
                            </div>
                        </div>
                    @endif
                    <form action="{{route('flipconnection',['id'=>$inst->id])}}" id="flipform" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </div>
        <div class="row p-2 {{count($messages)>0 ?'':'hidden'}}" >
            <div class="col-12">
                <div class="border roounde border-success bg-dark text-light p-2">
                    <div class="container">
                        @foreach ($messages as $message)
                            <div class="clearfix">
                                <p class="float-left p-0 m-0" style="color: rgba(163, 235, 198, 0.666);font-size:0.6em;">{{$message->message}}</p>
                                <p class="float-right p-0 m-0" style="color: rgba(206, 206, 206, 0.398);font-size:0.6em;">{{$message->created_at->format('Y.m.d H:i:s')}}</p>
                            </div>
                            <hr class="m-0 p-0">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/inst.js')}}" type="text/javascript"></script>
@endsection
