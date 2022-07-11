@extends('layouts.default')
@section('breadcrumb')
<li class="breadcrumb-item active">Comptes</li>
@stop
@section('content')
<div class="card-body">
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Url API</th>
                <th>Edit</th>
                <th>delete</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Désignation</th>
                <th>Url API</th>
                <th>edit</th>
                <th>delete</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($comptes as $compte)
                <tr>
                    <td>{{ $compte->designation }}</td>
                    <td>{{ $compte->label }}</td>
                    <td><a class="btn btn-primary" href="{{ route('comptes.edit', ['id'=>$compte->id]) }}">Edit</a></td>
                    <td><a class="btn btn-danger" onclick="return confirm('Etes vous certain de vouloir supprimer ?')" href="{{ route('comptes.delete', ['id'=>$compte->id]) }}">Delete</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
