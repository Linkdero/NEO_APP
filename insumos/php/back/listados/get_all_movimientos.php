<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $bodega = 3552;

    $moves = array();
    $moves = insumo::get_all_movimientos_by_bodega($bodega);
    $data = array();

    foreach ($moves as $m){


      $sub_array = array(
        'movimiento'=>$m['tipo_movimiento'],
        'producto'=>$m['producto'],
        'fecha'=>$m['fecha']
      );

      $data[]=$sub_array;
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($results);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
