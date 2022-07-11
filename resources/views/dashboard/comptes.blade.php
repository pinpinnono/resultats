@extends('dashboard')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Comptes
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>Url API</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Désignation</th>
                            <th>Url API</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($comptes as $compte)
                            <tr>
                                <td><a href="{{ route('invest.show',$compte->id)}}">{{$compte->designation }}</a></td>
                                <td>{{ $compte->label }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Bar Chart Example
            </div>
            <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
        </div>
    </div> --}}
</div>

@stop
