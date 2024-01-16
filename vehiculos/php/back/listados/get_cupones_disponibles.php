<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');


    $estado = 1913;
    $clase= new vehiculos();
    $cupones = array();
    $cupones = $clase->get_cupones_disponibles_para_seleccion();
    $data = array();

    foreach ($cupones as $c){
      $sub_array = array(
        'id_item'=>$c['id_cupon'],
        'item_string'=>$c['nro_cupon'].' - '.number_format($c['monto'],2,'.',','),
        //'monto'=>
      );

      $data[]=$sub_array;
    }
  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
