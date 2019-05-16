@extends('layouts.main')
@section('content')
<h2>
  Historial de procesos<br>
  <small class="text-info"><a href="{{url('/')}}">Volver al inicio.</a></small>
</h2>
<hr>
<table id="procesos" class="table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>Proceso</th>
      <th>Fecha</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
@forelse($procesos as $proceso)
  <tr>
    <td>{{ $proceso->descripcion }}</td>
    <td>{{ $proceso->created_at }}</td>
    <td class="text-right">
      <a href="{{ url('getHistorial/'.$proceso->id) }}"><i class="fas fa-download"></i></a>
      &nbsp;
      <a href="{{ url('borrarProceso/'.$proceso->id) }}" class="text-danger"><i class="fas fa-trash-alt"></i></a>
    </td>
  </tr>
@empty
  <tr><td colspan="3">No se encontraron procesos.</td></tr>
@endforelse
  </tbody>
</table>
@stop