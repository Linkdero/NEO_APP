<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    //include_once '../../../../empleados/php/back/functions.php';
    include_once '../../functions.php';

    $tipo = $_GET['tipo'];

    $clased = new documento;

    $data = array();

    $ordenes = array();
    $ordenes = $clased->get_ordenes_pendientes($tipo);

    foreach ($ordenes as $orden) {
      // code...
      $sub_array = array(
        'id_item'=>$orden['nro_orden'],
        'item_string'=>$orden['nro_orden']
      );
      $data[] = $sub_array;
    }


    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
