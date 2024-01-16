<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');


  /*$ini_=$_POST['ini'];
    $ini=date('Y-m-d', strtotime($ini_));
    // $fin_=date('Y-m-d H:i:s');
    $fin_=$_POST['fin'];
    $fin=date('Y-m-d', strtotime($fin_));*/

  $estado = $_POST['estado'];

  $clase = new vehiculos();

  $moves = array();
  //$moves = $clase->get_all_valesCombustible($fin));

  $noCupon = 15953206;
  if ($estado == 9999) {
    $moves = $clase->get_cupones_by_cupon($noCupon);
  } else {
    $moves = $clase->get_all_cupones_entregados('2021-01-01', date('Y-m-d'), $estado);
    //$moves = $clase->get_all_valesCombustible('2021-03-21');
  }
  $data = array();

  foreach ($moves as $m) {
    $accion = '';

    $accion .= '<div class="btn-group btn-group-sm" role="group">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="Second group">';

    $accion .= '<button class="btn btn-sm btn-personalizado outline" title="Devolucion" data-toggle="modal" data-target="#modal-remoto-lgg2" href="vehiculos/php/front/cupones/procesar_cupones.php?id_documento=' . $m['id_documento'] . '"><i class="fa fa-sync"></i></button>';
    $accion .= '<button class="btn btn-sm btn-personalizado outline" title="Impresion" onclick="reporte_cupon(' . $m['id_documento'] . ')"><i class="fa fa-print"></i></button>';

    $accion .= '</div></div></div>';

    $sub_array = array(
      'id_documento' => $m['id_documento'],
      'estado' => $m['estado'],
      'fecha' => date('d-m-y', strtotime($m['fecha'])),
      'nro_documento' => $m['nro_documento'],
      'opero' => $m['opero'],
      'autorizo' => $m['autorizo'],
      'recibe' => $m['recibe'],
      'evento' => $m['evento'],
      'cupones' => $m['cupones'],
      'total' => (!empty($m['total'])) ? number_format($m['total'], 2, '.', ',') : '0.00',
      'pendiente' => (empty($m['pendiente'])) ? "Sin utilizar" : number_format($m['pendiente'], 2),
      'accion' => $accion,
    );

    $data[] = $sub_array;
  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );

  echo json_encode($results);

else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
