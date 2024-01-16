<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $modulo = $_GET['modulo'];
    $pantalla = $_GET['pantalla'];
    $data = array();
    $response = (evaluar_flag($_SESSION['id_persona'],$modulo,$pantalla,'flag_actualizar')==1) ? true : false;
    $data = array(
      'permiso'=>$response,
      'bloqueo'=>($response == true) ? false : true
    );

    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
