<?php

class HomeController extends BaseController {

  public function getHome()
  {
    $proceso = Proceso::take(10)->orderBy('created_at', 'desc')->get();
    return View::make('home')->with('procesos', $proceso);
  }

  public function postProceso() {
    $fechaHoy = date('d/m/Y H:i:s');
    if (!Input::hasFile('archivo')) {
      exit('No autorizado!');
    }
    try {
      $public_path = public_path();
      $carpeta = 'procesos';
      $extension = Input::file('archivo')->getClientOriginalExtension();
      $archivo = time() . '.' . $extension;
      $archivo = Input::file('archivo')->move($carpeta, $archivo);
      $archivoRuta = $public_path . '/' . $archivo;
      $procesoDescripcion = Input::get('proceso', $fechaHoy);
      $filaProceso = [];
      // recorrer las filas de la hoja INGRESO
      Excel::selectSheets('INGRESO')->load($archivoRuta, function($reader) use(&$filaProceso) {
        $hayDatos = false;
        $fila = 0;
        $contador = 1;
        $reader->noHeading();
        $rows = $reader->get();
        foreach($rows as $key => $row) { // recorrer las filas
          $primeraCelda = trim($row[0]);
          if ($primeraCelda != '' && $fila == 0) $hayDatos = true;
          if ($hayDatos) {
            if ($contador == 1) {
              $filaProceso[$fila]['igr_area'] = trim($row[1]);
              $filaProceso[$fila]['igr_ges'] = trim($row[2]);
              $filaProceso[$fila]['igr_trabajador'] = trim($row[5]);
              $filaProceso[$fila]['igr_fri'] = trim($row[5]);
              $filaProceso[$fila]['igr_ciclott'] = trim($row[19]);
              $filaProceso[$fila]['igr_neqdbc'] = trim($row[21]);
              $filaProceso[$fila]['igr_peakc'] = trim($row[22]);
            } else {
              if (trim($row[5]) != '')
                $filaProceso[$fila]['igr_fri'] .= ' | ' . trim($row[5]);
              if (trim($row[19]) != '')
                $filaProceso[$fila]['igr_ciclott'] .= ' | ' . trim($row[19]);
              if (trim($row[21]) != '')
                $filaProceso[$fila]['igr_neqdbc'] .= '|' . trim($row[21]);
              if (trim($row[22]) != '')
                $filaProceso[$fila]['igr_peakc'] .= '|' . trim($row[22]);
            }
            $contador++;
            if ($contador > 5) {
              $contador = 1;
              $fila++;
            }
          }
        }
      });
      // recorrer las filas de la hoja CICLOS
      Excel::selectSheets('CICLOS')->load($archivoRuta, function($reader) use(&$filaProceso) {
        $cuenta = 0;
        $fila = 0;
        $contador = 1;
        $reader->noHeading();
        $rows = $reader->get();
        $totalFilas = count($filaProceso);
        foreach($rows as $key => $row) { // recorrer las filas
          $primeraCelda = trim($row[0]);
          if ($primeraCelda != '') {
            $cuenta++;
          }
          if ($cuenta >= 2) {
            if ($contador == 1) {
              $filaProceso[$fila]['cic_neqdbc'] = trim($row[4]);
              $filaProceso[$fila]['cic_tm'] = trim($row[5]);
              $filaProceso[$fila]['cic_tej'] = trim($row[6]);
            } else {
              if (trim($row[4]) != '')
                $filaProceso[$fila]['cic_neqdbc'] .= ' | ' . trim($row[4]);
              if (trim($row[5]) != '')
                $filaProceso[$fila]['cic_tm'] .= ' | ' . trim($row[5]);
              if (trim($row[6]) != '')
                $filaProceso[$fila]['cic_tej'] .= '|' . trim($row[6]);
            }
            $contador++;
            if ($contador > 5) {
              $contador = 1;
              $fila++;
            }
            if ($fila > $totalFilas) break;
          }
        }
      });
      // grabar el proceso
      $proceso = new Proceso;
      $proceso->descripcion = $procesoDescripcion;
      $proceso->archivo = $archivo;
      $proceso->estado = 1;
      $proceso->save();
      $procesoId = $proceso->id;
      // grabar detalle de proceso
      foreach($filaProceso as $detalle) {
        $procesoDetalle = new ProcesoDetalle;
        $procesoDetalle->id_proceso = $procesoId;
        $procesoDetalle->igr_area = $detalle['igr_area'];
        $procesoDetalle->igr_ges = $detalle['igr_ges'];
        $procesoDetalle->igr_trabajador = $detalle['igr_trabajador'];
        $procesoDetalle->igr_fri = $detalle['igr_fri'];
        $procesoDetalle->igr_ciclott = $detalle['igr_ciclott'];
        $igr_neqdbc = explode('|', $detalle['igr_neqdbc']);
        $procesoDetalle->igr_neqdbc_1 = $igr_neqdbc[0];
        $procesoDetalle->igr_neqdbc_2 = isset($igr_neqdbc[1]) ? $igr_neqdbc[1] : 0;
        $procesoDetalle->igr_neqdbc_3 = isset($igr_neqdbc[2]) ? $igr_neqdbc[2] : 0;
        $procesoDetalle->igr_neqdbc_4 = isset($igr_neqdbc[3]) ? $igr_neqdbc[3] : 0;
        $procesoDetalle->igr_neqdbc_5 = isset($igr_neqdbc[4]) ? $igr_neqdbc[4] : 0;
        $igr_peakc = explode('|', $detalle['igr_peakc']);
        $procesoDetalle->igr_peakc_1 = $igr_peakc[0];
        $procesoDetalle->igr_peakc_2 = isset($igr_peakc[1]) ? $igr_peakc[1] : 0;
        $procesoDetalle->igr_peakc_3 = isset($igr_peakc[2]) ? $igr_peakc[2] : 0;
        $procesoDetalle->igr_peakc_4 = isset($igr_peakc[3]) ? $igr_peakc[3] : 0;
        $procesoDetalle->igr_peakc_5 = isset($igr_peakc[4]) ? $igr_peakc[4] : 0;
        $cic_neqdbc = explode('|', $detalle['cic_neqdbc']);
        $procesoDetalle->cic_neqdbc_1 = $cic_neqdbc[0];
        $procesoDetalle->cic_neqdbc_2 = isset($cic_neqdbc[1]) ? $cic_neqdbc[1] : 0;
        $procesoDetalle->cic_neqdbc_3 = isset($cic_neqdbc[2]) ? $cic_neqdbc[2] : 0;
        $procesoDetalle->cic_neqdbc_4 = isset($cic_neqdbc[3]) ? $cic_neqdbc[3] : 0;
        $procesoDetalle->cic_neqdbc_5 = isset($cic_neqdbc[4]) ? $cic_neqdbc[4] : 0;
        $cic_tm = explode('|', $detalle['cic_tm']);
        $procesoDetalle->cic_tm_1 = $cic_tm[0];
        $procesoDetalle->cic_tm_2 = isset($cic_tm[1]) ? $cic_tm[1] : 0;
        $procesoDetalle->cic_tm_3 = isset($cic_tm[2]) ? $cic_tm[2] : 0;
        $procesoDetalle->cic_tm_4 = isset($cic_tm[3]) ? $cic_tm[3] : 0;
        $procesoDetalle->cic_tm_5 = isset($cic_tm[4]) ? $cic_tm[4] : 0;
        $cic_tej = explode('|', $detalle['cic_tej']);
        $procesoDetalle->cic_tej_1 = $cic_tej[0];
        $procesoDetalle->cic_tej_2 = isset($cic_tej[1]) ? $cic_tej[1] : 0;
        $procesoDetalle->cic_tej_3 = isset($cic_tej[2]) ? $cic_tej[2] : 0;
        $procesoDetalle->cic_tej_4 = isset($cic_tej[3]) ? $cic_tej[3] : 0;
        $procesoDetalle->cic_tej_5 = isset($cic_tej[4]) ? $cic_tej[4] : 0;
        $procesoDetalle->save();
      }
      // cargar vista con resultados: $filas por ejemplo
      $filas = count($filaProceso);
      return View::make('resultados')->with('filas', $filas)->with('proceso', $procesoDescripcion);
    } catch (Exception $e) {
      $procesoDescripcion = Input::get('proceso', $fechaHoy);
      $mensaje = $e->getMessage();
      return View::make('error')->with('mensaje', $mensaje)->with('proceso', $procesoDescripcion);
    }
  }

