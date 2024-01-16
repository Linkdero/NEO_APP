<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $escolaridad = array();
    $escolaridad=empleado::get_nivel_academico($id_persona);
    $data = array();

    foreach($escolaridad as $e){

      $arreglo = array(
        'id_escolaridad'=>$e['id_escolaridad'],
        'id_persona'=>$e['id_persona'],
        'flag_terminado'=>$e['flag_terminado'],
        'id_grado_academico'=>$e['id_grado_academico'],
        'ano_grado_academico'=>$e['ano_grado_academico'],

        'id_establecimiento'=>$e['id_establecimiento'],
        'id_titulo_obtenido'=>$e['id_titulo_obtenido'],
        'fecha_titulo'=>date('Y-m-d', strtotime($e['fecha_titulo'])),
        'nro_colegiado'=>$e['nro_colegiado'],
        'fec_venc_colegiado'=>date('Y-m-d', strtotime($e['fec_venc_colegiado'])),
        'establecimiento'=>$e['establecimiento'],
        'observaciones'=>$e['observaciones'],
      );

      $sub_array = array(
        'id_escolaridad'=>$e['id_escolaridad'],
        'nivel'=>$e['nivel'],
        'titulo'=>$e['titulo'],
        'nro_colegiado'=>$e['nro_colegiado'],
        'year'=>$e['ano_grado_academico'],
        'fecha_titulo'=>fecha_dmy($e['fecha_titulo']),
        'fec_venc'=>fecha_dmy($e['fec_venc']),
        'id'=>$e['id'],
        'id_ref'=>$e['id_ref'],
        'arreglo'=>$arreglo
      );
      $data[]=$sub_array;
    }

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
