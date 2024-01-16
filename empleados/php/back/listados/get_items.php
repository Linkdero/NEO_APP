<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';


    $clase= new empleado();
    $id_catalogo = $_GET['id_catalogo'];
    $tipo=$_GET['tipo'];

    $items = $clase->get_items($id_catalogo,$tipo);

    $data = array();

    if($items["status"] == 200){
        $response = "";
        $sub_array = array(
          'id_item'=>'',
          'item_string'=>'- Seleccionar -'
        );
        $data[] = $sub_array;
        foreach($items["data"] as $i){
            //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
            $sub_array = array(
              'id_item'=>$i['id_item'],
              'item_string'=>$i['descripcion']
            );
            $data[] = $sub_array;
        }
        if($tipo == 5){
          //finalizar contrato  031
          $sub_array = array(
            'id_item'=>'9999999',
            'item_string'=>'f) Finalizar Contrato'
          );
          $data[] = $sub_array;
        }
    }else{
        $response = $items["msg"];
    }



  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
