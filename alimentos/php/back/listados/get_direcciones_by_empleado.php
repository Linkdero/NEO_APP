<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $id_persona=$_POST['id_persona'];
    $direcciones = array();
    $direcciones = empleado::get_direcciones_by_empleado($id_persona);
    $data = array();

    foreach ($direcciones as $d){

      $reside='NO';
      if($d['flag_actual']==1){
        $reside='SI';
      }

      $sub_array = array(
        'id_direccion'=>$d['id_direccion'],
        'referencia'=>$d['referencia'],
        'reside'=>$reside,
        'nro_calle_avenida'=>$d['nro_calle_avenida'],
        'tope'=>'',
        'tipo_calle_desc'=>$d['tipo_calle_desc'],
        'nro_casa'=>$d['nro_casa'],
        'zona'=>$d['zona'],
        'departamento'=>$d['departamento'],
        'municipio'=>$d['municipio'],
        'lugar'=>$d['lugar'],
        'tipo_lugar'=>''


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
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
