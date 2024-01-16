<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $acceso=$_POST['acceso'];
    $persona=array();
    $persona=configuracion::get_persona_by_acceso($acceso);
    $data = array();




      $data = array(
        'id_persona'=>$persona['id_persona'],
        'id_acceso'=>$persona['id_acceso'],
        'empleado'=>$persona['nombre']

      );





    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
