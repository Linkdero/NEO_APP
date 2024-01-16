<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $array_dir = array();
    $array_dir = alimentos::get_dir_empleado($_SESSION['id_persona']);
    $secre = $array_dir['id_secre'] ;
    $subsecre = $array_dir['id_subsecre'] ;
    $direc = $array_dir['id_direc'] ;
    $tipo_user = $array_dir['tipo_user'];
    $empleados = array();

    if($tipo_user==1118){  // administrador
        $empleados = alimentos::get_empleados_excepcionesGen();
      }else{        // operador
        $empleados = alimentos::get_empleados_excepcionesxDir($secre,$subsecre,$direc);
      }
    
      $data = array();

    foreach ($empleados as $e){
      $link='';
      //$conteo=verificar_modulos_asignados_persona($e['id_persona']);
      $accion='';

      $accion.='<div class="btn-group btn-group-sm" role="group">
      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group mr-2" role="group" aria-label="Second group">';
      $accion.='<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="alimentos/php/front/empleados/excepxEmpleado.php?id_persona='.$e['id_persona'].'"><i class="fa fa-user-edit"></i></span>';
      //$accion.='<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="empleados/php/front/empleados/empleado_historial_plazas.php?id_persona='.$e['id_persona'].'"><i class="fa fa-file"></i></span>';
      $accion.='</div></div></div>';

      $chk1='';
        $chk1.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_persona'].'-1" class="custom-control-input" ';
        $chk1.='data-tipe="'.$e['id_persona'].'-1" data-id="1" ';
        if($e['tieneex']==1){
          $chk1.='checked';
        }
        $chk1.=' disabled name="'.$e['id_persona'].'" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_persona'].'-1"></label>
        </div>';

      $sub_array = array(
        'id_persona'=>$e['id_persona'],
        'empleado'=>$e['nombre'],
        'del'=>$e['fecha1'],
        'al'=>$e['fecha2'],
        'tieneex'=>$chk1,
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
