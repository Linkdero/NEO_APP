<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $ini_=$_POST['ini'];
    $fin_=$_POST['fin'];
    $ini=date('Y-m-d', strtotime($ini_));
    $fin=date('Y-m-d', strtotime($fin_));

    $clase= new insumo();

    $datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
      $bodega_nom = $d['descripcion_corta'];

      $moves = array();
      $moves = $clase->get_all_movimientos_encabezado_by_bodega($bodega,$ini,$fin);
      $data = array();

      foreach ($moves as $m){
        $accion='';
        $accion.='<div class="btn-group btn-group-sm" role="group">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="Second group">';
        $accion.='<button class="btn btn-sm btn-personalizado outline" onclick="reporte_movimiento('.$m['id_doc_insumo'].')"><i class="fa fa-print"></i></button>';
        $accion.='<button class="btn btn-sm btn-personalizado outline"  data-toggle="modal" data-target="#modal-remoto" href="insumos/php/front/insumos/get_insumos_by_transaccion.php?transaccion='.$m['id_doc_insumo'].'"><i class="fa fa-sliders-h"></i></button>';
        $accion.='</div></div></div>';
        $sub_array = array(
          'transaccion'=>$m['id_doc_insumo'],
          'movimiento'=>$m['tipo_movimiento'],
          //'producto'=>$m['producto'],
          'fecha'=>date('d-m-Y H:i:s', strtotime($m['fecha'])),
          'total'=>$m['total'],
          'factura'=>$m['nro_documento'],
          'factura_num'=>$m['nro_documento'],
          'responsable'=>$m['n1_1'].' '.$m['n1_2'].' '.$m['n1_3'].' '.$m['a1_1'].' '.$m['a1_2'].' '.$m['a1_3'],
          'empleado'=>$m['n2_1'].' '.$m['n2_2'].' '.$m['n2_3'].' '.$m['a2_1'].' '.$m['a2_2'].' '.$m['a2_3'],
          'accion'=>$accion,
          'bodega'=>$bodega_nom,
          'gafete'=>"[".$m['id_persona']."]"
        );

        $data[]=$sub_array;
      }
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
