<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';

    $opcion=$_POST['opcion'];

    $estados = viaticos::get_estado_viatico($opcion);

    $response="";
    foreach($estados as $d){
        $response .="<option value='".$d['id_item']."'>".$d['descripcion']."</option>";
    }

    echo $response;
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
