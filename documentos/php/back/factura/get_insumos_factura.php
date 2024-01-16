<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $orden_id='';
  if(isset($_GET['orden_id'])){
    $orden_id=$_GET['orden_id'];
  }

  $insumos = documento::get_insumos_by_factura($orden_id);
  $data = array();

  foreach($insumos as $i){
    $sub_array = array(
      'Ppr_id'=>$i['Ppr_id'],
      'orden_compra_id'=>$i['orden_compra_id'],
      'Ppr_id'=>$i['Ppr_id'],
      'Ppr_Pres'=>$i['Ppr_Pres'],
      'Ppr_Ren'=>$i['Ppr_Ren'],
      'Ppr_Med'=>$i['Med_nom'],
      'Ppr_cod'=>$i['Ppr_cod'],
      'Ppr_codPre'=>$i['Ppr_codPre'],
      'Ppr_Nom'=>$i['Ppr_Nom'],
      'Ppr_Des'=>$i['Ppr_Des'],
      'Med_nom'=>$i['Med_nom'],
      'Ppr_can'=>$i['Ppr_can'],
      'Ppr_costo'=>$i['Ppr_costo'],
      'Ppr_importe'=>number_format(($i['Ppr_can'] * $i['Ppr_costo']),2,'.',','),
      'Ppr_status'=>$i['Ppr_status'],
      'reng_num'=>$i['reng_num'],
      'checked'=>(empty($i['Ped_tra'])) ? false : true,
      'Ped_tra'=>(!empty($i['Ped_tra'])) ? $i['Ped_tra'] : ''

    );
    $data[] = $sub_array;
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
