<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $id_persona=$_POST['id_persona'];
    $telefonos = array();
    $telefonos = empleado::get_telefonos_by_empleado($id_persona);
    $data = array();

    foreach ($telefonos as $t){


      $sub_array = array(
        'id_telefono'=>$t['id_telefono']


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
