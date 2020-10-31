@extends('layouts.dashboard')

@section('title')
    New Instrument
@endsection

@section('content')
    <div class="container" id="APP1">
        <div class="row">
            <div class="col-md-6 col-sm-12 mx-auto">
                <div class="border rounded px-2 mt-4 shadow m-1">
                    <form action="{{route('postnewInst')}}" method="POST">
                        @csrf
                        <h4 class="p-1">Create New Instrument</h4>

                        @if(session()->has('errors'))
                        <div class="alert alert-danger">
                            @foreach (session()->get('errors') as $error)
                            <span>{{$error}}</span>
                            @endforeach
                        </div>
                        @endif


                        <hr>

                        <div class="pl-2 form-group">
                            <label for="Name" class="">Instrument Name</label>
                            <input type="text" class="form-control" name="name"  required id="name">
                        </div>
                        <div class="pl-2 form-group">
                            <label for="type">Instrument Type</label>
                            <select name="type" class="form-control"  id="type" required>
                                @foreach ($RefInstruments as $refInst)
                                    <option value="{{$refInst->id}} {{$refInst->commtype}}">{{$refInst->name}} - {{$refInst->protocol}}</option>
                                @endforeach
                                <option value="" id="emptyoption" selected></option>
                            </select>
                        </div>
                        <div id="NET" class="pl-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" value="autoip" name="autoip" disabled class="custom-control-input" id="autoip">
                                <label class="custom-control-label" for="autoip">Auto IP</label>
                            </div>
                            <div  class="form-group">
                                <label for="ip">Instrument IP</label>
                                <input type="text" class="form-control" name="ip" id="ip" disabled>
                            </div>
                            <div class="form-group">
                                <label for="netport">Instrument port</label>
                                <input type="text" class="form-control" name="netport" id="netport" disabled>
                            </div>
                        </div>
                        <div id="SER">
                            <div  class="pl-2 form-group">
                                <label for="serialport">Instrument serial port COM-</label>
                                <select class="form-control" name="serialport" id="serialport" disabled>
                                    @for ($i = 1; $i < 11; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="clearfix" id="submit">
                            <input type="submit" class="btn pull-right btn-success m-2">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('/js/newinst.js')}}" type="text/javascript"></script>
@endsection
