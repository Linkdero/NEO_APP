<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $plazas = array();
    $plazas=empleado::get_plazas_estado();
    $data = array();
    $flag=0;
    if(evaluar_flag($_SESSION['id_persona'],1163,87,'flag_actualizar')==1){
      $flag=1;
    }
    foreach($plazas as $e){

      $f_e='';
      $agregar='';
      if($e['cod_estado_plaza']==890){
        $agregar.='<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lg" href="empleados/php/front/plazas/asignar_plaza?partida='.$e['id_plaza'].'"><i class="fa fa-plus-circle"></i></span>';
      }else if($e['cod_estado_plaza']==889){
        $agregar.='<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="empleados/php/front/puestos/puesto_por_persona?id_persona='.$e['id_persona'].'"><i class="fa fa-user"></i></span>';
      }

      $agregar.='<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="empleados/php/front/plazas/plaza_nueva?id_plaza='.$e['id_plaza'].'&opcion=2"><i class="fa fa-pen"></i></span>';
      $sub_array = array(
        'id_plaza'=>$e['id_plaza'],
        'gafete'=>$e['id_persona'],
        'partida'=>$e['partida_presupuestaria'],
        'cod_plaza'=>$e['cod_plaza'],
        'puesto'=>$e['Puesto_presupuestario'],
        'direccion'=>$e['nombre_direccion_presupuestaria'],
        'estado'=>$e['estado_plaza'],
        'cod_estado_plaza'=>$e['cod_estado_plaza'],
        'renglon'=>$e['Renglon'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'fecha_efectiva'=>(!empty($e['fecha_toma_posesion']))?$e['fecha_toma_posesion']:'',
        'sueldo'=>$e['monto_sueldo_plaza'],
        'flag'=>$flag,
        'accion'=>'<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lg" href="empleados/php/front/plazas/plaza_historial?partida='.$e['id_plaza'].'"><i class="fa fa-check"></i></span>'.$agregar.'</div>'

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
