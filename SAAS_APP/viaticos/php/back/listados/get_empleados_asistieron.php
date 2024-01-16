<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $nombramiento=$_GET['vt_nombramiento'];
    $empleados = array();
    $empleados = viaticos::get_empleados_asistieron($nombramiento);

    $data = array();
    foreach ($empleados as $e){

        $sub_array = array(
          'id_empleado'=>$e['id_empleado'],
          'foto'=>$e['id_empleado'],
          'nombre'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
          'accion' => ''
        );
        $data[] = $sub_array;
        //$data[]=$e;

    }

  /*$results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );*/

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
