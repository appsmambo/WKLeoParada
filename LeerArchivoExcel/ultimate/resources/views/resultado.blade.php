@extends('layouts.main')
@section('content')
<h2>
  Proceso: {{ $proceso }}<br>
  <small>Fueron procesados {{ $filas }} filas</small>
</h2>
<h3>
  <small class="text-info"><a href="{{url('/')}}">Volver al inicio.</a></small>
</h3>
@stop