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

  $insumos = documento::get_insumos_by_pedido($ped_tra);
  $data = array();

  foreach($insumos as $i){
    $sub_array = array(
      'Ppr_id'=>$i['Ppr_id'],
      'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'Ppr_cod'=>$i['Ppr_cod'],
      'Ppr_codPre'=>$i['Ppr_codPre'],
      'Ppr_Nom'=>$i['Ppr_Nom'],
      'Ppr_Des'=>$i['Ppr_Des'],
      'Ppr_Pres'=>$i['Ppr_Pres'],
      'Ppr_Ren'=>$i['Ppr_Ren'],
      'Ppr_Med'=>$i['Med_nom'],
      'Pedd_canf'=>number_format($i['Pedd_canf'],0,'',''),
      'Ped_resta'=>$i['Pedd_can']-$i['Pedd_canf'],
      'checked'=>false,
      'v_req'=>'req'.$i['Ppr_id'],
      'v_ent'=>'ent'.$i['Ppr_id'],
      'v_rec'=>'',
      'v_fal'=>'',
      'id_cant'=>'txt'.$i['Ppr_id']
    );
    $data[] = $sub_array;
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
