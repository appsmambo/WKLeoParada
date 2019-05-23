@extends('layouts.main')
@section('content')
<div class="card hoverable">
	<div class="card-content">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget tincidunt libero. Curabitur scelerisque ante ultricies posuere accumsan. Donec at efficitur nisl, non posuere ante. Nam vitae malesuada justo.</p>
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
			<div class="card blue lighten-5">
				<div class="card-content">
					<p class="card-title">FICHA DE INFORMACIÓN DE MEDICIÓN DE RUIDO</p>
					<form>
						<p><i class="tiny material-icons">send</i> IDENTIFICACIÓN DE LA FUENTE EMISORA DE RUIDO</p>
						<div class="row">
							<div class="input-field col s12 m6">
								<input id="nombre" name="nombre" type="text" class="validate">
								<label for="nombre">Nombre o razón social</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="rut" name="rut" type="text" class="validate">
								<label for="rut">RUT</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="direccion" name="direccion" type="text" class="validate">
								<label for="direccion">Dirección</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="comuna" name="comuna" type="text" class="validate">
								<label for="comuna">Comuna</label>
							</div>
							<div class="input-field col s12">
								<input id="zona" name="zona" type="text" class="validate">
								<label for="zona">Nombre de Zona de emplazamiento (según IPT vigente)</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="datum" name="datum" type="text" class="validate">
								<label for="datum">Datum</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="huso" name="huso" type="text" class="validate">
								<label for="huso">Huso</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="coordenada-norte" name="coordenada-norte" type="text" class="validate">
								<label for="coordenada-norte">Coordenada Norte</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="coordenada-este" name="coordenada-este" type="text" class="validate">
								<label for="coordenada-este">Coordenada Este</label>
							</div>
						</div>
						<p><i class="tiny material-icons">send</i> CARACTERIZACIÓN DE LA FUENTE EMISORA DE RUIDO</p>
						<div class="row">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Productiva
							</div>
							<div class="col s12 l7 xl8">
								<div class="row">
									<div class="col s12 m6 l3">
										<label>
											<input type="checkbox" name="actividad-productiva" value="industrial" />
											<span>Industrial</span>
										</label>
									</div>
									<div class="col s12 m6 l3">
										<label>
											<input type="checkbox" name="actividad-productiva" value="agricola" />
											<span>Agrícola</span>
										</label>
									</div>
									<div class="col s12 m6 l3">
										<label>
											<input type="checkbox" name="actividad-productiva" value="extraccion" />
											<span>Extracción</span>
										</label>
									</div>
									<div class="col s12 m6 l3">
										<label>
											<input type="checkbox" name="actividad-productiva" value="otro" />
											<span>Otro</span>
										</label>
									</div>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Esparcimiento
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-esparcimiento" value="discoteca" />
										<span>Discoteca</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-esparcimiento" value="recinto-deportivo" />
										<span>Recinto Deportivo</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-esparcimiento" value="cultura" />
										<span>Cultura</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-esparcimiento" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
								</div>
						</div>
						<div class="row flex">
							<div class="grey lighten-1 white-text col s12 l5 xl4">
								Actividad Comercial
							</div>
							<div class="col s12 l7 xl8">
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="restaurant" />
										<span>Restaurant</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="taller-mecanico" />
										<span>Taller Mecánico</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="local-comercial" />
										<span>Local Comercial</span>
									</label>
								</div>
								<div class="col s12 m6 l3">
									<label>
										<input type="checkbox" name="actividad-comercial" value="otro" />
										<span>Otro</span>
									</label>
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