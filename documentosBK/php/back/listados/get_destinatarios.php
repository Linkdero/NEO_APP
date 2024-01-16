<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $destinatarios = documento::get_destinatarios();

    $data = array();

    if($destinatarios["status"] == 200){
        $response = "";
        $sub_array = array(
          'dest_id'=>'',
          'dest_string'=>'- Seleccionar -'
        );
        $data[] = $sub_array;
        foreach($destinatarios["data"] as $d){
            //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
            $sub_array = array(
              'dest_id'=>$d['id_direccion'],
              'dest_string'=>$d['descripcion'].' - '.$d['descripcion_corta']
            );
            $data[] = $sub_array;
        }

    }else{
        $response = $destinatarios["msg"];
    }



  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
