<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proceso;
use App\ProcesoDetalle;
use App\Imports\ProcesosImport;
use App\Exports\ProcesosExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class HomeController extends Controller
{
  public function getHome()
  // carga el archivo de vista home 
  // se envía la relación de procesos (uploads)
  {
    $proceso = Proceso::take(10)->orderBy('created_at', 'desc')->get();
    return View('home')->with('procesos', $proceso);
  }

  public function postProceso(Request $request)
  // función para los uploads
  // se recibe los datos por POST
  {
    // verificar si el archivo está presente
    if (!$request->hasFile('archivo')) {
      exit('No autorizado!');
    }
    $fechaHoy = date('d/m/Y H:i:s');
    try {
      // obtener el archivo para procesarlo
      $storage_path = storage_path();
      $carpeta = 'procesos';
      $extension = $request->archivo->extension();
      $archivo = time() . '.' . $extension;
      $archivoRuta = $storage_path . '/app/' . $request->archivo->storeAs($carpeta, $archivo);
      $procesoDescripcion = $request->input('proceso', $fechaHoy);
      // variable tipo array para almacenar la data del archivo y su posterior manejo en la base de datos
      $filaProceso = [];

      // obtener todas las filas del archivo
      $arrProceso = Excel::toArray(new ProcesosImport, $archivoRuta);
      // separar las hojas
      $arrIngreso = $arrProceso[0];
      $arrCiclos = $arrProceso[1];

      // iniciar variables
      // flag para detectar presencia de datos/encabezados
      $hayDatos = false;
      $fila = 0;
      $contador = 1;
      // recorrer las filas de la hoja INGRESO
      foreach($arrIngreso as $key => $row) {
        $primeraCelda = trim($row[0]);
        if ($primeraCelda != '' && $fila == 0) $hayDatos = true;
        if ($hayDatos) {
          // escribir el array según la estructura de la hoja
          if ($contador == 1) {
            // este grupo de datos representa
            // la primera fila de datos
            $filaProceso[$fila]['igr_area'] = trim($row[1]);
            $filaProceso[$fila]['igr_ges'] = trim($row[2]);
            $filaProceso[$fila]['igr_trabajador'] = trim($row[5]);
            $filaProceso[$fila]['igr_fri'] = trim($row[12]);
            $filaProceso[$fila]['igr_ciclott'] = trim($row[19]);
            $filaProceso[$fila]['igr_neqdbc'] = trim($row[21]);
            $filaProceso[$fila]['igr_peakc'] = trim($row[22]);
          } else {
            // este grupo de datos representa
            // las siguientes filas de datos y 
            // se concatenan en el array con el caracter |
            if (trim($row[12]) != '')
              $filaProceso[$fila]['igr_fri'] .= ' | ' . trim($row[12]);
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

      // iniciar variables
      $cuenta = 0;
      $fila = 0;
      $contador = 1;
      // cantidad de filas de la primera hoja para evitar filas adicionales
      $totalFilas = count($filaProceso);
      // recorrer las filas de la hoja CICLOS
      foreach($arrCiclos as $key => $row) {
        $primeraCelda = trim($row[0]);
        if ($primeraCelda != '') {
          $cuenta++;
        }
        if ($cuenta >= 2) {
          // escribir el array según la estructura de la hoja
          if ($contador == 1) {
            // este grupo de datos representa
            // la primera fila de datos
            $filaProceso[$fila]['cic_neqdbc'] = trim($row[4]);
            $filaProceso[$fila]['cic_tm'] = trim($row[5]);
            $filaProceso[$fila]['cic_tej'] = trim($row[6]);
          } else {
            // este grupo de datos representa
            // las siguientes filas de datos y 
            // se concatenan en el array con el caracter |
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

      // grabar el proceso en la base de datos
      $proceso = new Proceso;
      $proceso->descripcion = $procesoDescripcion;
      $proceso->archivo = $archivo;
      $proceso->estado = 1;
      // almacenar el proceso
      $proceso->save();
      // obtener el id de proceso
      $procesoId = $proceso->id;
      // grabar detalle de proceso
      // recorrer el array $filaProceso
      foreach($filaProceso as $detalle) {
        // armar la estructura para las filas del archivo
        $procesoDetalle = new ProcesoDetalle;
        $procesoDetalle->id_proceso = $procesoId;
        $procesoDetalle->igr_area = $detalle['igr_area'];
        $procesoDetalle->igr_ges = $detalle['igr_ges'];
        $procesoDetalle->igr_trabajador = $detalle['igr_trabajador'];
        $procesoDetalle->igr_fri = $detalle['igr_fri'];
        $procesoDetalle->igr_ciclott = $detalle['igr_ciclott'];
        // a continuación los campos concatenados se separan con 'explode' y 
        // se almacenan en los campos de base de datos
        // siempre que esten presentes 'isset'
        $igr_neqdbc = explode('|', $detalle['igr_neqdbc']);
        $procesoDetalle->igr_neqdbc_1 = isset($igr_neqdbc[0]) && $igr_neqdbc[0] != null ? $igr_neqdbc[0] : 0;
        $procesoDetalle->igr_neqdbc_2 = isset($igr_neqdbc[1]) && $igr_neqdbc[1] != null ? $igr_neqdbc[1] : 0;
        $procesoDetalle->igr_neqdbc_3 = isset($igr_neqdbc[2]) && $igr_neqdbc[2] != null ? $igr_neqdbc[2] : 0;
        $procesoDetalle->igr_neqdbc_4 = isset($igr_neqdbc[3]) && $igr_neqdbc[3] != null ? $igr_neqdbc[3] : 0;
        $procesoDetalle->igr_neqdbc_5 = isset($igr_neqdbc[4]) && $igr_neqdbc[4] != null ? $igr_neqdbc[4] : 0;
        $igr_peakc = explode('|', $detalle['igr_peakc']);
        $procesoDetalle->igr_peakc_1 = isset($igr_peakc[0]) && $igr_peakc[0] != null ? $igr_peakc[0] : 0;
        $procesoDetalle->igr_peakc_2 = isset($igr_peakc[1]) && $igr_peakc[1] != null ? $igr_peakc[1] : 0;
        $procesoDetalle->igr_peakc_3 = isset($igr_peakc[2]) && $igr_peakc[2] != null ? $igr_peakc[2] : 0;
        $procesoDetalle->igr_peakc_4 = isset($igr_peakc[3]) && $igr_peakc[3] != null ? $igr_peakc[3] : 0;
        $procesoDetalle->igr_peakc_5 = isset($igr_peakc[4]) && $igr_peakc[4] != null ? $igr_peakc[4] : 0;
        $cic_neqdbc = explode('|', $detalle['cic_neqdbc']);
        $procesoDetalle->cic_neqdbc_1 = isset($cic_neqdbc[0]) && $cic_neqdbc[0] != null ? $cic_neqdbc[0] : 0;
        $procesoDetalle->cic_neqdbc_2 = isset($cic_neqdbc[1]) && $cic_neqdbc[1] != null ? $cic_neqdbc[1] : 0;
        $procesoDetalle->cic_neqdbc_3 = isset($cic_neqdbc[2]) && $cic_neqdbc[2] != null ? $cic_neqdbc[2] : 0;
        $procesoDetalle->cic_neqdbc_4 = isset($cic_neqdbc[3]) && $cic_neqdbc[3] != null ? $cic_neqdbc[3] : 0;
        $procesoDetalle->cic_neqdbc_5 = isset($cic_neqdbc[4]) && $cic_neqdbc[4] != null ? $cic_neqdbc[4] : 0;
        $cic_tm = explode('|', $detalle['cic_tm']);
        $procesoDetalle->cic_tm_1 = isset($cic_tm[0]) && $cic_tm[0] != null ? $cic_tm[0] : 0;
        $procesoDetalle->cic_tm_2 = isset($cic_tm[1]) && $cic_tm[1] != null ? $cic_tm[1] : 0;
        $procesoDetalle->cic_tm_3 = isset($cic_tm[2]) && $cic_tm[2] != null ? $cic_tm[2] : 0;
        $procesoDetalle->cic_tm_4 = isset($cic_tm[3]) && $cic_tm[3] != null ? $cic_tm[3] : 0;
        $procesoDetalle->cic_tm_5 = isset($cic_tm[4]) && $cic_tm[4] != null ? $cic_tm[4] : 0;
        $cic_tej = explode('|', $detalle['cic_tej']);
        $procesoDetalle->cic_tej_1 = isset($cic_tej[0]) && $cic_tej[0] != null ? $cic_tej[0] : 0;
        $procesoDetalle->cic_tej_2 = isset($cic_tej[1]) && $cic_tej[1] != null ? $cic_tej[1] : 0;
        $procesoDetalle->cic_tej_3 = isset($cic_tej[2]) && $cic_tej[2] != null ? $cic_tej[2] : 0;
        $procesoDetalle->cic_tej_4 = isset($cic_tej[3]) && $cic_tej[3] != null ? $cic_tej[3] : 0;
        $procesoDetalle->cic_tej_5 = isset($cic_tej[4]) && $cic_tej[4] != null ? $cic_tej[4] : 0;
        // almacenar los registros de datos
        $procesoDetalle->save();
      }
      // cargar vista con resultados cantidad de filas procesadas
      $filas = count($filaProceso);
      return view('resultados')->with('filas', $filas)->with('proceso', $procesoDescripcion);
    } catch (Exception $e) {
      // si sucedió un error devolver el mensaje de error
      $procesoDescripcion = Input::get('proceso', $fechaHoy);
      $mensaje = $e->getMessage();
      return view('error')->with('mensaje', $mensaje)->with('proceso', $procesoDescripcion);
    }
  }

  public function getHistorial() {
    // obtener la lista de últimos 10 procesos
    $proceso = Proceso::take(10)->orderBy('created_at', 'desc')->get();
    return view('historial')->with('procesos', $proceso);
  }

  public function getHistorialById($idProceso) {
    // obtenr las filas procesadas por proceso
    // descarga el archivo con las filas
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
    // obtiene los datos para armar el consolidado
    $procesoDetalle = ProcesoDetalle::orderBy('created_at', 'DESC')->get();
    return view('consolidado')->with('procesos', $procesoDetalle);
  }

  public function getConsolidado() {
    return (new ProcesosExport)->download('consolidado.xlsx');
    /*
    // obtiene el consolidado en archivo excel para la descarga
    $archivo = 'consolidado_'.date('dMY').'.xlsx';
    $procesoDetalle = ProcesoDetalle::orderBy('created_at', 'DESC')->get();
    // variable tipo array para almacenar la data y escribir el archivo
    $consolidado = [];
    $fila = 0;
    $registro = 1;
    // primera fila con los encabezados de columnas
    $consolidado[$fila][0] = '';
    $consolidado[$fila][1] = 'ÁREA DEPARTAMENTO';
    $consolidado[$fila][2] = 'GES Grupo de Exposición Similar';
    $consolidado[$fila][3] = 'Trabajador';
    $consolidado[$fila][4] = 'Fuentes de Ruido Incidentes (y estado)';
    $consolidado[$fila][5] = 'Ciclo de Trabajo o Tareas incluidas en medición';
    $consolidado[$fila][6] = 'Neq dBC';
    $consolidado[$fila][7] = 'PeakC';
    $consolidado[$fila][8] = 'Neq dBA Cada Ciclo';
    $consolidado[$fila][9] = 'Tiempo Medición (minutos)';
    $consolidado[$fila][10] = 'Tiempo Efectivo por jornada (horas)';
    // recorrer los datos y armar la estructura del consolidado
    foreach($procesoDetalle as $detalle) {
      $fila++;
      $consolidado[$fila][0] = $registro;
      $consolidado[$fila][1] = $detalle['igr_area'];
      $consolidado[$fila][2] = $detalle['igr_ges'];
      $consolidado[$fila][3] = $detalle['igr_trabajador'];
      $consolidado[$fila][4] = $detalle['igr_fri'];
      $consolidado[$fila][5] = $detalle['igr_ciclott'];
      $consolidado[$fila][6] = $detalle['igr_neqdbc_1'];
      $consolidado[$fila][7] = $detalle['igr_peakc_1'];
      $consolidado[$fila][8] = $detalle['cic_neqdbc_1'];
      $consolidado[$fila][9] = $detalle['cic_tm_1'];
      $consolidado[$fila][10] = $detalle['cic_tej_1'];
      if ($detalle->igr_neqdbc_2 > 0 || $detalle->igr_peakc_2 > 0 || $detalle->cic_neqdbc_2 > 0 || $detalle->cic_tm_2 > 0 || $detalle->cic_tej_2 > 0) {
        $fila++;
        $consolidado[$fila][0] = '';
        $consolidado[$fila][1] = '';
        $consolidado[$fila][2] = '';
        $consolidado[$fila][3] = '';
        $consolidado[$fila][4] = '';
        $consolidado[$fila][5] = '';
        $consolidado[$fila][6] = $detalle['igr_neqdbc_2'];
        $consolidado[$fila][7] = $detalle['igr_peakc_2'];
        $consolidado[$fila][8] = $detalle['cic_neqdbc_2'];
        $consolidado[$fila][9] = $detalle['cic_tm_2'];
        $consolidado[$fila][10] = $detalle['cic_tej_2'];
      }
      if ($detalle->igr_neqdbc_3 > 0 || $detalle->igr_peakc_3 > 0 || $detalle->cic_neqdbc_3 > 0 || $detalle->cic_tm_3 > 0 || $detalle->cic_tej_3 > 0) {
        $fila++;
        $consolidado[$fila][0] = '';
        $consolidado[$fila][1] = '';
        $consolidado[$fila][2] = '';
        $consolidado[$fila][3] = '';
        $consolidado[$fila][4] = '';
        $consolidado[$fila][5] = '';
        $consolidado[$fila][6] = $detalle['igr_neqdbc_3'];
        $consolidado[$fila][7] = $detalle['igr_peakc_3'];
        $consolidado[$fila][8] = $detalle['cic_neqdbc_3'];
        $consolidado[$fila][9] = $detalle['cic_tm_3'];
        $consolidado[$fila][10] = $detalle['cic_tej_3'];
      }
      if ($detalle->igr_neqdbc_4 > 0 || $detalle->igr_peakc_4 > 0 || $detalle->cic_neqdbc_4 > 0 || $detalle->cic_tm_4 > 0 || $detalle->cic_tej_4 > 0) {
        $fila++;
        $consolidado[$fila][0] = '';
        $consolidado[$fila][1] = '';
        $consolidado[$fila][2] = '';
        $consolidado[$fila][3] = '';
        $consolidado[$fila][4] = '';
        $consolidado[$fila][5] = '';
        $consolidado[$fila][6] = $detalle['igr_neqdbc_4'];
        $consolidado[$fila][7] = $detalle['igr_peakc_4'];
        $consolidado[$fila][8] = $detalle['cic_neqdbc_4'];
        $consolidado[$fila][9] = $detalle['cic_tm_4'];
        $consolidado[$fila][10] = $detalle['cic_tej_4'];
      }
      if ($detalle->igr_neqdbc_5 > 0 || $detalle->igr_peakc_5 > 0 || $detalle->cic_neqdbc_5 > 0 || $detalle->cic_tm_5 > 0 || $detalle->cic_tej_5 > 0) {
        $fila++;
        $consolidado[$fila][0] = '';
        $consolidado[$fila][1] = '';
        $consolidado[$fila][2] = '';
        $consolidado[$fila][3] = '';
        $consolidado[$fila][4] = '';
        $consolidado[$fila][5] = '';
        $consolidado[$fila][6] = $detalle['igr_neqdbc_5'];
        $consolidado[$fila][7] = $detalle['igr_peakc_5'];
        $consolidado[$fila][8] = $detalle['cic_neqdbc_5'];
        $consolidado[$fila][9] = $detalle['cic_tm_5'];
        $consolidado[$fila][10] = $detalle['cic_tej_5'];
      }
      $registro++;
    }
    // declarar el archivo y descargar
    Excel::create($archivo, function($excel) use($consolidado) {
      $excel->sheet('Consolidado', function($sheet) use($consolidado) {
        $sheet->fromArray($consolidado, null, 'A1', false, false);
        $sheet->freezeFirstRow();
      });
    })->download('xlsx');
    */
  }
  public function getConsolidadoPDF(Request $request) {
    $procesoDetalle = ProcesoDetalle::orderBy('created_at', 'DESC')->get();
    $request->session()->put('procesoDetalle', $procesoDetalle);
    //print_r($procesoDetalle->toArray());exit;
    //return view('pdf.consolidado')->with('procesos', $procesoDetalle);
    $pdf = PDF::loadView('pdf.consolidado', $procesoDetalle->toArray());
    $pdf->setPaper('A4', 'landscape');
    return $pdf->download('consolidado.pdf');
  }
}
