<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');


    $cupon_ini = $_GET['cupon_ini'];
    $cupon_fin = $_GET['cupon_fin'];

    $estado = 1913;
    $clase= new vehiculos();
    $cupones = array();
    $cupones = $clase->get_cupones_disponibles($cupon_ini,$cupon_fin);
    $data = array();

    foreach ($cupones as $c){
      $sub_array = array(
        'id_cupon'=>$c['id_cupon'],
        'nro_cupon'=>$c['nro_cupon'],
        'monto'=>$c['monto']
      );

      $data[]=$sub_array;
    }
  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
