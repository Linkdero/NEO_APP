<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');

    $fin_=date('Y-m-d H:i:s');
    $fin=date('Y-m-d', strtotime($fin_));

      $clase= new vehiculos();

      $moves = array();
      // desactivar y activar las siguientes lineas, para reimpresion de fechas anteriores
      $moves = $clase->get_all_valesCombustible($fin);
      //$moves = $clase->get_all_valesCombustible('2022-11-03');
      $data = array();

      foreach ($moves as $m){
        $accion='';
        
        $accion.='<div class="btn-group btn-group-sm" role="group">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="Second group">';

        //  desactivar esta condicion, para reimpresion
        if ($m['id_estado']==1150) {  //  sin despachar
          if(evaluar_flag($_SESSION['id_persona'],1162,109,'flag_insertar')==1 ){
            $accion.='<button class="btn btn-sm btn-personalizado outline" title="Impresion" onclick="reporte_movimiento('.$m['nro_vale'].')"><i class="fa fa-print"></i></button>';}
          if(evaluar_flag($_SESSION['id_persona'],1162,109,'flag_eliminar')==1 ){
            $accion.='<button class="btn btn-sm btn-personalizado outline" title="Anular un vale" data-toggle="modal" data-target="#modal-remoto-lg" href="vehiculos/php/front/vales/despachar_vale.php?nro_vale='.$m['nro_vale'].'&tipo=2"><i class="fa fa-times"></i></button>';}
          if(evaluar_flag($_SESSION['id_persona'],1162,109,'flag_actualizar')==1 ){
            $accion.='<button class="btn btn-sm btn-personalizado outline" title="Despachar vale" data-toggle="modal" data-target="#modal-remoto-lg" href="vehiculos/php/front/vales/despachar_vale.php?nro_vale='.$m['nro_vale'].'&tipo=3"><i class="fa fa-gas-pump"></i></button>';}
        }

        $accion.='</div></div></div>';
        
        $sub_array = array(
          'fecha'=>date('d-m-Y H:i', strtotime($m['fecha'])),
          'nro_vale'=>$m['nro_vale'],
          'nro_placa'=>$m['nro_placa'],
          'estado'=>$m['estado'],
          'uso'=>$m['uso'],
          'evento'=>$m['evento'],
          'recibe'=>$m['recibe'],
          'accion'=>$accion,
          'corte'=>$m['identificador_corte'],
          'id_combustible'=>$m['id_combustible'],
          'descripcion'=>$m['descripcion'],
          'cant_galones'=>$m['cant_galones'],
          'tlleno'=>$m['tlleno'],


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
