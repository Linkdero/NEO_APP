<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $estado=$_POST['estado'];
    $tipo=0;
    set_time_limit(0);
    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
    }

    $totales = array();
    $totales = insumo::get_totales_marca_estado_by_desc($bodega,$tipo,$estado);
    $data = array();

    foreach ($totales as $t){
      $total=0;
      $sub_array = array(
        'estado'=>$t['estado'],
        'MOTOROLA'=>$t['MOTOROLA'],
        'CHICOM'=>$t['Chicom'],
        'HYTERA'=>$t['HYTERA'],
        'HYT'=>$t['HYT'],
        'BOAFENG'=>$t['BOAFENG'],
        'KENWOOD'=>$t['KENWOOD'],
        'VERTEX'=>$t['VERTEX'],
        'TOTAL'=>$total+$t['MOTOROLA']+$t['Chicom']+$t['HYTERA']+$t['HYT']+$t['BOAFENG']+$t['KENWOOD']+$t['VERTEX']
      );

      $data[]=$sub_array;
    }

  echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
