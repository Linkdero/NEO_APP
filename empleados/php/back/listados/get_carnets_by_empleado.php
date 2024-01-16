<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $empleados = array();
    $id_empleado = $_POST['id_empleado'];
    $carnets = empleado::get_carnets_by_empleado($id_empleado);
    $data = array();

    foreach ($carnets as $c){
      $accion='<a class="btn btn-sm btn-soft-info" href="empleados/php/back/carnets/print_carnet.php?id_gafete='.$c['id_gafete'].'" target="_blank"><i class="fa fa-print"></i></a>';

      $sub_array = array(
        'id_gafete'=>$c['id_gafete'],
        'id_empleado'=>$c['id_empleado'],
        'id_contrato'=>$c['id_contrato'],
        'id_version'=>$c['id_version'],
        'puesto'=>$c['puesto'],
        'fecha_generado'=>$c['fecha_generado'],
        'fecha_vencimiento'=>$c['fecha_vencimiento'],
        'fecha_validado'=>$c['fecha_validado'],
        'fecha_baja'=>$c['fecha_baja'],
        'fecha_impreso'=>$c['fecha_impreso'],
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
