<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $ped_tra='';
  if(isset($_GET['ped_tra'])){
    $ped_tra=$_GET['ped_tra'];
  }
  $clased = new documento;

  $p = $clased->get_porcentaje_pedido($ped_tra);
  $data = array();

  $data = array(
    'ped_tra'=>$p['ped_tra'],
    'pedido_num'=>$p['ped_num'],//.' - - '.$p['Dir_cor'].' - - '.$clased->devuelve_direccion_app_pos($p['Dir_cor']),
    'valor'=>$p['porcentaje'],
    'texto'=>'text-'.$p['color'],
    'bg'=>'bg-'.$p['color'],
    'percent'=>$p['porcentaje'].'%',
    'estado'=>$p['estado'],
    'ped_status'=>$p['Ped_status'],
    'verificacion'=>$p['tipo_verificacion'],
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
