<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    $id_documento=$_GET['id_documento'];
    $d = array();
    $d = empleado::get_documento_by_id($id_documento);
    $data = array();

    $data = array(
      'id_persona'=>$d['id_persona'],
      'id_documento'=>$d['id_documento'],
      'id_tipo_identificacion'=>$d['id_tipo_identificacion'],
      'id_tipo_documento'=>$d['id_tipo_documento'],
      'nro_registro'=>$d['nro_registro'],
      'fecha_vencimiento'=>date('Y-m-d', strtotime($d['fecha_vencimiento'])),
      'departamento'=>$d['id_departamento'],
      'municipio'=>$d['id_municipio'],
      'lugar'=>$d['id_aldea'],
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
