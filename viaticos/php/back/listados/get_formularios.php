<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $formularios = array();
    $formularios = viaticos::get_reporte_formularios();

    $data = array();
    foreach ($formularios as $f){
      $sub_array = array(
        'DT_RowId'=>$f['id_correlativo'],
        'tipo_formulario'=>$f['tipo_formulario'],
        'v_inicial'=>$f['v_inicial'],
        'v_final'=>$f['v_final'],
        'v_actual'=>$f['v_actual'],
        'v_restante'=>($f['v_final']-$f['v_actual']),
        'accion'=>''
      );
      $data[] = $sub_array;
        //$data[]=$e;

    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
