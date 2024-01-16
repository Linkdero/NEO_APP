<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    $id_persona=$_GET['id_persona'];
    $documentos = array();
    $documentos = empleado::get_documentos_by_empleado($id_persona);
    $data = array();

    foreach ($documentos as $d){
      $arreglo = array(
        'id_persona'=>$d['id_persona'],
        'id_documento'=>$d['id_documento'],
        'id_tipo_identificacion'=>$d['id_tipo_identificacion'],
        'id_tipo_documento'=>$d['id_tipo_documento'],
        'nro_registro'=>$d['nro_registro'],
        'fecha_vencimiento'=>date('Y-m-d', strtotime($d['fecha_vencimiento'])),
        'id_departamento'=>$d['id_departamento'],
        'id_municipio'=>$d['id_municipio'],
        'id_lugar'=>$d['id_aldea'],
      );
      $sub_array = array(
        'id_persona'=>$d['id_persona'],
        'id_documento'=>$d['id_documento'],
        'nombre_tipo_identificacion'=>$d['nombre_tipo_identificacion'],
        'nombre_tipo_documento'=>$d['nombre_tipo_documento'],
        'id_orden_cedula'=>$d['id_orden_cedula'],
        'nro_registro'=>$d['nro_registro'],
        'fecha_vencimiento'=>fecha_dmy($d['fecha_vencimiento']),
        'departamento'=>$d['nombre_departamento'],
        'municipio'=>$d['nombre_municipio'],
        'lugar'=>$d['nombre_aldea'],
        'arreglo'=>$arreglo
      );

      $data[]=$sub_array;
    }

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
