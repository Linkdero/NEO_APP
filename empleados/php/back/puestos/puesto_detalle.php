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

  $tipo = $clase->get_tipo_contrato_by_empleado($id_persona);
  $tpersona =$clase->get_tipo_persona($id_persona);

  if($tipo['id_contrato']==7 || $tpersona['id_status']==2312 || $tpersona['id_status']==9102 || $tpersona['id_status']==5611){
    if(empty($tipo['id_empleado'])){
      $e = $clase->get_apoyo_actual_by_persona($id_persona,$tpersona['id_status']);
    }else{
      $e = $clase->get_empleado_puesto_actual($id_persona);
    }

  	$response = array(
  	  'id_persona'=>$e['id_persona'],
      'id_empleado'=>(!empty($e['id_empleado']))?$e['id_empleado']:9999999,
  	  //'nombre'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
  		'id_secretaria_nominal'=>(!empty($e['id_secretaria_presupuestario']))?$e['id_secretaria_presupuestario']:'Sin asignación',
  	  'id_subsecretaria_nominal'=>(!empty($e['id_subsecretaria_presupuestaria']))?$e['id_subsecretaria_presupuestaria']:'Sin asignación',
  	  'id_direccion_nominal'=>(!empty($e['id_direccion_presupuestaria']))?$e['id_direccion_presupuestaria']:'Sin asignación',
  		'secretarian'=>(!empty($e['secretarian']))?$e['secretarian']:'Sin asignación',
  		'subsecretarian'=>(!empty($e['subsecretarian']))?$e['subsecretarian']:'Sin asignación',
  		'direccionn'=>(!empty($e['direccionn']))?$e['direccionn']:'-- -- -- --',
  		'subdireccionn'=>(!empty($e['subdireccionn']))?$e['subdireccionn']:'-- -- -- --',
  		'departamenton'=>(!empty($e['departamenton']))?$e['departamenton']:'-- -- -- --',
  		'seccionn'=>(!empty($e['seccionn']))?$e['seccionn']:'-- -- -- --',
  		'pueston'=>(!empty($e['pueston']))?$e['pueston']:'-- -- -- --',

  	  'id_secretaria_funcional'=>(!empty($e['id_secretaria_funcional']))?$e['id_secretaria_funcional']:'Sin asignación',
  	  'id_subsecretaria_funcional'=>(!empty($e['id_subsecretaria_funcional']))?$e['id_subsecretaria_funcional']:'-- -- -- --',
  	  'id_direccion_funcional'=>(!empty($e['id_direccion_funcional']))?$e['id_direccion_funcional']:'-- -- -- --',
  		'secretariaf'=>(!empty($e['secretariaf']))?$e['secretariaf']:'-- -- -- --',
  		'subsecretariaf'=>(!empty($e['subsecretariaf']))?$e['subsecretariaf']:'-- -- -- --',
  		'direccionf'=>(!empty($e['direccionf']))?$e['direccionf']:'-- -- -- --',
  		'subdireccionf'=>(!empty($e['subdireccionf']))?$e['subdireccionf']:'-- -- -- --',
  		'departamentof'=>(!empty($e['departamentof']))?$e['departamentof']:'-- -- -- --',
  		'seccionf'=>(!empty($e['seccionf']))?$e['seccionf']:'-- -- -- --',
  		'puestof'=>(!empty($e['puestof']))?$e['puestof']:'-- -- -- --',
      //'estado_empleado'=>'1',
      'estado'=>$e['emp_estado'],
      'tipo_contrato'=>$tipo['id_contrato'],
      'tipo'=>$tipo['id_contrato'],
      'tipo_persona'=>$tpersona['tipo_persona']
  	);
  }else if($tipo['id_contrato']==1075){
    $e = $clase->get_contrato_actual_by_persona($id_persona);

  	$response = array(
  	  'id_persona'=>$e['id_persona'],
      'id_empleado'=>$e['id_empleado'],
  	  'id_secretaria_funcional'=>(!empty($e['id_secretaria_funcional']))?$e['id_secretaria_funcional']:'Sin asignación',
  	  'id_subsecretaria_funcional'=>(!empty($e['id_subsecretaria_funcional']))?$e['id_subsecretaria_funcional']:'-- -- -- --',
  	  'id_direccion_funcional'=>(!empty($e['id_direccion_funcional']))?$e['id_direccion_funcional']:'-- -- -- --',
  		'secretariaf'=>(!empty($e['secretariaf']))?$e['secretariaf']:'-- -- -- --',
  		'subsecretariaf'=>(!empty($e['subsecretariaf']))?$e['subsecretariaf']:'-- -- -- --',
  		'direccionf'=>(!empty($e['direccionf']))?$e['direccionf']:'-- -- -- --',
  		'subdireccionf'=>(!empty($e['subdireccionf']))?$e['subdireccionf']:'-- -- -- --',
  		'departamentof'=>(!empty($e['departamentof']))?$e['departamentof']:'-- -- -- --',
  		'seccionf'=>(!empty($e['seccionf']))?$e['seccionf']:'-- -- -- --',
  		'puestof'=>(!empty($e['puestof']))?$e['puestof']:'-- -- -- --',
      //'estado_empleado'=>'1',
      'estado'=>$e['emp_estado'],
      'tipo_contrato'=>$tipo['id_contrato'],
      'fecha_contrato'=>fecha_dmy($e['fecha_contrato']),
      'fecha_inicio'=>fecha_dmy($e['fecha_inicio']),
      'fecha_finalizacion'=>fecha_dmy($e['fecha_finalizacion']),
      'nro_acuerdo_aprobacion'=>$e['nro_acuerdo_aprobacion'],
      'fecha_acuerdo_aprobacion'=>fecha_dmy($e['fecha_acuerdo_aprobacion']),
      'nro_acuerdo_resicion'=>$e['nro_acuerdo_resicion'],
      'fecha_acuerdo_resicion'=>fecha_dmy($e['fecha_acuerdo_resicion']),
      'fecha_efectiva_resicion'=>fecha_dmy($e['fecha_efectiva_resicion']),
      'monto_contrato'=>number_format($e['monto_contrato'],2,'.',','),
      'monto_mensual'=>number_format($e['monto_mensual'],2,'.',','),
      'id_puesto_servicio'=>$e['id_puesto_servicio'],
      'tipo'=>$tipo['id_contrato'],
      'renglon'=>'029',
      'tipo_persona'=>$tpersona['tipo_persona']
  	);
  }

	echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
