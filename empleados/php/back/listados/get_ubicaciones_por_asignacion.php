<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    include_once '../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $ubicaciones = array();
    $ubicaciones=empleado::get_ubicaciones_por_asignacion($id_persona);
    $data = array();

    foreach($ubicaciones as $u){

      $sub_array = array(
        //'plaza'=>$e['partida_presupuestaria'],
        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'id_plaza'=>$u['id_plaza'],
        'id_asignacion'=>$u['id_asignacion'],
        'reng_num'=>$u['reng_num'],
        'cod_plaza'=>$u['cod_plaza'],
        's'=>$u['s'],
        'ss'=>$u['ss'],
        'dir'=>$u['dir'],
        'sdir'=>$u['sdir'],
        'dep'=>$u['dep'],
        'sec'=>$u['sec'],
        'puesto'=>$u['puesto'],
        'fecha_ini'=>$u['fecha_inicio'],
        'fecha_fin'=>$u['fecha_fin'],
        'acuerdo'=>$u['acuerdo'],
        'status'=>$u['status']


        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
      );
      $data[]=$sub_array;
    }

    $output = array(
      "data"    => $data
    );


    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
