<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona=$_POST['id_persona'];
  $e = array();
  $e = empleado::get_empleado_by_id($id_persona);
  $data = array();

  $accion = "";
  $status = '';
  $igss='--';
  $nisp='--';
  $observaciones='--';
  $tipo_contrato='--';
  if($e['afiliacion_IGSS']!=''){
    $igss=$e['afiliacion_IGSS'];
  }
  if($e['NISP']!=''){
    $nisp=$e['NISP'];
  }
  if($e['emp_observaciones']!=''){
    $observaciones=$e['emp_observaciones'];
  }
  if($e['tipo_contrato']!=''){
    $tipo_contrato=$e['tipo_contrato'];
  }


  $data = array(
    'id_persona'=>$e['id_persona'],
    'nombres'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'],
    'apellidos'=>$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
    'profesion'=>$e['profesion'],
    'nit'=>$e['nit'],
    'igss'=>$igss,
    'nisp'=>$nisp,
    'email'=>$e['correo_electronico'],
    'descripcion'=>$e['descripcion'],
    'estado_civil'=>$e['estado_civil'],
    'procedencia'=>$e['procedencia'],
    'tipo_personal'=>$e['tipo_personal'],
    'religion'=>$e['religion'],
    'fecha_nacimiento'=>fecha_dmy($e['fecha_nacimiento']),
    'municipio'=>$e['municipio'],
    'departamento'=>$e['departamento'],
    'tipo_contrato'=>$tipo_contrato,
    'observaciones'=>$observaciones,
    'status'=>$status,
    'genero'=>$e['genero'],
    'accesos'=>0,
    'accion'=>$accion
  );




    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
