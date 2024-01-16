<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $partida=$_POST['partida'];
    $plazas = array();
    $plazas=empleado::get_plaza_historial($partida);
    $data = array();

    foreach($plazas as $e){
      $f_r='';
      if($e['fecha_efectiva_resicion']!=''){
        $f_r=fecha_dmy($e['fecha_efectiva_resicion']);
      }
      $sub_array = array(
        //'plaza'=>$e['partida_presupuestaria'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'fecha_toma'=>fecha_dmy($e['fecha_toma_posesion']),
        'fecha_resecion'=>$f_r
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
