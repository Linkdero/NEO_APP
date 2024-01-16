<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
	$id_persona='';
	if(isset($_GET['id_persona'])){
		$id_persona=$_GET['id_persona'];
	}

	$clase = new empleado;

	$e = $clase->get_empleado_info($id_persona);

	$response = array(
	  'id_persona'=>$e['id_persona'],
    'id_empleado'=>(!empty($e['id_empleado']))?$e['id_empleado']:0,
	  'nombre'=>'',//ucwords($e['primer_nombre']).' '.ucwords($e['segundo_nombre']).' '.ucwords($e['tercer_nombre']).' '.ucwords($e['primer_apellido']).' '.ucwords($e['segundo_apellido']).' '.ucwords($e['tercer_apellido']),
    'tipo_persona'=>$e['tipo_persona'],
    'tipo_aspirante'=>$e['id_tipo_aspirante'],
    //'estado'=>$e['emp_estado']
	);

  $empleado = ucwords($e['primer_nombre']).' '.ucwords($e['segundo_nombre']).' '.ucwords($e['tercer_nombre']).' '.ucwords($e['primer_apellido']).' '.ucwords($e['segundo_apellido']).' '.ucwords($e['tercer_apellido']);
  createLog(84, 1163, 'rrhh_empleado','Visualizando los datos laborales del empleado: - codigo: '.$e['id_persona'].' - '.$empleado,'', '');

	echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
