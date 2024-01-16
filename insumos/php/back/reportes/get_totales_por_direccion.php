<?php
include_once '../../../../inc/functions.php';
include_once '../../../../empleados/php/back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $tipo=$_POST['tipo'];
    set_time_limit(0);
    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
    }

    $totales = array();
    $totales = insumo::get_totales_por_direccion_nuevo($bodega);
    $data = array();

    foreach ($totales as $t){
 
      $sub_array = array(
        'direccion'=>$t['DIRECCION'],
        'total_ap'=>$t['a_p'],
        'total_at'=>$t['a_t']
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
