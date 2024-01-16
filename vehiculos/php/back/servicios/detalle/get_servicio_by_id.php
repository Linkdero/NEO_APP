<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');
  $ini = '';
  $fin = '';
  $id_servicio = $_GET['id_servicio'];

      $clase= new vehiculos();

      $s = array();
      // desactivar y activar las siguientes lineas, para reimpresion de fechas anteriores
      $s = $clase->get_servicio_by_id($id_servicio);
      //$moves = $clase->get_all_valesCombustible('2022-03-25');
      $data = array();

      $data = array(
        'id_vehiculo'=>$s['id_vehiculo'],
        'orden_id'=>$s['id_servicio'],
        'nro_orden'=>$s['nro_orden'],
        'nro_placa'=>$s['nro_placa'],
        'nombre_tipo'=>$s['nombre_tipo'],
        'motor'=>$s['motor'],
        'marca'=>$s['marca'],
        'modelo'=>$s['modelo'],
        'linea'=>$s['linea'],
        'color'=>$s['color'],
        'nombre_taller'=>$s['nombre_taller'],
        'tipo_servicio'=>$s['tipo_servicio'],
        'km_vehiculo'=>$s['km_vehiculo'],
        'desc_solicitado'=>$s['desc_solicitado'],//str_replace("{","",$s['desc_solicitado']),//$s['desc_solicitado'],//strtoupper(wordwrap($s['desc_solicitado'], 50, '<br />')),
        'obs_solicitado'=>$s['obs_solicitado'],
        'desc_realizado'=>$s['desc_realizado'],//str_replace("{","",$s['desc_realizado']),//strtoupper(wordwrap($s['desc_realizado'], 50, '<br />')),
        'estado'=>$s['estado'],
        'accion'=>''
      );
    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
