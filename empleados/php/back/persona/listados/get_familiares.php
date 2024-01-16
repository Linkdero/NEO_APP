<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $familia = array();
    $familia=empleado::get_familia_by_empleado($id_persona);
    $data = array();

    foreach($familia as $f){

      $arreglo = array(
        'id_referencia'=>$f['id_referencia'],
        'id_tipo_referencia'=>$f['tipo_referencia'],
        'id_parentesco'=>$f['id_parentesco'],
        'id_genero'=>$f['id_genero'],
        'primer_nombre'=>$f['primer_nombre'],
        'segundo_nombre'=>$f['segundo_nombre'],
        'primer_apellido'=>$f['primer_apellido'],
        'segundo_apellido'=>$f['segundo_apellido'],
        'fecha_nacimiento'=>date('Y-m-d', strtotime($f['fecha_nacimiento'])),
        'empresa_trabaja'=>$f['empresa_trabaja'],
        'empresa_direccion'=>$f['empresa_direccion'],
        'empresa_telefono'=>$f['empresa_telefono'],
        'id_ocupacion'=>$f['id_ocupacion'],
        'flag_fallecido'=>$f['flag_fallecido']
      );
      $sub_array = array(
        'id_referencia'=>$f['id_referencia'],
        'parentesco'=>$f['parentesco'],
        'flag_fallecido'=>$f['flag_fallecido'],
        'nombre'=>$f['nombre'],
        'edad'=>$f['edad'],
        'arreglo'=>$arreglo
      );
      $data[]=$sub_array;
    }

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
