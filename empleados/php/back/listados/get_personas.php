<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $empleados = array();
    $empleados=empleado::get_empleados();
    $data = array();

    foreach($empleados as $e){
      $sub_array = array(
        'id_persona'=>$e['id_persona'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
      );
      $data[]=$sub_array;
    }

    $output = array(
      "data"    => $data
    );


    echo json_encode($empleados);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
