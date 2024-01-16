<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  header('Content-Type: text/html; charset=utf-8');

  $data = array();
  $insumos = array();
  $array_insumos = array();

  $ped_tra=$_POST['ped_tra'];

  $clased = new documento;

  $p = $clased->get_pedido_by_id($ped_tra);
  $insumos = $clased->get_insumos_by_pedido($ped_tra);
  $u = $clased->get_unidad_by_pedido($ped_tra);

  $correlativo = 0;
  $tipo = 1;
  $tipo = (date('Y-m-d') > '2022-03-01') ? 2 : 1;
  foreach($insumos as $i){

    $original = str_replace("\n","",$i['descripcion']);

    //$my_wrapped_string = wordwrap($original, 100, );
    $letras = strlen($original);
    $lineas = round(($letras/70)*3.4);
    if($i['Ppr_id'] == 39555 || $i['Ppr_id'] == 69584 || $i['Ppr_id'] == 38267 || $i['Ppr_id'] == 69585 || $i['Ppr_id'] == 45548){
      $lineas +=2;
    }
    $correlativo +=1;
    //$original .= ' || '.$letras.' || '.$lineas;

    $sub_array = array(
      'correlativo'=>''.$correlativo.'',
      'Ppr_id'=>$i['Ppr_id'],
      'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'Ppr_cod'=>$i['Ppr_cod'],
      'Ppr_codPre'=>$i['Ppr_codPre'],
      'Ppr_Nom'=>$i['Ppr_Nom'],
      'Ppr_Des'=>$i['Ppr_Des'],
      'Ppr_Pres'=>$i['Ppr_Pres'],
      'Ppr_Ren'=>$i['Ppr_Ren'],
      'Ppr_Med'=>$i['Med_nom'],
      'descripcion'=>$original,
      'lineas'=>($lineas == 0 || $lineas < 5.5)?5:$lineas,
      'codificacion'=>$i['Ppr_cod'].'-'.$i['Ppr_codPre'].'-'.$i['Ppr_Ren'].'-'.$i['Ppr_id']
    );
    $array_insumos[] = $sub_array;
  }

  $data = array(
    'ped_tra'=>$p['ped_tra'],
    'pedido_num'=>$p['ped_num'],//.' - - '.$p['Dir_cor'].' - - '.$clased->devuelve_direccion_app_pos($p['Dir_cor']),
    'fecha'=>fecha_dmy($p['ped_fec']),
    'observaciones'=>'*[CodigoPPR],[PPR.presentación],[PPR.renglón],[correlativo] '.$p['ped_obs'].' (Tran: '.$p['ped_tra'].' / '.$p['ped_num'].')',
    'id_direccion'=>$p['Dir_cor'],
    'direccion'=>$u['direccion'],
    'departamento'=>$u['departamento'],
    //'direccion_funcional'=>$clased->devuelve_direccion_app_pos($p['Dir_cor']),
    'insumos'=>$array_insumos,
    'tipo'=>$tipo//$clased->devuelve_direccion_funcional_desde_app_pos($p['Dir_cor'])
  );

//echo $output;
echo json_encode($data);



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
