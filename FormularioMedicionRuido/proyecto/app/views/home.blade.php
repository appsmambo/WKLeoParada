@extends('layouts.main')
@section('content')
<div class="card hoverable">
	<div class="card-content">
		<p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
	</div>
	<div class="card-tabs">
		<ul class="tabs tabs-fixed-width">
			<li class="tab"><a href="#paso1" class="active">Paso #1</a></li>
			<li class="tab"><a href="#paso2">Paso #2</a></li>
			<li class="tab"><a href="#paso3">Paso #3</a></li>
		</ul>
	</div>
	<div class="card-content grey lighten-4">
		<div id="paso1">
			<div class="card blue-grey darken-1">
				<div class="card-content white-text">
					<span class="card-title">FICHA DE INFORMACIÓN DE MEDICIÓN DE RUIDO</span>
					<p>IDENTIFICACIÓN DE LA FUENTE EMISORA DE RUIDO</p>
					<form>
						<div class="row">
							<div class="input-field col s6">
								<input id="nombre" name="nombre" type="text" class="validate">
								<label for="nombre">Nombre o razón social</label>
							</div>
							<div class="input-field col s6">
								<input id="rut" name="rut" type="text" class="validate">
								<label for="rut">RUT</label>
							</div>
						</div>
				</div>
				<div class="card-action">
					<a href="#">This is a link</a>
					<a href="#">This is a link</a>
				</div>
			</div>
		</div>
		<div id="paso2">Test 2</div>
		<div id="paso3">Test 3</div>
	</div>
</div>
<div class="row">
	<div class="col s1">1</div>
</div>
@stop