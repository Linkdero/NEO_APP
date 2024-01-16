<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $pac_id='';
  if(isset($_GET['pac_id'])){
    $pac_id=$_GET['pac_id'];
  }
  $clased = new documento;

  $p = $clased->get_pac_by_id($pac_id);
  $data = array();
  $usuario_actual = $_SESSION['id_persona'];

  $data = array(
    'pac_id'=>$p['pac_id'],
    'nombre'=>$p['pac_nombre'],//.' - - '.$p['Dir_cor'].' - - '.$clased->devuelve_direccion_app_pos($p['Dir_cor']),
    //'fecha'=>fecha_dmy($p['ped_fec']),
    'renglon'=>$p['pac_renglon'],
    'renglon_nm'=>$p['renglon_nm'],
    'detalle'=>$p['pac_detalle'],
    'id_direccion'=>'1',
    'tiempo'=>(date('Y-m-d')>='2021-12-10') ? true : false,
    'creador'=>($p['pac_creado_por'] == $usuario_actual) ? true : false,
    'anterior'=>(!empty($p['pac_ejercicio_ant'])) ? true : false,
    'pac_ejercicio_ant'=>$p['pac_ejercicio_ant'],
    'pac_ejercicio_ant_desc'=>$p['pac_ejercicio_ant_desc'],
    'id_status'=>$p['id_status']
    /*'direccion_funcional'=>$clased->devuelve_direccion_from_app_pos($p['Dir_cor'])//$clased->devuelve_direccion_funcional_desde_app_pos($p['Dir_cor'])*/
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
