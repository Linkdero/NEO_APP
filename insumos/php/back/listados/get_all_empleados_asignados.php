<?php
include_once '../../../../inc/functions.php';
include_once '../../../../empleados/php/back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $empleados = array();
    $clase= new insumo();
    $datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
    $bodega=0;
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
    }
    $empleados = insumo::get_empleados_ficha_asignados($bodega);
    $data = array();

    foreach ($empleados as $e){
      $link='';
      //$conteo=verificar_modulos_asignados_persona($e['id_persona']);
      $accion='';


      $direccion =$e['dir_funcional'];
      if($e['id_tipo']==2){
        $direccion=$e['dir_nominal'];
      }else
      if($e['id_tipo']==4){
        $dir_ = empleado::get_direcciones_saas_by_id($e['id_dirapy']);
        $direccion=$dir_['descripcion'];
      }

      $status='';
      /*if($e['estado_persona']==1){
        $status.='<span class="badge badge-success">Activo.</span>';
      }else if($e['estado_persona']==0){
        $status.='<span class="badge badge-danger">Inactivo</span>';
      }*/

      $sub_array = array(
        'id_persona'=>$e['id_persona'],
        'nombres'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'],
        'apellidos'=>$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'igss'=>$e['id_persona'],//$e['afiliacion_IGSS'],
        'nit'=>$e['dpi'],
        'puesto'=>$e['descripcion'],
        'status'=>$status,
        'estado'=>'Activo',//$e['estado_persona'],
        'direccion'=>$direccion,
        'sicoin'=>$e['sicoin'],
        'marca'=>$e['marca'],
        'modelo'=>$e['modelo'],
        'serie'=>$e['numero_serie']
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
