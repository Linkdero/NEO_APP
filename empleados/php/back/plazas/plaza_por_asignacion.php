<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions_plaza.php';

    $plazas = array();
    $id_asignacion='';
  	if(isset($_GET['id_asignacion'])){
  		$id_asignacion=$_GET['id_asignacion'];
  	}
    $p=plaza::get_plazas_por_asignacion($id_asignacion);
    $data = array();

    $data = array(
      'id_asignacion'=>$p['id_asignacion'],
      'fecha'=>$p['fecha'],
      'descripcion'=>$p['descripcion'],
      'id_empleado'=>$p['id_empleado'],
      'id_plaza'=>$p['id_plaza'],
      'fecha_toma_posesion'=>date('Y-m-d', strtotime($p['fecha_toma_posesion'])),
      'fecha_acuerdo'=>date('Y-m-d', strtotime($p['fecha_acuerdo'])),
      'nro_acuerdo'=>$p['nro_acuerdo'],
      'fecha_acuerdo_baja'=>date('Y-m-d', strtotime($p['fecha_acuerdo_baja'])),
      'nro_acuerdo_baja'=>$p['nro_acuerdo_baja'],
      'fecha_efectiva_resicion'=>date('Y-m-d', strtotime($p['fecha_efectiva_resicion'])),
      'id_status'=>$p['id_status'],
      'id_auditori'=>$p['id_auditoria']
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
