<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $id_documento='';
  if(isset($_GET['id_documento'])){
    $id_documento=$_GET['id_documento'];
  }
  $clased = new vehiculos;

  $p = $clased->get_devolCuponesEnc($id_documento);
  $data = array();

  $data = array(
    'id_documento'=>$p['id_documento'],
    'fecha'=>fecha_dmy($p['fecha']),
    'nro_documento'=>$p['nro_documento'],
    'estado'=>$p['estado'],
    'auto'=>$p['auto'],
    'id_estado'=>($p['id_estado'] == 4347)?true:false,
    'docto_estado'=>$p['id_estado'],
    'recibe'=>$p['recibe'],
    'total'=>number_format($p['total'],2,'.',','),
    'devuelto'=>number_format($p['devuelto'],2,'.',','),
    'observa'=>$p['observa'],
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
