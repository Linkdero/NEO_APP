<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';


    $clase= new viaticos();
    $item = $_GET['tipo'];
    $horas = $clase->get_items($item);

    $data = array();

    if($horas["status"] == 200){
        $response = "";
        $sub_array = array(
          'id_hora'=>'',
          'hora_string'=>'- Seleccionar -'
        );
        $data[] = $sub_array;
        foreach($horas["data"] as $hora){
            //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
            $sub_array = array(
              'id_hora'=>$hora['id_item'],
              'hora_string'=>$hora['descripcion_corta']
            );
            $data[] = $sub_array;
        }

    }else{
        $response = $hora["msg"];
    }



  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
