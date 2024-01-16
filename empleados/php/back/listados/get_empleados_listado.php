<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $empleados = array();
    $empleados = empleado::get_empleados();
    $data = array();

    foreach ($empleados as $e){
      $link='';
      //$conteo=verificar_modulos_asignados_persona($e['id_persona']);
      $accion='';


      /*$accion.='<div class="btn-group btn-group-sm" role="group">
      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group mr-2" role="group" aria-label="Second group">';
      $accion.='<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="empleados/php/front/empleados/empleado.php?id_persona='.$e['id_persona'].'"><i class="fa fa-user-edit"></i></span>';
      //$accion.='<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="empleados/php/front/empleados/empleado_historial_plazas.php?id_persona='.$e['id_persona'].'"><i class="fa fa-file"></i></span>';
      $accion.='<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="empleados/php/front/puestos/puesto_por_persona?id_persona='.$e['id_persona'].'"><i class="fa fa-check"></i></span>';
      $accion.='</div></div></div>';*/

      $status='';
      /*if($e['estado_persona']==1){
        $status.='<span class="badge badge-success">Activo.</span>';
      }else if($e['estado_persona']==0){
        $status.='<span class="badge badge-danger">Inactivo</span>';
      }*/

      $sub_array = array(
        'id_persona'=>$e['id_persona'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'nit'=>$e['nit'],
        'igss'=>$e['afiliacion_IGSS'],
        'email'=>$e['correo_electronico'],
        'descripcion'=>$e['descripcion'],
        'status'=>$status,
        'estado'=>$e['estado_persona'],
        'nisp'=>$e['NISP'],
        'f_ingreso'=>$e['fecha_inicio'],
        'f_baja'=>$e['fecha_baja'],
        //'accion'=>$accion
      );

      $data[]=$sub_array;
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
