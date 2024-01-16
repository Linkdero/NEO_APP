<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $clase = new vehiculos();

  $moves = array();

  $moves = $clase->get_all_cupones_ingresados('2020-01-01', date('Y-m-d'));
  $data = array();
$t=time();
  foreach ($moves as $m) {
    $accion = '';

    $accion .= '<div class="btn-group btn-group-sm" role="group">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="Second group">';

    $accion .= '<button class="btn btn-sm btn-personalizado outline" title="Total Cupones" data-toggle="modal" data-target="#modal-remoto-lgg2" href="vehiculos/php/front/cupones/cuponesIngresados.php?id_documento=' . $m['id_documento'] . '"><i class="fa fa-bars" aria-hidden="true"></i></button>';
    $accion .= '</div></div></div>';

    $sub_array = array(
      'id_documento' => $m['id_documento'],
      'estado' => $m['estado'],
      'fecha' => date('d-m-y', strtotime($m['fecha'])),
      'nro_documento' => $m['nro_documento'],
      'opero' => $m['opero'],
      'total' => (!empty($m['total'])) ? number_format($m['total'], 2, '.', ',') : '0.00',
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
