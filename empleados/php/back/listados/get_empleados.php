<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $empleados = array();
    $empleados = empleado::get_empleados_con_accesos();
    $data = array();

    foreach ($empleados as $e){
      $link='';
      //$conteo=verificar_modulos_asignados_persona($e['id_persona']);
      $accion='';


      $accion.='<div class="btn-group btn-group-sm" role="group">
      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group mr-2" role="group" aria-label="Second group">';

      if($e['conteo']>0){
        $accion.='<button class="btn btn-personalizado outline btn-sm"  data-toggle="modal" data-target="#modal-remoto-lg" href="configuracion/php/front/modulos/menu_empleados.php?id_persona='.$e['id_persona'].'&tipo=1" ><i class="fa fa-key"></i></button>';

         //onclick="obtener_accesos_por_persona('.$e['id_persona'].')"

      }else{
        if($e['estado_persona']==0){
          $accion.='No';
        }

      }
      if($e['estado_persona']==1){
        $accion.='<button class="btn btn-personalizado outline btn-sm"  data-toggle="modal" data-target="#modal-remoto-lg" href="configuracion/php/front/modulos/menu_empleados.php?id_persona='.$e['id_persona'].'&tipo=2" ><i class="fa fa-plus-circle"></i></button>';
        //$accion.='<button class="btn btn-personalizado outline btn-sm" onclick="obtener_accesos_pendiente_por_persona('.$e['id_persona'].')"><i class="fa fa-plus-circle"></i></button>';
      }
      $status='';
      if($e['estado_persona']==1){
        $status.='<span class="badge badge-success">Activo.</span>';
      }else if($e['estado_persona']==0){
        $status.='<span class="badge badge-danger">Inactivo</span>';
      }

      $accion.='</div></div></div>';

      $sub_array = array(
        'id_persona'=>$e['id_persona'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'nit'=>$e['nit'],
        'igss'=>$e['afiliacion_IGSS'],
        'email'=>$e['correo_electronico'],
        'descripcion'=>$e['descripcion'],
        'status'=>$status,
        'accesos'=>$e['conteo'],
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
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
