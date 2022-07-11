@extends('layouts.default')
@section('breadcrumb')
<li class="breadcrumb-item active">Invests</li><li class="breadcrumb-item active">Daily</li>
@stop
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Historique
                <a href="{{route('invest.exportDaily',["id"=>$id,"id_invest"=>$id_invest])}}"><button type="button" class="btn btn-outline-success float-end">Export</button></a>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            @foreach ($days[0] as $data =>$val)
                                <th>{{ $data}}</th>
                            @endforeach
                        <tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @foreach ($days[0] as $data =>$val)
                                <th>{{ $data}}</th>
                            @endforeach
                        <tr>
                    </tfoot>
                    <tbody>
                        @foreach ($days as $invest)
                            <tr>
                            @foreach ($invest as $data =>$val) 
                                @if($data == "date")
                                    <td>{!! Str::limit($val, 10,'') !!}</td>
                                @else 
                                    <td>{{ $val}}</td>
                                @endif
                            @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')

@stop
