<?php
include_once '../../../../../inc/functions.php';

sec_session_start();
$u = usuarioPrivilegiado();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../../empleados/php/back/functions.php';
    include_once '../../functions.php';

    $id_persona=$_SESSION['id_persona'];

    $borrar = array('"', '[', ']');
    $arreglo = str_replace($borrar, "", $_GET['array']);
    //echo $onlyconsonants;
    $pedidos = array();
    if(!empty($arreglo)){
      $pedidos = documento::get_pedidos_seleccionados('('.$arreglo.')');



    }

    echo json_encode($pedidos);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
