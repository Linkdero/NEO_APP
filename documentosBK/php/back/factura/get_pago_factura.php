<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $orden = $_GET['orden_id'];

    $clased = new documento;

    $data = array();

    $tecnico = array();
    $tecnico = $clased->get_pago_by_factura($orden);

    $data = array(
      'orden_id'=>$tecnico['orden_compra_id'],
      'tecnico'=>(!empty($tecnico['tecnico'])) ? $tecnico['tecnico'] : 'No hay técnico asignado'
    );

    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
