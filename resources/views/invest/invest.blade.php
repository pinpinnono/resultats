@extends('layouts.default')
@section('breadcrumb')
<li class="breadcrumb-item active">Invests</li>
@stop
@section('content')
<div class="row">
    @foreach ($invests as $invest)
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                @foreach (json_decode($invest->data) as $data =>$val)
                    @if($data == "id")
                        @php
                            $id = $val;
                        @endphp
                    @endif
                    @if($data == "name")
                        <u>{{$val}}</u>
                        <a href="{{route('invest.daily',["id"=>$id,"id_invest"=>$id_invest])}}"><button type="button" class="btn btn-outline-secondary float-end">Daily</button></a>
                        <a href="{{route('invest.history',["id"=>$id,"id_invest"=>$id_invest])}}"><button type="button" class="btn btn-outline-secondary float-end">Historique</button></a>
                    @endif
                @endforeach
            </div>
            <div class="card-body">
                
                    <table class="table table-striped">
                        <tbody>
                            @foreach (json_decode($invest->data) as $data =>$val)
                                <tr>
                                    @if($data == "server")
                                        <td>{{$data}}</td><td>{{$val->name}}</td>
                                    @else 
                                        <td>{{$data}}</td><td>{{ $val}}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                
            </div>
        </div>
    </div>
    @endforeach
</div>

@stop
