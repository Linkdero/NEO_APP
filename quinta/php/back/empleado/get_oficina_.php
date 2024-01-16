<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    ?>
    <?php

    $id_persona = $_POST['id_puerta'];
    $oficinas = visita::get_oficinas($id_persona);
    $response = "";
    $response .= "<option value='0'>Todos</option>";
    foreach($oficinas as $oficina){

      $response .="<option value=".$oficina['id_oficina'].">".$oficina['nombre_oficina']."</option>";
    }
    echo $response;
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
