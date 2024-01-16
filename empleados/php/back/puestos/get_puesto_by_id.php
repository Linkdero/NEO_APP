<?php

include_once '../../../../inc/functions.php';


sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');

  include_once '../functions.php';
	$response = array();
	$id_asignacion='';
  $reng_num='';
  $id_persona='';
  if(isset($_GET['id_persona'])){
		$id_persona=$_GET['id_persona'];
	}
	if(isset($_GET['id_asignacion'])){
		$id_asignacion=$_GET['id_asignacion'];
	}
  if(isset($_GET['reng_num'])){
		$reng_num=$_GET['reng_num'];
	}

  $clase_e = new empleado;
  //$tipo= $clase_e->get_tipo_contrato_by_empleado($id_persona);


    include_once '../functions_plaza.php';
    //$clase = new plaza;
    $e = $clase_e->get_ubicacion_by_id($id_asignacion,$reng_num);

  	$response = array(
      'id_asignacion'=>$e['id_asignacion'],
      'reng_num'=>$e['reng_num'],
      'id_plaza'=>$e['id_plaza'],
      'fecha_inicio'=>date('Y-m-d', strtotime($e['fecha_inicio'])),
      'fecha_toma_posesion'=>date('Y-m-d', strtotime($e['fecha_toma_posesion'])),
      'fecha_fin'=>date('Y-m-d', strtotime($e['fecha_fin'])),
      'acuerdo'=>$e['acuerdo'],
      'nivel_f'=>$e['nivel_f'],
      'secretaria_f'=>$e['secretaria_f'],
      'subsecretaria_f'=>$e['subsecretaria_f'],
      'direccion_f'=>$e['direccion_f'],
      'subdireccion_f'=>$e['subdireccion_f'],
      'departamento_f'=>$e['departamento_f'],
      'seccion_f'=>$e['seccion_f'],
      'puesto_f'=>$e['puesto_f'],
      'observaciones'=>$e['observaciones'],
  	);

	echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
