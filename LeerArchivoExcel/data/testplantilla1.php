<?php
	ini_set('max_execution_time', 600);
	ini_set('memory_limit', '256M');

	$arrParms = array(
		"template" => "modelo1",
		"typefile" => "docx"
	);

    $arrData = array(
		// INFORME DE EVALUACION
		"TAG_P_INFO_CLIENTE" => "CLIENTE",
		"TAG_P_INFO_EMPRESA" => "Empresa 123",
		"TAG_P_INFO_RUT" => "22222222222",
		"TAG_P_INFO_NROADHERENTE" => "VALOR",
		"TAG_P_INFO_ACTIVECONOMICA" => "VALOR",
		"TAG_P_INFO_CENTROTRABAJO" => "VALOR",
		"TAG_P_INFO_COMUNA" => "VALOR",
		"TAG_P_INFO_REPLEGAL" => "VALOR",
		"TAG_P_INFO_CONTACTOEMP" => "VALOR",
		"TAG_P_INFO_CORREOCONTACTO" => "VALOR",
		"TAG_P_INFO_NROINFORME" => "VALOR",
		"TAG_P_INFO_NROMATRIZRUIDO" => "VALOR",
		"TAG_P_INFO_FECHEMISIONINFO" => "VALOR",
		"TAG_P_INFO_PROFESIONALTERRRENO" => "VALOR",
		"TAG_P_INFO_PROFESIONALINFORME" => "VALOR",
		"TAG_P_INFO_CARGOESPECIALIDAD" => "VALOR",
		
		//1	ANTECEDENTES
		"TAG_P_ANTECEDENTE_SR" => "LEONARDO (Gerente General)",
		"TAG_P_ANTECEDENTE_EMPRESA" => "Empresa",
		"TAG_P_ANTECEDENTE_FECHA" => "01 de Mayo de 2019",
		"TAG_P_ANTECEDENTE_UBICADO" => "UBICADO",
		"TAG_P_ANTECEDENTE_COMUNA" => "COMUNA",
		"TAG_P_ANTECEDENTE_AUTORIZA" => "autorización/en compañía",

		//3.1	DESCRIPCIÓN DEL CENTRO DE TRABAJO Y ACTIVIDADES
		"TAG_P_DCTA_EMPRESA" => "Empresa",
		"TAG_P_DCTA_PLANTA" => "PLANTA",
		"TAG_P_DCTA_UBICADA" => "UBICADO",
		"TAG_P_DCTA_COMUNA" => "COMUNA",
		"TAG_P_DCTA_PARRAFO2" => "En el centro de trabajo evaluado se Fabricación de estructuras metálicas para la industria y minería. Esta planta consta de 5 plantas de áridos, piso de tierra gran parte al aire libre con un terreno total de 50 hectáreas aproximadamente. Además cuenta con talleres y maestranza de soldadura, torno, taller mecánico y eléctrico para los camiones y maquinaria.",
		"TAG_P_DCTA_TITULO_IMAGEN" => "Planta Principal",
		"TAG_P_DCTA_IMAGEN" => "http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/templates/modelo1/images/000008.png",
		
		//3.2	ESTRATEGIA DE MEDICIONES Y EVALUACIÓN
		"TAG_T_EME_31DATA" => array(
			array("col1"=>"TALLER: OPERADORES DE TALLER", "col2"=>"92.2", "col3"=>"528%", "col4"=>"ALTA", "col5"=>"1", "col6"=>"100%", "col7"=>"Debe ingresar o mantenerse en PVSA"),
			array("col1"=>"TALLER: AYUDANTE SOLDADOR Y SOLDADORES", "col2"=>"138.0", "col3"=>"1273%", "col4"=>"MUY ALTA", "col5"=>"3", "col6"=>"300%", "col7"=>"Debe ingresar o mantenerse en PVSA"),
			array("col1"=>"TALLER: ARMADOR", "col2"=>"143.5", "col3"=>"4432%", "col4"=>"MUY ALTA", "col5"=>"3", "col6"=>"300%", "col7"=>"Debe ingresar o mantenerse en PVSA"),
			array("col1"=>"TALLER: ADMINISTRATIVO, SUPERVISOR", "col2"=>"0.5", "col3"=>"0%", "col4"=>"MUY BAJA", "col5"=>"1", "col6"=>"100%", "col7"=>"No requiere estar en PVSA")
		),
		"TAG_P_EME_HORAS" => "8",
		"TAG_P_EME_PARRAFO1" => "Lunes a Sábado de 08:00 am a 17:30 pm horas y Sabados de 08:00 am a 13:00 pm, incluyendo una hora de colación, la cual es excluida para la obtención de la jornada efectiva de 8 horas.",
		"TAG_P_EME_IMAGENLAYOUT" => "http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/templates/modelo1/images/000007.png",
		
		//3.3	MARCAS Y MODELOS DE ELEMENTOS DE PROTECCIÓN AUDITIVA OBSERVADOS
		"TAG_T_MMEPAO_33DATA" => array(
			array("col1"=>"STEELPRO", "col2"=>"EPT06C", "col3"=>"TAPON REUTILIZABLE", "col4"=>"Si"),
			array("col1"=>"MASPROT", "col2"=>"MPA101C", "col3"=>"OREJERA PARA CASCO", "col4"=>"Si")
		),

		//4.1	CLASIFICACIÓN DE EXPUESTOS POR NIVELES DE RIESGO
		"TAG_P_CENR_NROTRABAJADORES" => "7",
		"TAG_T_CENR_41DATA_COL1" => "1",
		"TAG_T_CENR_41DATA_COL2" => "0",
		"TAG_T_CENR_41DATA_COL3" => "0",
		"TAG_T_CENR_41DATA_COL4" => "0",
		"TAG_T_CENR_41DATA_COL5" => "7",
		"TAG_T_CENR_41DATA_COL6" => "0",

		//4.2	FUENTES DE RUIDO PRINCIPALES 
		"TAG_T_FRP_42DATA" => array(
			array("col1"=>"1: Sierras de corte", "col2"=>"Fricción del disco al cortar materiales", "col3"=>"108.7", "col4"=>"7"),
			array("col1"=>"2: Esmeril angular", "col2"=>"Fricción del disco al cortar materiales", "col3"=>"108.7", "col4"=>"7"),
			array("col1"=>"3: Matillo de punto y combo", "col2"=>"Golpe contra superficies metálicas", "col3"=>"108.7", "col4"=>"7")
		),

		//4.4	RESPECTO A PROTECCIÓN AUDITIVA
		"TAG_P_RPA_SEENCUENTRA" => "se encuentran",
		"TAG_P_RPA_PARRAFO1" => "(Señalar Detalles al respecto)",
		"TAG_P_RPA_APORTAN" => "aportan/no",
		"TAG_P_RPA_PARRAFO2" => "(Señalar Detalles al respecto)",

		//5.1	RESUMEN DE MEDIDAS TÉCNICAS
		"TAG_T_RMT_51DATA" => array(
			array("col1"=>"TALLER: <br class='calibre11'/>OPERADORES DE TALLER", 
				"col2"=>"<p class='block_57'>Barreras acústicas fijas / paneles móviles</p><p class='block_57'>Absortores acústicos en paredes / techo</p><p class='block_57'>Martillo de goma para disminución de ruido impulsivo</p>", 
				"col3"=>"12 meses"),
			array("col1"=>"TALLER: <br class='calibre11'/>AYUDANTE SOLDADOR Y SOLDADORES", 
				"col2"=>"<p class='block_57'>Barreras acústicas fijas / paneles móviles</p><p class='block_57'>Absortores acústicos en paredes / techo</p>", 
				"col3"=>"6 meses"),
			array("col1"=>"TALLER: <br class='calibre11'/>ARMADOR", 
				"col2"=>"<p class='block_57'>Barreras acústicas fijas / paneles móviles</p><p class='block_57'>Absortores acústicos en paredes / techo</p><p class='block_57'>Martillo de goma para disminución de ruido impulsivo</p>", 
				"col3"=>"6 meses"),
		),

		//5.3	PRE-SELECCIÓN TEÓRICA DE PROTECTORES AUDITIVOS POR CADA GES
		"TAG_T_PSTPAG_53DATA" => array(
			array("col1"=>"Cámara de corte: Operador de tronzadora", 
				"col2"=>"MMM", "col3"=>"H9P3E", "col4"=>"Orejera de casco", "col5"=>"75,1", "col6"=>"Permanente"),
			array("col1"=>"", "col2"=>"HOWARD LEIGHT", "col3"=>"THUNDER T2H", "col4"=>"Orejera", "col5"=>"75,1", "col6"=>""),
			array("col1"=>"", "col2"=>"3M", "col3"=>"ULTRAFIT C/CORDÓN", "col4"=>"Tapón reutilizable", "col5"=>"73,1", "col6"=>""),
			array("col1"=>"&nbsp;", "col2"=>"", "col3"=>"", "col4"=>"", "col5"=>"", "col6"=>"Temporal"),
			array("col1"=>"", "col2"=>"", "col3"=>"", "col4"=>"", "col5"=>"", "col6"=>""),
			array("col1"=>"", "col2"=>"", "col3"=>"", "col4"=>"", "col5"=>"", "col6"=>""),
		),

		//5.5	SISTEMA DE GESTIÓN ACTUALIZACIÓN Y MEJORA CONTINUA DE MATRIZ DE RUIDO 
		"TAG_P_SGAMCMR_PREXOR" => "ESTRUCTURAS METALICAS RALFU Y CIA. LTDA",
		"TAG_P_SGAMCMR_FECHA" => "8 de agosto de 2018",
		"TAG_P_SGAMCMR_REALIZADOR1" => "Realizador: Nombres y apellidos",
		"TAG_P_SGAMCMR_REALIZADOR2" => "INGENIERO ACÚSTICO",
		"TAG_P_SGAMCMR_REALIZADOR3" => "HIGIENISTA OCUPACIONAL",
		"TAG_P_SGAMCMR_REVISOR1" => "Revisor: Nombres y apellidos",
		"TAG_P_SGAMCMR_REVISOR2" => "INGENIERO ACÚSTICO",
		"TAG_P_SGAMCMR_REVISOR3" => "HIGIENISTA OCUPACIONAL",

		//6.2.3	ROTACION DE PERSONAL Y CONTROL DE TIEMPOS DE EXPOSICIÓN
		"TAG_P_RPCTE_EMPRESA" => "Sr………… en",
		"TAG_T_RPCTE_61DATA" => array(
			array("col1"=>"Fundir ambos GES", "col1rowspan" => "3",
				"col2"=>"GES 10 (2 personas)", "col2rowspan" => "2", "col3"=>"Operación máquina 1 (4h)", "col4"=>"200%", "col5"=>"2 h", "col6"=>"50%"),
			array("col1"=>"", "col1rowspan" => "",
				"col2"=>"", "col2rowspan" => "", "col3"=>"Operación máquina 2 (4h)", "col4"=>"800%", "col5"=>"0,5 h", "col6"=>"50%"),
			array("col1"=>"", "col1rowspan" => "",
				"col2"=>"GES 20 (6 personas)", "col2rowspan" => "1", "col3"=>"Recepción de línea 1 (8h)", "col4"=>"25%", "col5"=>"5,5 h", "col6"=>"17%"),
			array("col1"=>"Reordenar trabajo: Evitar cortar en 1 día, piezas para toda  la semana", "col1rowspan" => "2",
				"col2"=>"GES 301", "col2rowspan" => "2", "col3"=>"Soldadura (4h)", "col4"=>"100%", "col5"=>"6 h", "col6"=>"75%"),
			array("col1"=>"", "col1rowspan" => "",
				"col2"=>"", "col2rowspan" => "", "col3"=>"Corte con tronzadora (4h)", "col4"=>"800%", "col5"=>"1h", "col6"=>"100%"),
		),

		//6.3	SELECCIÓN TEÓRICA DE PROTECTORES AUDITIVOS POR CADA GES Y GESTIÓN DE EEPA DE PARTE DE LA EMPRESA
		"TAG_T_STPAGGEPE_62DATA" => array(
			array("col1"=>"TALLER: OPERADORES DE TALLER",
				"col2"=>"<ul class='list_'>
					<li class='block_85'>SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.</li>
					<li class='block_85'>USO DE TALADRO VERTICAL EN MATERIALES FERROSOS</li>
					<li class='block_85'>CORTES DE MATERIALES FERROSOS, DESBASTES. CON ESMERIL </li>
					<li class='block_85'>OPERADOR UTILIZA MARTILLO PARA MARCAR PIEZAS </li>
					<li class='block_85'>USO DE MAQUINA DE CORTE EN OCACIONES DE ANGULOS U OTRAS PIEZAS</li>
					<li class='block_85'>SEGÚN NECESIDADES DE FABRICACIÓN SE REALIZAN CORTES CON PLASMA</li>
					<li class='block_85'>LABORES DE ASEO, BUSQUEDA DE PLANOS, <span class='calibre14'>BAÑO,  BEBER</span> AGUA, BUSQUEDA DE MATERIALES Y EPP.</li></ul>", 
				"col3"=>"94.7", "col4"=>"94.9"),
			array("col1"=>"TALLER: AYUDANTE SOLDADOR Y SOLDADORES",
				"col2"=>"<ul class='list_'>
					<li class='block_85'>SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.</li>
					<li class='block_85'>TRABAJADOR COMIENZA A UTILIZAR MAQUINA SOLDADORA, PARA LA UNION DE MATERIALES FERROSOS. </li>
					<li class='block_85'>LABORES DE ASEO, BUSQUEDA DE PLANOS, <span class='calibre14'>BAÑO,  BEBER</span> AGUA, BUSQUEDA DE MATERIALES Y EPP.</li></ul>", 
				"col3"=>"95.5", "col4"=>"94.8"),
			array("col1"=>"TALLER: ARMADOR",
				"col2"=>"<ul class='list_'>
					<li class='block_85'>SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.</li>
					<li class='block_85'><span class='calibre14'>Armador  recibe</span> el material, comienzan a empalmar utilizando maquina soldadora para luego entregar al soldador. </li>
					<li class='block_85'>ARMADOR UTILIZA <span class='calibre14'>EL  MARTILLO</span> DE PUNTO PARA ALINEAR ESTRUCTURAS Y ENCAJARLAS BIEN NO QUEDEN MAL EMPALMADAS</li>
					<li class='block_85'>TRABAJADOR OPERA ESMERIL PARA LIMPIAR MATERIAL <span class='calibre14'>FERROSOS,  SACAR</span> EXCESO DE SOLDADURA. </li>
					<li class='block_85'>LABORES DE ASEO, BUSQUEDA DE PLANOS, <span class='calibre14'>BAÑO,  BEBER</span> AGUA, BUSQUEDA DE MATERIALES Y EPP, TAREAS PROPIAS DEL CARGO, ENCAJAR PIEZAS Y ARMAR</li></ul>", 
				"col3"=>"100.9", "col4"=>"101"),
		),

		//6.7	CERTIFICADOS DE CALIBRACIÓN
		"TAG_P_CC_UBICADO" => "UBICADO",

		//6.8	FOTOS ADICIONALES DE ÁREAS VISITADAS
		"TAG_P_FAAV_IMAGEN" => "http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/templates/modelo1/images/000011.png",
	);
	$arrParms["data"] = $arrData;
    
	$params = base64_encode(json_encode($arrParms));
	$postdata = http_build_query(array('params' => $params));
	$opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-Type: application/x-www-form-urlencoded', 'content' => $postdata));
	$context  = stream_context_create($opts);
	echo file_get_contents("http://ec2-18-191-196-64.us-east-2.compute.amazonaws.com/convertfiles/generatedocx.php", false, $context);
?>