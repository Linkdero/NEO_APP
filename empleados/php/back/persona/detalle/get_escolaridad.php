<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_plaza.php';
    $id_escolaridad=$_GET['id_escolaridad'];
    $escolaridad = array();
    $escolaridad=empleado::get_nivel_academico_by_id($id_escolaridad);
    $data = array();

    $data = array(
      'id_escolaridad'=>$escolaridad['id_escolaridad'],
      'id_persona'=>$escolaridad['id_persona'],
      'flag_terminado'=>$escolaridad['flag_terminado'],
      'id_grado_academico'=>$escolaridad['id_grado_academico'],
      'ano_grado_academico'=>$escolaridad['ano_grado_academico'],

      'id_establecimiento'=>$escolaridad['id_establecimiento'],
      'id_titulo_obtenido'=>$escolaridad['id_titulo_obtenido'],
      'fecha_titulo'=>date('Y-m-d', strtotime($escolaridad['fecha_titulo'])),
      'nro_colegiado'=>$escolaridad['nro_colegiado'],
      'fec_venc_colegiado'=>date('Y-m-d', strtotime($escolaridad['fec_venc_colegiado'])),
      'observaciones'=>$escolaridad['observaciones'],
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
