<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';

  $pais=$_GET['pais'];
  $departamentos = viaticos::get_departamentos($pais);
  $data = array();
  if($departamentos["status"] == 200){
    $response = "";
    foreach($departamentos["data"] as $departamento){
      $sub_array = array(
        'id_hora'=>$departamento['id_departamento'],
        'hora_string'=>$departamento['nombre']
      );
      $data[] = $sub_array;
    }
  }else{
    $response = $departamentos["msg"];
  }




  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
