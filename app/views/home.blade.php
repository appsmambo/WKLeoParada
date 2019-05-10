@extends('layouts.main')
@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="subir-tab" data-toggle="tab" href="#subir" role="tab" aria-controls="subir" aria-selected="true">Subir</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="historial-tab" data-toggle="tab" href="#historial" role="tab" aria-controls="historial" aria-selected="false">Historial</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active p-3" id="subir" role="tabpanel" aria-labelledby="subir-tab">
    <form method="post" action="{{ url('proceso') }}" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="form-group">
        <label for="archivo">* Seleccione el archivo excel:</label>
        <input type="file" class="form-control-file" name="archivo" id="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
      </div>
      <div class="form-group">
        <label for="proceso">Identificador de proceso:</label>
        <input type="text" class="form-control" name="proceso" id="proceso">
      </div>
      <button type="submit" class="btn btn-primary">Ejecutar</button>
    </form>
  </div>
  <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab">
  </div>
</div>
@stop