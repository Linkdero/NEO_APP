<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $data = array();

    $reclu = (evaluar_flag($_SESSION['id_persona'],1163,38,'flag_actualizar')==1) ? true : false;
    $acciones = (evaluar_flag($_SESSION['id_persona'],1163,175,'flag_actualizar')==1) ? true : false;
    $nominas = (evaluar_flag($_SESSION['id_persona'],1163,167,'flag_actualizar')==1) ? true : false;
    $asuntos = (evaluar_flag($_SESSION['id_persona'],1163,280,'flag_actualizar')==1) ? true : false;
    $bienestar = (evaluar_flag($_SESSION['id_persona'],1163,206,'flag_actualizar')==1) ? true : false;
    $desarrollo = (evaluar_flag($_SESSION['id_persona'],1163,146,'flag_actualizar')==1) ? true : false;


    $data = array(
      'reclu'=>$reclu,
      'acciones'=>$acciones,
      'nominas'=>$nominas,
      'asuntos'=>$asuntos,
      'bienestar'=>$bienestar,
      'desarrollo'=>$desarrollo
    );

    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
