<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Consolidado</title>
    <script>
      const APP_URL = {!! json_encode(url('/')) !!}
    </script>
  </head>
  <body>
    <section class="container-fluid py-3">
      <div class="card">
        <div class="card-header">
          <a href="{{ url('/') }}">Inicio</a> > Lista consolidada
          <a href="#" id="reiniciarSistema" class="float-right btn btn-danger ml-1">Limpiar datos <i class="fas fa-recycle 2x"></i></a>
          <a href="{{ url('descargarConsolidado') }}" class="float-right btn btn-primary ml-1">Descargar excel <i class="fas fa-file-excel 2x"></i></a>
          <a href="{{ url('descargarConsolidadoPDF') }}" class="float-right btn btn-primary ml-1">Descargar pdf <i class="fas fa-file-pdf 3x"></i></a>
        </div>
        <div class="card-body">
          @yield('content')
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>
      var url = APP_URL + '/reiniciar'
      $(function() {
        $('#reiniciarSistema').click(function() {
          var respuesta = confirm('Este proceso borrará todos los datos, desea continuar?')
          if (respuesta)
            window.location.href = url
          else
            return
        })
      })
    </script>
  </body>
</html>