<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    //$ini_=$_POST['ini'];
    // $fin_=$_POST['fin'];
    // $ini=date('Y-m-d', strtotime($ini_));
    // $fin=date('Y-m-d', strtotime($fin_));
    $nro_vale_=$_POST['nro_vale'];
    $nro_vale=$nro_vale_;
    $clase= new vehiculos();

      $moves = array();
      $moves = $clase->get_valeCombustible($nro_vale);
      $data = array();

      foreach ($moves as $m){
        // $accion='';
        // $accion.='<div class="btn-group btn-group-sm" role="group">
        // <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        // <div class="btn-group mr-2" role="group" aria-label="Second group">';
        // $accion.='<button class="btn btn-sm btn-personalizado outline" onclick="reporte_movimiento('.$m['nro_vale'].')"><i class="fa fa-print"></i></button>';
        // //$accion.='<button class="btn btn-sm btn-personalizado outline"  data-toggle="modal" data-target="#modal-remoto" href="insumos/php/front/insumos/get_insumos_by_transaccion.php?transaccion='.$m['nro_vale'].'"><i class="fa fa-sliders-h"></i></button>';
        // $accion.='</div></div></div>';
        $sub_array = array(
          'fecha'=>date('d-m-Y H:i:s', strtotime($m['fecha'])),
          'nro_vale'=>$m['nro_vale'],
          'nro_placa'=>(empty($m['nro_placa']))?$m['refer']:$m['nro_placa'],
          'titulo_placa'=>(empty($m['nro_placa']))?'Referencia':'Placa',
          'estado'=>$m['estado'],
          'uso'=>$m['uso'],
          'evento'=>$m['evento'],
          'emite'=>$m['emite'],
          'autoriza'=>$m['autoriza'],
          'recibe'=>$m['recibe'],
          //'accion'=>$accion,
          'corte'=>$m['identificador_corte'],
          'id_combustible'=>$m['id_combustible'],
          'descripcion'=>$m['descripcion'],
          'cant_galones'=>number_format($m['cant_galones'],2,'.',','),
          'tlleno'=>$m['tlleno'],
     
        );

        $data[]=$sub_array;
      }

      echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
