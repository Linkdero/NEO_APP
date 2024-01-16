<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';



    $id_persona=$_SESSION['id_persona'];
    $tipo = $_GET['tipo'];
    $clase = new empleado;
    $clased = new documento;
    $current_year=date('Y-m-d');
    $last_year=strtotime ( '-1 year' , strtotime ( date('Y-m-d') ) ) ;
    $last_year=date('Y-m-d',$last_year);

    $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);

    $depto=(!empty($e['id_depto_funcional']))?$e['id_depto_funcional']:$e['id_dirf'];
    $data = array();

    $listado = $clased->get_pedidos_remesas_by_direccion($depto,$tipo);
    $sub_array = array(
      'ped_tra'=>'',
      'pedido_num'=>'-- Seleccionar --'.$e['id_depto_funcional'],
      'fecha'=>'',
      'observaciones'=>''
    );
    $data[] = $sub_array;
    foreach($listado as $l){
      $sub_array = array(
        'ped_tra'=>$l['ped_tra'],
        'pedido_num'=>$l['ped_num'].' -- '.$depto,
        'fecha'=>fecha_dmy($l['ped_fec']),
        'observaciones'=>$l['ped_obs']
      );
      $data[] = $sub_array;
    }

    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
