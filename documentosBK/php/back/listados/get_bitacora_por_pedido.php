<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  $ped_tra=$_GET['ped_tra'];

  $response = array();


  $bitacora = documento::get_bitacora_pedido($ped_tra);
  $data = array();
  foreach ($bitacora as $b){
    $respuesta='';

    $sub_array = array(
      'DT_RowId'=>$b['ped_tra'],
      'tipo'=>$b['id_item'].'-'.$b['descripcion'],
      'operador'=>$b['pn'].' '.$b['sn'].' '.$b['tn'].' '.$b['pa'].' '.$b['sa'].' '.$b['ta'],
      'enlace'=>$b['pne'].' '.$b['sne'].' '.$b['tne'].' '.$b['pae'].' '.$b['sae'].' '.$b['tae'],
      'fecha'=>date('Y-m-d H:i:s', strtotime($b['ped_seguimiento_fecha'])),
      'observaciones'=>$b['ped_observaciones']
    );
    $data[] = $sub_array;

  }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
