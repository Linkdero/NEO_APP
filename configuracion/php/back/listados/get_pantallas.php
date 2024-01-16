<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $modulo=$_POST['modulo'];
    $pantallas = array();
    $pantallas=configuracion::get_pantallas($modulo);
    $data = array();

    foreach ($pantallas as $p){
      $status='';
      if($p['id_activo']==0){
        $status.='<span class="badge badge-danger">Inactivo</span>';
      }else{
        $status.='<span class="badge badge-success">Activo</span>';
      }
      $id_pantalla=$p['id_pantalla'];
      $accion='<button onclick="mostrar_empleados_por_pantalla('.$id_pantalla.')" class="btn btn-personalizado outline btn-sm"><i class="fa fa-check"></i></button>';



      $sub_array = array(
        'id_pantalla'=>$p['id_pantalla'],
        'titulo'=>$p['titulo'],
        'pantalla_padre'=>$p['pantalla_padre'],
        'id_activo'=>$status,
        'descripcion'=>$p['descripcion'],
        'id_modulo'=>$p['id_sistema'],
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
