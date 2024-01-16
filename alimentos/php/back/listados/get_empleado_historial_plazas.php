<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $id_persona=$_POST['id_persona'];
    $plazas = array();
    $plazas=empleado::get_plazas_por_empleado($id_persona);
    $data = array();

    foreach($plazas as $e){
      $f_r='';
      if($e['fecha_efectiva_resicion']!=''){
        $f_r=fecha_dmy($e['fecha_efectiva_resicion']);
      }
      $sub_array = array(
        //'plaza'=>$e['partida_presupuestaria'],
        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'partida'=>$e['partida_presupuestaria'],
        'plaza'=>$e['id_plaza'],
        'puesto'=>$e['puesto'],
        'inicio'=>fecha_dmy($e['fecha_toma_posesion']),
        'final'=>$f_r,
        'sueldo'=>$e['SUELDO']
        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
      );
      $data[]=$sub_array;
    }

    $output = array(
      "data"    => $data
    );


    echo json_encode($output);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
