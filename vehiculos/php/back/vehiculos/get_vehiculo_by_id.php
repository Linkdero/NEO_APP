<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
      $clase= new vehiculos();

      $id_vehiculo = $_GET['id_vehiculo'];
      $v = array();

      $v = $clase->get_vehiculo_by_id($id_vehiculo);

      $data = array();

      $data = array(
        'DT_RowId'=>$v['id_vehiculo'],
        'id_vehiculo'=>$v['id_vehiculo'],
        'nro_placa'=>$v['nro_placa'],
        'chasis'=>$v['chasis'],
        'motor'=>$v['motor'],
        'modelo'=>$v['modelo'],
        'flag_franjas_de_color'=>$v['flag_franjas_de_color'],
        'nombre_marca'=>$v['nombre_marca'],
        'nombre_tipo'=>$v['nombre_tipo'],
        'nombre_estado'=>$v['nombre_estado'],
        'nombre_persona_asignado'=>$v['nombre_persona_asignado'],
        'capacidad_tanque'=>number_format($v['capacidad_tanque'],2,'.',','),
        'nombre_tipo_combustible'=>$v['nombre_tipo_combustible'],
        'km_actual'=>$v['km_actual'],
        'accion'=>''
    );


  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
