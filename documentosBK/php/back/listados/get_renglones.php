<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $data = array();

    $items = documento::get_renglones_pac();

    if($items["status"] == 200){
        $response = "";
        $sub_array = array(
          'id_item'=>'',
          'item_string'=>'- Seleccionar -'
        );
        $data[] = $sub_array;
        foreach($items["data"] as $i){
          if(is_numeric($i['id_renglon'])){
            $sub_array = array(
              'id_item'=>$i['id_renglon'],
              'item_string'=>$i['id_renglon'].' - '.$i['nombre']
            );
            $data[] = $sub_array;
          }
        }
    }else{
        $response = $items["msg"];
    }



  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
