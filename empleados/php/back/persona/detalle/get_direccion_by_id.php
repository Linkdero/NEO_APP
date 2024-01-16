<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    $id_direccion=$_GET['id_direccion'];
    $d = array();
    $d = empleado::get_direccion_by_id($id_direccion);
    $data = array();

    $data = array(
      'id_direccion'=>$d['id_direccion'],
      'id_tipo_referencia'=>$d['id_tipo_referencia'],

      'flag_actual'=>$d['Direccion_actual'],
      'nro_calle_avenida'=>$d['nro_calle_avenida'],
      'calle_tope'=>$d['calle_tope'],
      'id_tipo_calle'=>$d['tipo_calle'],
      'nro_casa'=>$d['nro_casa'],
      'id_zona'=>$d['zona'],
      'nro_apto_oficina'=>$d['nro_apto_oficina'],
      'id_departamento'=>$d['id_depto'],
      'id_municipio'=>$d['id_muni'],
      'id_lugar'=>$d['id_aldea'],
      'tipo_lugar'=>$d['id_tipo_lugar_poblado'],
      'observaciones'=>$d['observaciones']
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