  public function getHistorial() {
    $proceso = Proceso::take(10)->orderBy('created_at', 'desc')->get();
    return View::make('historial')->with('procesos', $proceso);
  }

  public function getHistorialById($idProceso) {
    $proceso = Proceso::find($idProceso);
    $archivo = $proceso->descripcion == '' ? 'archivo' : $proceso->descripcion;
    $procesoDetalle = ProcesoDetalle::where('id_proceso', $idProceso)->select('igr_area', 'igr_ges', 'igr_trabajador', 'igr_fri', 'igr_ciclott', 'igr_neqdbc_1', 'igr_neqdbc_2', 'igr_neqdbc_3', 'igr_neqdbc_4', 'igr_neqdbc_5', 'igr_peakc_1', 'igr_peakc_2', 'igr_peakc_3', 'igr_peakc_4', 'igr_peakc_5', 'cic_neqdbc_1', 'cic_neqdbc_2', 'cic_neqdbc_3', 'cic_neqdbc_4', 'cic_neqdbc_5', 'cic_tm_1', 'cic_tm_2', 'cic_tm_3', 'cic_tm_4', 'cic_tm_5', 'cic_tej_1', 'cic_tej_2', 'cic_tej_3', 'cic_tej_4', 'cic_tej_5')->get();
    Excel::create($archivo, function($excel) use($procesoDetalle) {
      $excel->sheet('datos', function($sheet) use($procesoDetalle) {
        $sheet->fromArray($procesoDetalle->toArray());
      });
    })->download('xlsx');
  }

  public function getOnline() {
    $procesoDetalle = ProcesoDetalle::orderBy('created_at', 'DESC')->get();
    return View::make('consolidado')->with('procesos', $procesoDetalle);
  }

}