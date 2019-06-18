<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proceso;
use App\ProcesoDetalle;
use App\Imports\ProcesosImport;
use App\Exports\ProcesosExport;
use Illuminate\Support\Facades\DB;
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
      $arrIni = $arrProceso[2];

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
      // datos INI
      $proceso->empresa = $arrIni[2][1];
      $proceso->rut = $arrIni[3][4];
      $proceso->adherente = $arrIni[3][1];
      $proceso->act_economica = $arrIni[4][2];
      $proceso->act_economica_cod = $arrIni[4][1];
      $proceso->centro_trabajo = $arrIni[5][1];
      $proceso->comuna = $arrIni[5][4];
      $proceso->rep_legal = $arrIni[6][1];
      $proceso->contacto_empresa = $arrIni[16][1];
      $proceso->contacto_empresa_telefono = $arrIni[17][1];
      $proceso->avance_obras = $arrIni[7][1];
      $proceso->t1_horas = $arrIni[10][1];
      $proceso->t1_dias = $arrIni[11][1];
      $proceso->t1_jornada = intval($arrIni[10][1]) && intval($arrIni[11][1]) ? round(intval($arrIni[10][1]) / intval($arrIni[11][1]), 1) : 0;
      $proceso->t2_horas = $arrIni[10][2];
      $proceso->t2_dias = $arrIni[11][2];
      $proceso->t2_jornada = intval($arrIni[10][2]) && intval($arrIni[11][2]) ? round(intval($arrIni[10][2]) / intval($arrIni[11][2]), 1) : 0;
      $proceso->t3_horas = $arrIni[10][3];
      $proceso->t3_dias = $arrIni[11][3];
      $proceso->t3_jornada = intval($arrIni[10][3]) && intval($arrIni[11][3]) ? round(intval($arrIni[10][3]) / intval($arrIni[11][3]), 1) : 0;
      $proceso->desc_sistema_turnos = $arrIni[14][1];
      $proceso->visita_1 = $arrIni[19][1];
      $proceso->visita_2 = $arrIni[19][2];
      $proceso->visita_3 = $arrIni[19][3];
      $proceso->visita_4 = $arrIni[19][4];
      $proceso->profesional_medicion = $arrIni[23][2];
      // FIN datos INI
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

  public function getReiniciarSistema() {
    DB::table('proceso_detalle')->truncate();
    DB::table('proceso')->truncate();
    return redirect('/');
  }
}
