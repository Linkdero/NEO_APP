<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../../../php/back/listados/functions.php';

    $empleados = array();
    $empleados = empleado::get_empleados_con_accesos();
    $data = array();

    foreach ($empleados as $e){
      $link='';
      //$conteo=verificar_modulos_asignados_persona($e['id_persona']);
      $accion='';


      $accion.='<span class="btn btn-personalizado outline"><i class="fa fa-check"></i></span>';

      $sub_array = array(
        'id_persona'=>$e['id_persona'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        /*'nit'=>$e['nit'],
        'igss'=>$e['afiliacion_IGSS'],
        'email'=>$e['correo_electronico'],
        'descripcion'=>$e['descripcion'],*/
        'status'=>$status,
        //'accesos'=>$e['conteo'],
        'accion'=>$accion
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
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
