<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $transaccion=$_POST['transaccion'];

    $insumos = array();
    $insumos = insumo::get_insumos_by_transaccion($transaccion);
    $data = array();

    foreach ($insumos as $insumo){
      $chk1='';
      //$chk1.='<label class="css-input switch switch-danger"><input name="check" data-id="" type="checkbox"/><span></span></label>';
      //$movimiento = insumo::get_tipo_movimiento_by_id($m['id_tipo_movimiento']);
      $cantidad=0;

      if($insumo['id_tipo_movimiento']==1 || $insumo['id_tipo_movimiento']==4
      || $insumo['id_tipo_movimiento']==10){
        $cantidad=$insumo['cantidad_devuelta'];
      }else
      if($insumo['id_tipo_movimiento']==2 || $insumo['id_tipo_movimiento']==3
      || $insumo['id_tipo_movimiento']==7){
        $cantidad=$insumo['cantidad_entregada'];
      }
      $sub_array = array(
        'marca'=>$insumo['marca'],
        'modelo'=>$insumo['modelo'],
        'serie'=>$insumo['numero_serie'],
        'cantidad'=>number_format($cantidad, 0, ".", ",")/*,
        'estante'=>'',//$m['id_bodega_insumo'],
        'numero_serie'=>$m['numero_serie'],
        'cod_inventario'=>'',
        'propietario'=>'',
        'movimiento'=>$movimiento['descripcion'],
        'cantidad'=>number_format($m['cantidad_entregada'], 0, ".", ","),
        'cantidad_devuelta'=>number_format($m['cantidad_devuelta'], 0, ".", ","),
        'accion'=>$chk1*/
      );
      $data[]=$sub_array;
    }



  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($results);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
