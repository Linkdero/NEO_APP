<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $plazas = array();
    $plazas=empleado::get_plazas_estado();
    $data = array();

    foreach($plazas as $e){
      $f_e='';
      if($e['fecha_toma_posesion']!=''){
        $f_e=fecha_dmy($e['fecha_toma_posesion']);
      }
      $sub_array = array(
        'gafete'=>$e['id_persona'],
        'partida'=>$e['partida_presupuestaria'],
        'cod_plaza'=>$e['cod_plaza'],
        'puesto'=>$e['Puesto_presupuestario'],
        'direccion'=>$e['nombre_direccion_presupuestaria'],
        'estado'=>$e['estado_plaza'],
        'renglon'=>$e['Renglon'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'fecha_efectiva'=>$f_e,
        'sueldo'=>$e['monto_sueldo_plaza'],
        'accion'=>'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="empleados/php/front/plazas/plaza_historial?partida='.$e['id_plaza'].'"><i class="fa fa-check"></i></span>'

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
