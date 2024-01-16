<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $arreglo = array();
    $cursos = array();
    $cursos=empleado::get_trabajos_by_empleado($id_persona);
    $data = array();

    foreach($cursos as $c){
      $arreglo = array(
        'id_persona'=>$c['id_persona'],
        'id_experiencia'=>$c['id_experiencia'],
        'empresa_nombre'=>$c['empresa_nombre'],
        'empresa_direccion'=>$c['empresa_direccion'],
        'nombre_puesto_ocupado'=>$c['nombre_puesto_ocupado'],
        'id_empresa_rama'=>$c['id_empresa_rama'],
        'id_puesto_ocupado'=>$c['id_puesto_ocupado'],
        'fecha_ingreso'=>date('Y-m-d', strtotime($c['fecha_ingreso'])),
        'fecha_salida'=>date('Y-m-d', strtotime($c['fecha_salida'])),
        'salario_inicial'=>number_format($c['salario_inicial'],2,'.',''),
        'salario_final'=>number_format($c['salario_final'],2,'.',''),
        'puesto_atribuciones'=>$c['puesto_atribuciones'],
        'motivo_retiro'=>$c['motivo_retiro'],
        'empresa_telefonos'=>$c['empresa_telefonos'],
        'personas_subordinadas'=>$c['personas_subordinadas'],
        'personas_subordinadas_puesto'=>$c['personas_subordinadas_puesto'],
        'jefe_inmediato'=>$c['jefe_inmediato'],
        'jefe_inmediato_telefono'=>$c['jefe_inmediato_telefono'],

      );
      $sub_array = array(
        'id_persona'=>$c['id_persona'],
        'id_experiencia'=>$c['id_experiencia'],
        'empresa_nombre'=>$c['empresa_nombre'],
        'empresa_direccion'=>$c['empresa_direccion'],
        'empresa_telefonos'=>$c['empresa_telefonos'],
        'id_puesto_ocupado'=>$c['id_puesto_ocupado'],
        'nombre_puesto_ocupado'=>$c['nombre_puesto_ocupado'],
        'fecha_inicio'=>date('Y-m-d', strtotime($c['fecha_ingreso'])),
        'fecha_fin'=>date('Y-m-d', strtotime($c['fecha_salida'])),
        'salario_final'=>$c['salario_final'],
        'jefe_inmediato'=>$c['jefe_inmediato'],
        'motivo_retiro'=>$c['motivo_retiro'],
    //'nombre_patrocinado'=>$c['nombre_patrocinado'],
        'arreglo'=>$arreglo

      );
      $data[]=$sub_array;
    }

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
