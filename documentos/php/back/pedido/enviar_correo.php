<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $destinatarios = $_POST['destinatarios'];
  $ped_tra = $_POST['ped_tra'];
  $clase = new documento;
  $mensaje = $clase->get_bitacora_by_id($ped_tra);
  $pedido = $clase->get_pedido_by_id($ped_tra);

  $subject = 'PEDIDO '.$mensaje['descripcion'];
  $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el pedido No. <strong>'.$pedido['ped_num'].'</strong> fue '.$mensaje['descripcion'];
  if($mensaje['id_item'] == 8141){
    $body.="<br><br> Motivo: ".$mensaje['ped_observaciones'].' <br><br> siendo las: '.date('H:i:s',strtotime($mensaje['ped_seguimiento_fecha'])).' del '.date('d-m-Y', strtotime($mensaje['ped_seguimiento_fecha']));
  }
  $body.='<br><br>Favor comunicarse con el área';

  $body.='<br><br><br>Correo enviado desde SAAS APP - Módulo control de Pedidos y Remsas';

  /*$insumos =*/ //documento::enviar_correo_estado("'".$destinatarios."'", "'".$subject."'", "'".$body."'");

  $data = array();

  $data = array(
    'emisor'=>$mensaje['persona_user'],
    'receptor'=>$_POST['destinatarios'],
    'subject'=>$subject,
    'body'=>$body,
  );

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
