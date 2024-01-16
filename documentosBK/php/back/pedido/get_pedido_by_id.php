<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $usuario_actual = $_SESSION['id_persona'];
  $response = array();
  $ped_tra='';
  if(isset($_GET['ped_tra'])){
    $ped_tra=$_GET['ped_tra'];
  }
  $clased = new documento;

  $p = $clased->get_pedido_by_id($ped_tra);
  $data = array();

  $data = array(
    'ped_tra'=>$p['ped_tra'],
    'pedido_num'=>$p['ped_num'],//.' - - '.$p['Dir_cor'].' - - '.$clased->devuelve_direccion_app_pos($p['Dir_cor']),
    'fecha'=>fecha_dmy($p['ped_fec']),
    'observaciones'=>$p['ped_obs'],
    'id_direccion'=>$p['Dir_cor'],
    'tiempo'=>($clased->get_aprobacion_plani_by_pedido($ped_tra) == 'true') ? false : true,
    'creador'=>($p['persona_id'] == $usuario_actual) ? true : false,
    'direccion_funcional'=>$clased->devuelve_direccion_from_app_pos($p['Dir_cor'])//$clased->devuelve_direccion_funcional_desde_app_pos($p['Dir_cor'])
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
