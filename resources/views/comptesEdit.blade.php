@extends('layouts.default')
@section('breadcrumb')
<li class="breadcrumb-item active">Comptes</li><li class="breadcrumb-item active">edit</li>
@stop
@section('content')
<form action="{{ route('comptes.update') }}" method="post">
    @csrf
    <input class="form-control" id="id" type="hidden" value="{{$comptes->id}}" name="id"/>
    <div class="form-floating mb-3">
        <input class="form-control" id="designation" type="text" value="{{$comptes->designation}}" name="designation" />
        <label for="designation">Désignation</label>
    </div>
    <div class="form-floating mb-3">
        <select class="form-select" id="label" aria-label="Floating label select example" name="label">
            @foreach ($options as $option =>$val)
                <option 
                @if ($option == $comptes->label ) 
                    selected="selected" 
                @endif value="{{$option}}">{{$val}}</option>
            @endforeach
          </select>
        <label for="label">Type</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" id="login" type="text" value="{{$comptes->login}}"  name="login"/>
        <label for="login">Login</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" id="pass" type="password" value="{{$comptes->pass}}"  name="pass"/>
        <label for="pass">Password</label>
    </div>
    <input type="submit" name="send" value="Editer" class="btn btn-dark btn-block">
</form>
@stop
