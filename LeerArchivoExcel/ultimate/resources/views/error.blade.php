@extends('layouts.main')
@section('content')
<h2>
  Proceso: {{ $proceso }}<br>
  <small class="text-danger">Se produjo un error: {{ $mensaje }}</small>
</h2>
<h3>
  <small class="text-info"><a href="{{url('/')}}">Vuelva a subir el archivo.</a></small>
</h3>
@stop