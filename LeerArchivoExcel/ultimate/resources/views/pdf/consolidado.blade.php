@extends('layouts.pdf')
@section('content')
<table id="procesos" class="table table-bordered" style="width:100%">
  <thead>
    <tr class="table-warning">
      <th>&nbsp;</th>
      <th>ÁREA DEPARTAMENTO</th>
      <th>GES Grupo de Exposición Similar</th>
      <th>Trabajador</th>
      <th>Fuentes de Ruido Incidentes (y estado)</th>
      <th>Ciclo de Trabajo o Tareas incluidas en medición</th>
      <th>Neq dBC</th>
      <th>PeakC</th>
      <th>Neq dBA Cada Ciclo</th>
      <th>Tiempo Medición (minutos)</th>
      <th>Tiempo Efectivo por jornada (horas)</th>
    </tr>
  </thead>
  <tbody>
<?php $fila = 1 ?>
<?php $procesos = Session::get('procesoDetalle'); ?>
@forelse($procesos as $proceso)
  @for ($i = 0; $i <= 5; $i++)
    @if ($i == 1)
    <tr class="table-success">
      <td>{{ $fila }}</td>
      <td>{{ $proceso->igr_area }}</td>
      <td>{{ $proceso->igr_ges }}</td>
      <td>{{ $proceso->igr_trabajador }}</td>
      <td>{{ $proceso->igr_fri }}</td>
      <td>{{ $proceso->igr_ciclott }}</td>
      <td>{{ $proceso->igr_neqdbc_1 }}</td>
      <td>{{ $proceso->igr_peakc_1 }}</td>
      <td>{{ $proceso->cic_neqdbc_1 }}</td>
      <td>{{ $proceso->cic_tm_1 }}</td>
      <td>{{ $proceso->cic_tej_1 }}</td>
    </tr>
    @elseif ($i == 2)
    @if ($proceso->igr_neqdbc_2 > 0 || $proceso->igr_peakc_2 > 0 || $proceso->cic_neqdbc_2 > 0 || $proceso->cic_tm_2 > 0 || $proceso->cic_tej_2 > 0)
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $proceso->igr_neqdbc_2 }}</td>
      <td>{{ $proceso->igr_peakc_2 }}</td>
      <td>{{ $proceso->cic_neqdbc_2 }}</td>
      <td>{{ $proceso->cic_tm_2 }}</td>
      <td>{{ $proceso->cic_tej_2 }}</td>
    </tr>
    @endif
    @elseif ($i == 3)
    @if ($proceso->igr_neqdbc_3 > 0 || $proceso->igr_peakc_3 > 0 || $proceso->cic_neqdbc_3 > 0 || $proceso->cic_tm_3 > 0 || $proceso->cic_tej_3 > 0)
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $proceso->igr_neqdbc_3 }}</td>
      <td>{{ $proceso->igr_peakc_3 }}</td>
      <td>{{ $proceso->cic_neqdbc_3 }}</td>
      <td>{{ $proceso->cic_tm_3 }}</td>
      <td>{{ $proceso->cic_tej_3 }}</td>
    </tr>
    @endif
    @elseif ($i == 4)
    @if ($proceso->igr_neqdbc_4 > 0 || $proceso->igr_peakc_4 > 0 || $proceso->cic_neqdbc_4 > 0 || $proceso->cic_tm_4 > 0 || $proceso->cic_tej_4 > 0)
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $proceso->igr_neqdbc_4 }}</td>
      <td>{{ $proceso->igr_peakc_4 }}</td>
      <td>{{ $proceso->cic_neqdbc_4 }}</td>
      <td>{{ $proceso->cic_tm_4 }}</td>
      <td>{{ $proceso->cic_tej_4 }}</td>
    </tr>
    @endif
    @elseif ($i == 5)
    @if ($proceso->igr_neqdbc_5 > 0 || $proceso->igr_peakc_5 > 0 || $proceso->cic_neqdbc_5 > 0 || $proceso->cic_tm_5 > 0 || $proceso->cic_tej_5 > 0)
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $proceso->igr_neqdbc_5 }}</td>
      <td>{{ $proceso->igr_peakc_5 }}</td>
      <td>{{ $proceso->cic_neqdbc_5 }}</td>
      <td>{{ $proceso->cic_tm_5 }}</td>
      <td>{{ $proceso->cic_tej_5 }}</td>
    </tr>
    @endif
    @endif
  @endfor
<?php $fila++ ?>
@empty
  <tr><td colspan="11">No se encontraron registros.</td></tr>
@endforelse
  </tbody>
</table>
@stop