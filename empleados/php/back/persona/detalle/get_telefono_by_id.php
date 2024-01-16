<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    $id_telefono=$_GET['id_telefono'];
    $t = array();
    $t = empleado::get_telefono_by_id($id_telefono);
    $data = array();

    $data = array(

      'id_persona'=>$t['id_persona'],
      'id_telefono'=>$t['id_telefono'],
      'flag_privado'=>($t['flag_privado'] == 1) ? true : false,
      'flag_activo'=>($t['flag_activo'] == 1) ? true : false,
      'flag_principal'=>($t['flag_principal'] == 1) ? true : false,
      'tipo'=>$t['tipo'],
      'id_tipo_telefono'=>$t['id_tipo_telefono'],
      'nro_telefono'=>$t['nro_telefono'],
      'observaciones'=>$t['observaciones'],
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
