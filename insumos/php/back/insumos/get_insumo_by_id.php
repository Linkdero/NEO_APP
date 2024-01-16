<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $id_insumo=$_POST['id_insumo'];
  $producto = insumo::get_insumo_by_id($id_insumo);//Ingreso a Bodega
  $data = array();
  $codigo='';
  /*if($producto['id_prod_ins_detalle']!=''){
    $codigo=$producto['id_prod_ins_detalle'];
  }*/
  $cantidad=1;
  /*if($producto['id_tipo_insumo']==10 || $producto['id_tipo_insumo']==11
  || $producto['id_tipo_insumo']==12 || $producto['id_tipo_insumo']==18
  || $producto['id_tipo_insumo']==31 || $producto['id_tipo_insumo']==34
  || $producto['id_tipo_insumo']==35 || $producto['id_tipo_insumo']==40
  || $producto['id_tipo_insumo']==41 || $producto['id_tipo_insumo']==42
  || $producto['id_tipo_insumo']==49){
    $cantidad.='<span id="message'.$codigo.'" class="bar"></span>';
    $cantidad="<input id='txt".$codigo."' class='cantidad_ form-control input-sm text-center form_corto' style='' value='1' required min='1' ></input>";
  }
  else{
    $cantidad="<input id='txt".$codigo."' disabled class='cantidad_ form-control input-sm form_corto' style='' value='1'  required min='1' ></input>";
  }*/
  //$desc="<textarea id='text_".$codigo."' class='form-control form_medio' rows='2'></textarea>";
  $sicoin='- - - -';
  if($producto['codigo_inventarios']!=''){
    $sicoin=$producto['codigo_inventarios'];
  }
  $data = array(
    'sicoin'=>$sicoin,
    'codigo'=>$codigo,
    'marca'=>$producto['marca'],
    'modelo'=>$producto['modelo'],
    'serie'=>$producto['numero_serie'],
    'existencia'=>number_format($producto['existencia'], 0, ".", ","),
    'id_estado'=>$producto['id_status'],
    'cantidad'=>$cantidad,
    'estado'=>$producto['estado'],
    'tipo'=>$producto['tipo']/*,
    'desc'=>$desc*/
  );


echo json_encode($data);
exit;




else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
