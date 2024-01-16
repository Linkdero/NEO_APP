<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $arreglo = array();
    $cursos = array();
    $cursos=empleado::get_cursos_by_empleado($id_persona,NULL);
    $data = array();

    foreach($cursos as $c){
      $arreglo = array(
        'id_persona'=>$c['id_persona'],
        'id_capacitacion'=>$c['id_capacitacion'],
        'id_curso'=>$c['id_curso'],
        'nombre_curso'=>$c['nombre_curso'],
        'id_centro_capacitacion'=>$c['id_centro_capacitacion'],
        'id_tipo_curso'=>$c['id_tipo_curso'],
        'fecha_inicio'=>date('Y-m-d', strtotime($c['fecha_inicio'])),
        'fecha_fin'=>date('Y-m-d', strtotime($c['fecha_fin'])),
        'horas_curso'=>$c['horas_curso'],
        'horas_completadas'=>$c['horas_completadas'],
        'id_pais'=>$c['id_pais'],
        'id_patrocinador'=>$c['id_patrocinador'],
        'id_modalidad'=>$c['id_modalidad']

      );
      $sub_array = array(
        'id_persona'=>$c['id_persona'],
        'id_capacitacion'=>$c['id_capacitacion'],
        'id_curso'=>$c['id_curso'],
        'id_centro_capacitacion'=>$c['id_centro_capacitacion'],
        'nombre_centro_capacitacion'=>$c['nombre_centro_capacitacion'],
        'id_tipo_curso'=>$c['id_tipo_curso'],
        'nombre_tipo_curso'=>$c['nombre_tipo_curso'],
        'nombre_curso'=>$c['nombre_curso'],
        'nombre_curso_temporal'=>$c['nombre_curso_temporal'],
        'descripcion'=>$c['descripcion'],
        'fecha_inicio'=>fecha_dmy($c['fecha_inicio']),
        'fecha_fin'=>fecha_dmy($c['fecha_fin']),
        'horas_curso'=>$c['horas_curso'],
        'horas_completadas'=>$c['horas_completadas'],
        'id_pais'=>$c['id_pais'],
        'nombre_pais'=>$c['nombre_pais'],
        'id_patrocinador'=>$c['id_patrocinador'],
        'nombre_patrocinador'=>$c['nombre_patrocinador'],
        'id_status'=>$c['id_status'],
        'id_modalidad'=>$c['id_modalidad'],
        'modalidad'=>($c['id_modalidad'] == 1) ? 'Presencial' : 'Virtual',
        'arreglo'=>$arreglo

      );
      $data[]=$sub_array;
    }


    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
