<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $data = array();

    $plani_au = (evaluar_flag($_SESSION['id_persona'],8017,311,'flag_autoriza')==1) ? true : false;
    $plani_rev = (evaluar_flag($_SESSION['id_persona'],8017,311,'flag_actualizar')==1) ? true : false;
    $ssa_au = (evaluar_flag($_SESSION['id_persona'],8017,308,'flag_autoriza')==1) ? true : false;

    $compras_au = (evaluar_flag($_SESSION['id_persona'],8017,302,'flag_autoriza')==1) ? true : false;
    $compras_recepcion = (evaluar_flag($_SESSION['id_persona'],8017,302,'flag_insertar')==1) ? true : false;
    $compras_tecnico = (evaluar_flag($_SESSION['id_persona'],8017,302,'flag_actualizar')==1) ? true : false;
    $compras_asignar_tecnico = (evaluar_flag($_SESSION['id_persona'],8017,318,'flag_actualizar')==1) ? true : false;

    $directorf_au = (evaluar_flag($_SESSION['id_persona'],8017,306,'flag_autoriza')==1) ? true : false;
    $tesoreria_au = (evaluar_flag($_SESSION['id_persona'],8017,324,'flag_autoriza')==1) ? true : false;

    $data = array(
      'plani_au'=>$plani_au,
      'plani_rev'=>$plani_rev,

      'ssa_au'=>$ssa_au,

      'compras_au'=>$compras_au,
      'compras_recepcion'=>$compras_recepcion,
      'compras_tecnico'=>$compras_tecnico,
      'compras_asignar_tecnico'=>$compras_asignar_tecnico,

      'directorf_au'=>$directorf_au,
      'tesoreria_au'=>$tesoreria_au
    );

    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
