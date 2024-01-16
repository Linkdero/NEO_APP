<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../../empleados/php/back/functions.php';
  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');
  $clase = new viaticos;

  $vt_nombramiento=$_GET['id_viatico'];
  $id_persona=$_GET['id_persona'];

  $parametros = substr($id_persona, 1);

  $verificacion = $clase->verificarFacturasPorDia($vt_nombramiento,$parametros);
  $data = array();

  $response = '';
  $msg = 'OK';
  $message = '';

  if(count($verificacion)>0){
    foreach ($verificacion as $key => $v) {

      if($v['facturas'] == 0){
        $msg = 'ERROR';
        $message = 'Le falta agregar facturas a los días.';
      }
      /*$sub_array = array(
        'dia_id'=>$v['dia_id'],
        'facturas'=>$v['facturas']
      );
      $data[]=$sub_array;*/
    }
  }else{
    $msg = 'ERROR';
    $message = 'Debe de empezar a agregar facturas';
  }



  $response = array('msg'=>$msg,'message'=>$message);

  echo json_encode($response);
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
