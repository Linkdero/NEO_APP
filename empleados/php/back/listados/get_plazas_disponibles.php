<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions_plaza.php';

    $plazas = array();
    $plazas=plaza::get_plazas_disponibles();
    $data = array();

    if($plazas["status"] == 200){
      $response = "";
      $sub_array = array(
        'id_plaza'=>'',
        'plaza_string'=>'- Seleccionar -'
      );
      $data[] = $sub_array;
      foreach($plazas["data"] as $p){
        //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
        $sub_array = array(
          'id_plaza'=>$p['id_plaza'],
          'plaza_string'=>$p['cod_plaza'].' -- '.$p['partida_presupuestaria'].' -- '.$p['descripcion']. ' -- '.$p['nombre_direccion_presupuestaria']
        );
        $data[] = $sub_array;
      }
    }else{
      $response = $items["msg"];
    }

      /*$sub_array = array(
        'id_plaza'=>$e['id_plaza'],
        'partida'=>$e['partida_presupuestaria'],
        'cod_plaza'=>$e['cod_plaza'],
        'puesto'=>$e['Puesto_presupuestario'],
        'direccion'=>$e['nombre_direccion_presupuestaria'],
        'estado'=>$e['estado_plaza'],
        'cod_estado_plaza'=>$e['cod_estado_plaza'],
        'renglon'=>$e['Renglon'],
        'sueldo'=>$e['monto_sueldo_plaza']

        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
      );
      $data[]=$sub_array;
*/

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
