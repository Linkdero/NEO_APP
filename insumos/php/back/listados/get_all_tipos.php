<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $bodega = $datos['bodega'];

    $tipos = array();
    $tipos = insumo::get_all_tipo_insumos_by_bodega($bodega);
    $data = array();

    foreach ($tipos as $t){


      $sub_array = array(
        'codigo'=>$t['id_tipo_insumo'],
        'descripcion'=>$t['descripcion']
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
