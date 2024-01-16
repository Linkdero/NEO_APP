<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    $id_persona=$_GET['id_persona'];
    $direcciones = array();
    $direcciones = empleado::get_direcciones_by_empleado($id_persona);
    $data = array();

    foreach ($direcciones as $d){

      $arreglo = array(
        'id_direccion'=>$d['id_direccion'],
        'id_tipo_referencia'=>$d['id_tipo_referencia'],

        'flag_actual'=>$d['Direccion_actual'],
        'nro_calle_avenida'=>$d['nro_calle_avenida'],
        'calle_tope'=>$d['calle_tope'],
        'id_tipo_calle'=>$d['tipo_calle'],
        'nro_casa'=>$d['nro_casa'],
        'id_zona'=>$d['zona'],
        'nro_apto_oficina'=>$d['nro_apto_oficina'],
        'id_departamento'=>$d['id_depto'],
        'id_municipio'=>$d['id_muni'],
        'id_lugar'=>$d['id_aldea'],
        'tipo_lugar'=>$d['id_tipo_lugar_poblado'],
        'observaciones'=>$d['observaciones']
      );
      $sub_array = array(
        'id_direccion'=>$d['id_direccion'],
        'referencia'=>$d['nombre_tipo_referencia'],
        'reside'=>$d['Reside'],
        'nro_calle_avenida'=>$d['nro_calle_avenida'],
        'calle_tope'=>$d['calle_tope'],
        'nombre_tipo_calle'=>$d['nombre_tipo_calle'],
        'nro_casa'=>$d['nro_casa'],
        'zona'=>$d['zona'],
        'departamento'=>$d['nombre_departamento'],
        'municipio'=>$d['nombre_municipio'],
        'lugar'=>$d['nombre_aldea'],
        'tipo_lugar'=>$d['nombre_tipo_lugar_poblado'],
        'arreglo'=>$arreglo


      );

      $data[]=$sub_array;
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
