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

  public function getGeneraFicha() {
    $procesos = Proceso::orderBy('id', 'desc')->take(1)->get();
    $proceso = $procesos[0];
    $arrParms = array(
      'template' => 'modelo1',
      'typefile' => 'docx'
    );
    $arrData = array(
      // INFORME DE EVALUACION
      'TAG_P_INFO_CLIENTE' => 'CLIENTE',
      'TAG_P_INFO_EMPRESA' => $proceso->empresa,
      'TAG_P_INFO_RUT' => $proceso->rut,
      'TAG_P_INFO_NROADHERENTE' => $proceso->adherente,
      'TAG_P_INFO_ACTIVECONOMICA' => $proceso->act_economica_cod . ' - ' . $proceso->act_economica,
      'TAG_P_INFO_CENTROTRABAJO' => $proceso->centro_trabajo,
      'TAG_P_INFO_COMUNA' => $proceso->comuna,
      'TAG_P_INFO_REPLEGAL' => $proceso->rep_legal,
      'TAG_P_INFO_CONTACTOEMP' => $proceso->contacto_empresa,
      'TAG_P_INFO_CORREOCONTACTO' => $proceso->contacto_empresa_telefono,
      'TAG_P_INFO_NROINFORME' => 'VALOR NROINFORME',
      'TAG_P_INFO_NROMATRIZRUIDO' => 'VALOR',
      'TAG_P_INFO_FECHEMISIONINFO' => 'VALOR',
      'TAG_P_INFO_PROFESIONALTERRRENO' => 'VALOR',
      'TAG_P_INFO_PROFESIONALINFORME' => $proceso->profesional_medicion,
      'TAG_P_INFO_CARGOESPECIALIDAD' => 'VALOR',

      //1  ANTECEDENTES
      'TAG_P_ANTECEDENTE_SR' => 'LEONARDO (Gerente General)',
      'TAG_P_ANTECEDENTE_EMPRESA' => 'Empresa',
      'TAG_P_ANTECEDENTE_FECHA' => '01 de Mayo de 2019',
      'TAG_P_ANTECEDENTE_UBICADO' => 'UBICADO',
      'TAG_P_ANTECEDENTE_COMUNA' => 'COMUNA',
      'TAG_P_ANTECEDENTE_AUTORIZA' => 'autorización/en compañía',
  
      //3.1  DESCRIPCIÓN DEL CENTRO DE TRABAJO Y ACTIVIDADES
      'TAG_P_DCTA_EMPRESA' => 'Empresa',
      'TAG_P_DCTA_PLANTA' => 'PLANTA',
      'TAG_P_DCTA_UBICADA' => 'UBICADO',
      'TAG_P_DCTA_COMUNA' => 'COMUNA',
      'TAG_P_DCTA_PARRAFO2' => 'En el centro de trabajo evaluado se Fabricación de estructuras metálicas para la industria y minería. Esta planta consta de 5 plantas de áridos, piso de tierra gran parte al aire libre con un terreno total de 50 hectáreas aproximadamente. Además cuenta con talleres y maestranza de soldadura, torno, taller mecánico y eléctrico para los camiones y maquinaria.',
      'TAG_P_DCTA_TITULO_IMAGEN' => 'Planta Principal',
      'TAG_P_DCTA_IMAGEN' => 'http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/templates/modelo1/images/000008.png',

      //3.2  ESTRATEGIA DE MEDICIONES Y EVALUACIÓN
      'TAG_T_EME_31DATA' => array(
        array('col1'=>'TALLER: OPERADORES DE TALLER', 'col2'=>'92.2', 'col3'=>'528%', 'col4'=>'ALTA', 'col5'=>'1', 'col6'=>'100%', 'col7'=>'Debe ingresar o mantenerse en PVSA'),
        array('col1'=>'TALLER: AYUDANTE SOLDADOR Y SOLDADORES', 'col2'=>'138.0', 'col3'=>'1273%', 'col4'=>'MUY ALTA', 'col5'=>'3', 'col6'=>'300%', 'col7'=>'Debe ingresar o mantenerse en PVSA'),
        array('col1'=>'TALLER: ARMADOR', 'col2'=>'143.5', 'col3'=>'4432%', 'col4'=>'MUY ALTA', 'col5'=>'3', 'col6'=>'300%', 'col7'=>'Debe ingresar o mantenerse en PVSA'),
        array('col1'=>'TALLER: ADMINISTRATIVO, SUPERVISOR', 'col2'=>'0.5', 'col3'=>'0%', 'col4'=>'MUY BAJA', 'col5'=>'1', 'col6'=>'100%', 'col7'=>'No requiere estar en PVSA')
      ),
      'TAG_P_EME_HORAS' => '8',
      'TAG_P_EME_PARRAFO1' => 'Lunes a Sábado de 08:00 am a 17:30 pm horas y Sabados de 08:00 am a 13:00 pm, incluyendo una hora de colación, la cual es excluida para la obtención de la jornada efectiva de 8 horas.',
      'TAG_P_EME_IMAGENLAYOUT' => 'http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/templates/modelo1/images/000007.png',

      //3.3  MARCAS Y MODELOS DE ELEMENTOS DE PROTECCIÓN AUDITIVA OBSERVADOS
      'TAG_T_MMEPAO_33DATA' => array(
        array('col1'=>'STEELPRO', 'col2'=>'EPT06C', 'col3'=>'TAPON REUTILIZABLE', 'col4'=>'Si'),
        array('col1'=>'MASPROT', 'col2'=>'MPA101C', 'col3'=>'OREJERA PARA CASCO', 'col4'=>'Si')
      ),

      //4.1  CLASIFICACIÓN DE EXPUESTOS POR NIVELES DE RIESGO
      'TAG_P_CENR_NROTRABAJADORES' => '7',
      'TAG_T_CENR_41DATA_COL1' => '1',
      'TAG_T_CENR_41DATA_COL2' => '0',
      'TAG_T_CENR_41DATA_COL3' => '0',
      'TAG_T_CENR_41DATA_COL4' => '0',
      'TAG_T_CENR_41DATA_COL5' => '7',
      'TAG_T_CENR_41DATA_COL6' => '0',

      //4.2  FUENTES DE RUIDO PRINCIPALES 
      'TAG_T_FRP_42DATA' => array(
        array('col1'=>'1: Sierras de corte', 'col2'=>'Fricción del disco al cortar materiales', 'col3'=>'108.7', 'col4'=>'7'),
        array('col1'=>'2: Esmeril angular', 'col2'=>'Fricción del disco al cortar materiales', 'col3'=>'108.7', 'col4'=>'7'),
        array('col1'=>'3: Matillo de punto y combo', 'col2'=>'Golpe contra superficies metálicas', 'col3'=>'108.7', 'col4'=>'7')
      ),

      //4.4  RESPECTO A PROTECCIÓN AUDITIVA
      'TAG_P_RPA_SEENCUENTRA' => 'se encuentran',
      'TAG_P_RPA_PARRAFO1' => '(Señalar Detalles al respecto)',
      'TAG_P_RPA_APORTAN' => 'aportan/no',
      'TAG_P_RPA_PARRAFO2' => '(Señalar Detalles al respecto)',

      //5.1  RESUMEN DE MEDIDAS TÉCNICAS
      'TAG_T_RMT_51DATA' => array(
        array('col1'=>"TALLER: <br class='calibre11'/>OPERADORES DE TALLER", 
          'col2'=>"<p class='block_57'>Barreras acústicas fijas / paneles móviles</p><p class='block_57'>Absortores acústicos en paredes / techo</p><p class='block_57'>Martillo de goma para disminución de ruido impulsivo</p>", 
          'col3'=>'12 meses'),
        array('col1'=>"TALLER: <br class='calibre11'/>AYUDANTE SOLDADOR Y SOLDADORES", 
          'col2'=>"<p class='block_57'>Barreras acústicas fijas / paneles móviles</p><p class='block_57'>Absortores acústicos en paredes / techo</p>", 
          'col3'=>'6 meses'),
        array('col1'=>"TALLER: <br class='calibre11'/>ARMADOR", 
          'col2'=>"<p class='block_57'>Barreras acústicas fijas / paneles móviles</p><p class='block_57'>Absortores acústicos en paredes / techo</p><p class='block_57'>Martillo de goma para disminución de ruido impulsivo</p>", 
          'col3'=>'6 meses'),
      ),

      //5.3  PRE-SELECCIÓN TEÓRICA DE PROTECTORES AUDITIVOS POR CADA GES
      'TAG_T_PSTPAG_53DATA' => array(
        array('col1'=>'Cámara de corte: Operador de tronzadora', 
          'col2'=>'MMM', 'col3'=>'H9P3E', 'col4'=>'Orejera de casco', 'col5'=>'75,1', 'col6'=>'Permanente'),
        array('col1'=>'', 'col2'=>'HOWARD LEIGHT', 'col3'=>'THUNDER T2H', 'col4'=>'Orejera', 'col5'=>'75,1', 'col6'=>''),
        array('col1'=>'', 'col2'=>'3M', 'col3'=>'ULTRAFIT C/CORDÓN', 'col4'=>'Tapón reutilizable', 'col5'=>'73,1', 'col6'=>''),
        array('col1'=>'&nbsp;', 'col2'=>'', 'col3'=>'', 'col4'=>'', 'col5'=>'', 'col6'=>'Temporal'),
        array('col1'=>'', 'col2'=>'', 'col3'=>'', 'col4'=>'', 'col5'=>'', 'col6'=>''),
        array('col1'=>'', 'col2'=>'', 'col3'=>'', 'col4'=>'', 'col5'=>'', 'col6'=>''),
      ),

      //5.5  SISTEMA DE GESTIÓN ACTUALIZACIÓN Y MEJORA CONTINUA DE MATRIZ DE RUIDO 
      'TAG_P_SGAMCMR_PREXOR' => 'ESTRUCTURAS METALICAS RALFU Y CIA. LTDA',
      'TAG_P_SGAMCMR_FECHA' => '8 de agosto de 2018',
      'TAG_P_SGAMCMR_REALIZADOR1' => 'Realizador: Nombres y apellidos',
      'TAG_P_SGAMCMR_REALIZADOR2' => 'INGENIERO ACÚSTICO',
      'TAG_P_SGAMCMR_REALIZADOR3' => 'HIGIENISTA OCUPACIONAL',
      'TAG_P_SGAMCMR_REVISOR1' => 'Revisor: Nombres y apellidos',
      'TAG_P_SGAMCMR_REVISOR2' => 'INGENIERO ACÚSTICO',
      'TAG_P_SGAMCMR_REVISOR3' => 'HIGIENISTA OCUPACIONAL',

      //6.2.3  ROTACION DE PERSONAL Y CONTROL DE TIEMPOS DE EXPOSICIÓN
      'TAG_P_RPCTE_EMPRESA' => 'Sr………… en',
      'TAG_T_RPCTE_61DATA' => array(
        array('col1'=>'Fundir ambos GES', 'col1rowspan' => '3',
          'col2'=>'GES 10 (2 personas)', 'col2rowspan' => '2', 'col3'=>'Operación máquina 1 (4h)', 'col4'=>'200%', 'col5'=>'2 h', 'col6'=>'50%'),
        array('col1'=>'', 'col1rowspan' => '',
          'col2'=>'', 'col2rowspan' => '', 'col3'=>'Operación máquina 2 (4h)', 'col4'=>'800%', 'col5'=>'0,5 h', 'col6'=>'50%'),
        array('col1'=>'', 'col1rowspan' => '',
          'col2'=>'GES 20 (6 personas)', 'col2rowspan' => '1', 'col3'=>'Recepción de línea 1 (8h)', 'col4'=>'25%', 'col5'=>'5,5 h', 'col6'=>'17%'),
        array('col1'=>'Reordenar trabajo: Evitar cortar en 1 día, piezas para toda  la semana', 'col1rowspan' => '2',
          'col2'=>'GES 301', 'col2rowspan' => '2', 'col3'=>'Soldadura (4h)', 'col4'=>'100%', 'col5'=>'6 h', 'col6'=>'75%'),
        array('col1'=>'', 'col1rowspan' => '',
          'col2'=>'', 'col2rowspan' => '', 'col3'=>'Corte con tronzadora (4h)', 'col4'=>'800%', 'col5'=>'1h', 'col6'=>'100%'),
      ),

      //6.3  SELECCIÓN TEÓRICA DE PROTECTORES AUDITIVOS POR CADA GES Y GESTIÓN DE EEPA DE PARTE DE LA EMPRESA
      'TAG_T_STPAGGEPE_62DATA' => array(
        array('col1'=>'TALLER: OPERADORES DE TALLER',
          'col2'=>"<ul class='list_'>
            <li class='block_85'>SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.</li>
            <li class='block_85'>USO DE TALADRO VERTICAL EN MATERIALES FERROSOS</li>
            <li class='block_85'>CORTES DE MATERIALES FERROSOS, DESBASTES. CON ESMERIL </li>
            <li class='block_85'>OPERADOR UTILIZA MARTILLO PARA MARCAR PIEZAS </li>
            <li class='block_85'>USO DE MAQUINA DE CORTE EN OCACIONES DE ANGULOS U OTRAS PIEZAS</li>
            <li class='block_85'>SEGÚN NECESIDADES DE FABRICACIÓN SE REALIZAN CORTES CON PLASMA</li>
            <li class='block_85'>LABORES DE ASEO, BUSQUEDA DE PLANOS, <span class='calibre14'>BAÑO,  BEBER</span> AGUA, BUSQUEDA DE MATERIALES Y EPP.</li></ul>", 
          'col3'=>'94.7', 'col4'=>'94.9'),
        array('col1'=>'TALLER: AYUDANTE SOLDADOR Y SOLDADORES',
          'col2'=>"<ul class='list_'>
            <li class='block_85'>SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.</li>
            <li class='block_85'>TRABAJADOR COMIENZA A UTILIZAR MAQUINA SOLDADORA, PARA LA UNION DE MATERIALES FERROSOS. </li>
            <li class='block_85'>LABORES DE ASEO, BUSQUEDA DE PLANOS, <span class='calibre14'>BAÑO,  BEBER</span> AGUA, BUSQUEDA DE MATERIALES Y EPP.</li></ul>", 
          'col3'=>'95.5', 'col4'=>'94.8'),
        array('col1'=>'TALLER: ARMADOR',
          'col2'=>"<ul class='list_'>
            <li class='block_85'>SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.</li>
            <li class='block_85'><span class='calibre14'>Armador  recibe</span> el material, comienzan a empalmar utilizando maquina soldadora para luego entregar al soldador. </li>
            <li class='block_85'>ARMADOR UTILIZA <span class='calibre14'>EL  MARTILLO</span> DE PUNTO PARA ALINEAR ESTRUCTURAS Y ENCAJARLAS BIEN NO QUEDEN MAL EMPALMADAS</li>
            <li class='block_85'>TRABAJADOR OPERA ESMERIL PARA LIMPIAR MATERIAL <span class='calibre14'>FERROSOS,  SACAR</span> EXCESO DE SOLDADURA. </li>
            <li class='block_85'>LABORES DE ASEO, BUSQUEDA DE PLANOS, <span class='calibre14'>BAÑO,  BEBER</span> AGUA, BUSQUEDA DE MATERIALES Y EPP, TAREAS PROPIAS DEL CARGO, ENCAJAR PIEZAS Y ARMAR</li></ul>", 
          'col3'=>'100.9', 'col4'=>'101'),
      ),

      //6.7  CERTIFICADOS DE CALIBRACIÓN
      'TAG_P_CC_UBICADO' => 'UBICADO',

      //6.8  FOTOS ADICIONALES DE ÁREAS VISITADAS
      'TAG_P_FAAV_IMAGEN' => 'http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/templates/modelo1/images/000011.png',
    );
    $arrParms['data'] = $arrData;
    $params = base64_encode(json_encode($arrParms));
    $postdata = http_build_query(array('params' => $params));
    $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-Type: application/x-www-form-urlencoded', 'content' => $postdata));
    $context  = stream_context_create($opts);
    echo file_get_contents("http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/generatedocx.php", false, $context);
  }
}
