<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../../functions.php';
  $orden_id=$_GET['orden_id'];

  $response = array();


  $bitacora = documento::get_bitacora_by_factura($orden_id);
  $data = array();
  foreach ($bitacora as $b){
    $respuesta='';

    $sub_array = array(
      'orden_compra_id'=>$b['orden_compra_id'],
      'id_seguimiento'=>$b['id_seguimiento'],//.'-'.$b['descripcion'],
      'reng_num'=>$b['reng_num'],
      //'operado_por'=>$b['pne'].' '.$b['sne'].' '.$b['tne'].' '.$b['pae'].' '.$b['sae'].' '.$b['tae'],
      'fecha'=>date('d-m-Y H:i:s', strtotime($b['operado_en'])),
      'persona_id'=>$b['persona_id'],
      'observaciones'=>$b['fac_observaciones'],
      'fac_status'=>$b['fac_status'],
      'id_seguimiento_detalle'=>$b['id_seguimiento_detalle'],
      'tipo'=>$b['descripcion_corta'],
      'operador'=>$b['primer_nombre'].' '.$b['segundo_nombre'].' '.$b['tercer_nombre'].' '.$b['primer_apellido'].' '.$b['segundo_apellido'].' '.$b['tercer_apellido']
    );
    $data[] = $sub_array;

  }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
