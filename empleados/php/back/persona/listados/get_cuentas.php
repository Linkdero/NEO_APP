<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $cuentas = array();
    $cuentas=empleado::get_cuentas_by_empleado($id_persona);
    $data = array();

    foreach($cuentas as $c){
      $arreglo = array(
        'id_persona'=>$c['id_persona'],
        'id_cuenta'=>$c['id_cuenta'],
        'flag_activa'=>($c['flag_activa'] == 1) ? true : false,
        'flag_principal'=>($c['flag_principal'] == 1) ? true : false,
        'nro_cuenta'=>$c['nro_cuenta'],
        'id_tipo_cuenta'=>$c['id_tipo_cuenta'],
        'id_banco'=>$c['id_banco'],
        'fecha_apertura'=>date('Y-m-d', strtotime($c['fecha_apertura'])),
        'fecha_vencimiento'=>date('Y-m-d', strtotime($c['fecha_vencimiento']))
      );
      $sub_array = array(
        'id_persona'=>$c['id_persona'],
        'id_cuenta'=>$c['id_cuenta'],
        'flag_activa'=>$c['flag_activa'],
        'flag_principal'=>$c['flag_principal'],
        'nro_cuenta'=>$c['nro_cuenta'],
        'nombre_tipo_cuenta'=>$c['nombre_tipo_cuenta'],
        'nombre_banco'=>$c['nombre_banco'],
        'cactiva'=>($c['flag_activa'] == 1) ? 'fa fa-check-circle text-info' : 'fa fa-times-circle text-danger',
        'cprincipal'=>($c['flag_principal'] == 1) ? 'fa fa-check-circle text-info' : 'fa fa-times-circle text-danger',
        'arreglo'=>$arreglo

      );
      $data[]=$sub_array;
    }

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
