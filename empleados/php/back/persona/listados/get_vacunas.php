<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../functions.php';
  $id_persona = $_GET['id_persona'];

  $vacunas = empleado::get_vacunas_by_persona($id_persona);

  $data = array();

  foreach($vacunas as $v){
    $arreglo = array(
      'id_vacuna'=>$v['id_vacuna'],
      'id_dosis'=>$v['id_vacuna_dosis'],
      'tipo_vacuna'=>$v['id_vacuna_tipo'],
      'fecha_vacuna'=>date('Y-m-d', strtotime($v['fecha_vacunacion'])),
    );
    $id_vacuna_dosis = '';
    if($v['id_vacuna_dosis'] == 1){
      $id_vacuna_dosis = 'Primera Dosis';
    }else if($v['id_vacuna_dosis'] == 2){
      $id_vacuna_dosis = 'Segunda Dosis';
    }else if($v['id_vacuna_dosis'] == 3){
      $id_vacuna_dosis = 'Refuerzo';
    }
    $sub_array = array(
      'id_dosis'=>$id_vacuna_dosis,//($v['id_vacuna_dosis'] == 1) ? 'Primera Dosis' : 'Segunda Dosis',
      'tipo_vacuna'=>$v['tipo_vacuna'],
      'fecha_vacuna'=>fecha_dmy($v['fecha_vacunacion']),
      'arreglo'=>$arreglo
    );

    $data[]=$sub_array;
  }

  echo json_encode($data);
else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


  ?>
