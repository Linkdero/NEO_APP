<?php

include_once '../../../../inc/functions.php';


sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');

  include_once '../functions.php';



	$response = array();
	$id_persona='';
	if(isset($_GET['id_persona'])){
		$id_persona=$_GET['id_persona'];
	}

  $clase_e = new empleado;


  $tipo= $clase_e->get_tipo_contrato_by_empleado($id_persona);

  if($tipo['id_contrato']==7){
    include_once '../functions_plaza.php';
    $clase = new plaza;
    $e = $clase->get_empleado_plaza_actual($id_persona);

  	$response = array(
      'id_plaza'=>(!empty($e['id_plaza']))?$e['id_plaza'] :'Sin asignación',
  	  'id_persona'=>(!empty($e['id_persona']))?$e['id_persona'] :'Sin asignación',
      'id_asignacion'=>(!empty($e['id_asignacion']))? $e['id_asignacion']:'Sin asignación',
      'nro_acuerdo'=>(!empty($e['nro_acuerdo']))? $e['nro_acuerdo']:'Sin asignación',
      'cod_plaza'=>(!empty($e['cod_plaza']))? $e['cod_plaza']:'Sin asignación',
      'partida'=>(!empty($e['partida_presupuestaria']))?$e['partida_presupuestaria'] :'Sin asignación',
      'fecha_toma_posesion'=>(!empty($e['fecha_toma_posesion']))?fecha_dmy($e['fecha_toma_posesion']):'Sin asignación',
      'fecha_acuerdo_baja'=>(!empty($e['fecha_acuerdo_baja']))?$e['fecha_acuerdo_baja'] :'Sin asignación',
      'fecha_efectiva_resicion'=>(!empty($e['fecha_efectiva_resicion']))? $e['fecha_efectiva_resicion']:'Sin asignación',
      'f_acuerdo'=>date('Y-m-d', strtotime($e['fecha_acuerdo'])),
      'f_toma'=>date('Y-m-d', strtotime($e['fecha_toma_posesion'])),
      'desc_asignacion'=>$e['desc_asignacion'],
      'estado'=>$e['emp_estado'],
      'emp_estado'=>$e['emp_estado'],
      'tipo'=>$tipo['id_contrato'],
      'renglon'=>'011',
      'load'=>true//(!empty($e['emp_estado']))? $e['emp_estado']:'Sin asignación'
  	);

  }else{
    include_once '../functions_contratoS.php';
    $clase = new contrato;
    $e = $clase->get_empleado_contrato_actual($id_persona);

  	$response = array(
      'id_plaza'=>(!empty($e['reng_num']))?$e['reng_num'] :'Sin asignación',
  	  'id_persona'=>(!empty($e['id_persona']))?$e['id_persona'] :'Sin asignación',
  	  'nombre'=>'',//(!empty($e['primer_nombre']))?$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'] :'Sin asignación',
      'cod_plaza'=>(!empty($e['nro_contrato']))? $e['nro_contrato']:'Sin asignación',
      'partida'=>'',//(!empty($e['partida_presupuestaria']))?$e['partida_presupuestaria'] :'Sin asignación',
      'fecha_toma_posesion'=>(!empty($e['fecha_inicio']))?fecha_dmy($e['fecha_inicio']):'Sin asignación',
      'fecha_acuerdo_baja'=>'',//(!empty($e['fecha_acuerdo_baja']))?$e['fecha_acuerdo_baja'] :'Sin asignación',
      'fecha_efectiva_resicion'=>(!empty($e['fecha_acuerdo_resicion']))? $e['fecha_acuerdo_resicion']:'Sin asignación',
      'estado'=>$e['emp_estado'],
      'emp_estado'=>$e['emp_estado'],
      'tipo'=>$tipo['id_contrato'],
      'renglon'=>$e['renglon'],
      'fecha_inicio'=>(!empty($e['fecha_inicio']))?fecha_dmy($e['fecha_inicio']):'Sin asignación',
      'fecha_fin'=>(!empty($e['fecha_finalizacion']))?fecha_dmy($e['fecha_finalizacion']):'Sin asignación',
      'load'=>true

  	);
  }


	echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
