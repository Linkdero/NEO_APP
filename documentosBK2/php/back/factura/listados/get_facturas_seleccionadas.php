<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();

if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    //include_once '../../../../empleados/php/back/functions.php';
    include_once '../../functions.php';

    $arreglo = "(".substr($_GET['arreglo'],1).")";

    $clased = new documento;

    $data = array();

    $facturas = array();
    $facturas = $clased->get_facturas_seleccionadas($arreglo);

    foreach ($facturas as $f) {
      // code...
      $sub_array = array(
        'serie'=>$f['serie'],
        'factura'=>$f['factura'],
        'monto'=>$f['monto'],
      );
      $data[] = $sub_array;
    }


    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
