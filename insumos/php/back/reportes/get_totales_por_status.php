<?php
include_once '../../../../inc/functions.php';
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
    $totales = insumo::get_totales_marca_estado($bodega,$tipo);
    $data = array();

    if($bodega==3552){
      foreach ($totales as $t){
        $total=0;
        $sub_array = array(
          'MARCA'=>$t['MARCA'],
          'DISPONIBLE'=>$t['DISPONIBLE'],
          'ASIGNADO'=>$t['ASIGNADO'],
          'ASIGNADO TEMPORAL'=>$t['ASIGNADO TEMPORAL'],
          'EXTRAVIADO'=>$t['EXTRAVIADO'],
          'MAL ESTADO'=>$t['MAL ESTADO'],
          'TOTAL'=>$total+$t['DISPONIBLE']+$t['ASIGNADO']+$t['ASIGNADO TEMPORAL']+$t['EXTRAVIADO']+$t['MAL ESTADO']
        );
  
        $data[]=$sub_array;
      }

    }
    if($bodega==5907){
      foreach ($totales as $t){
        $total=0;
        $sub_array = array(
          'ESTADO'=>$t['ESTADO'],
          'HUAWEI'=>$t['HUAWEI'],
          'IPHONE'=>$t['IPHONE'],
          'SAMSUNG'=>$t['SAMSUNG'],
          'TOTAL'=>$total+$t['HUAWEI']+$t['IPHONE']+$t['SAMSUNG']
        );
  
        $data[]=$sub_array;
      }

    }

    if($bodega==5066){
      foreach ($totales as $t){
        $total=0;
        $sub_array = array(
          'ESTADO'=>$t['ESTADO'],
          'DAEWOO'=>$t['DAEWOO'],
          'UZI'=>$t['UZI'],
          'KALASHNIKOV'=>$t['KALASHNIKOV'],
          'MEPOR21'=>$t['MEPOR21'],
          'EAGLE'=>$t['EAGLE'],
          'ROSSI'=>$t['ROSSI'],
          'GENERICO'=>$t['GENERICO'],
          'DESANTIS'=>$t['DESANTIS'],
          'GALIL'=>$t['GALIL'],
          'CAA'=>$t['CAA'],
          'VALTRO'=>$t['VALTRO'],
          'FNHERSTAL'=>$t['FNHERSTAL'],
          'JERICHO'=>$t['JERICHO'],
          'TAURUS'=>$t['TAURUS'],
          'IMI'=>$t['IMI'],
          'TANFOGLIO'=>$t['TANFOGLIO'],
          'FOBUS'=>$t['FOBUS'],
          'SM'=>$t['SM'],
          'BLACKHAWK'=>$t['BLACKHAWK'],
          'STOEGER'=>$t['STOEGER'],
          'BERETTA'=>$t['BERETTA'],
          'CZ'=>$t['CZ'],
          'BUSHNELL'=>$t['BUSHNELL'],
          'ANAJMAN'=>$t['ANAJMAN'],
          'SIGPRO'=>$t['SIGPRO'],
          'TAVOR'=>$t['TAVOR'],
          'SEGARMOR'=>$t['SEGARMOR'],
          'COLT'=>$t['COLT'],
          'GLOCK'=>$t['GLOCK'],
          'REMINGTON'=>$t['REMINGTON'],
          'TOTAL'=>$total+$t['DAEWOO']+$t['UZI']+$t['KALASHNIKOV']+$t['MEPOR21']+$t['EAGLE']+$t['ROSSI']+$t['GENERICO']+$t['DESANTIS']+$t['GALIL']+$t['CAA']+$t['VALTRO']+$t['FNHERSTAL']+$t['JERICHO']+$t['TAURUS']+$t['IMI']+$t['TANFOGLIO']+$t['FOBUS']+$t['SM']+$t['BLACKHAWK']+$t['STOEGER']+$t['BERETTA']+$t['CZ']+$t['BUSHNELL']+$t['ANAJMAN']+$t['SIGPRO']+$t['TAVOR']+$t['SEGARMOR']+$t['COLT']+$t['GLOCK']+$t['REMINGTON']
        );
  
        $data[]=$sub_array;
      }

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
