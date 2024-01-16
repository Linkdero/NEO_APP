<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true):

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');
  $id_documento = $_GET['id_documento'];
  $clase = new vehiculos();
  $cupones = array();
  $cupones = $clase->get_devolCuponesDet($id_documento);
  $data = array();

  foreach ($cupones as $c) {

    $sub_array = array(
      'id_documento' => $c['id_documento'],
      'id_cupon' => $c['id_cupon'],
      'cupon' => $c['cupon'],
      'monto' => number_format($c['monto'], 2, '.', ','),
      'usado' => $c['usado'],
      'devuelto' => $c['devuelto'],
      'usadoen' => $c['usadoen'],
      'nombre' => $c['nombre'],
      'placa' => $c['placa'],
      'km' => $c['km'],
      'id_tipo_uso' => $c['id_tipo_uso'],

      'radio' => $c['radio'],
      'v_flag' => $c['id_cupon'],
      'cupon1' => 'cp1-' . $c['cupon'],
      'cupon2' => 'cp2-' . $c['cupon'],
      'checked1' => ($c['usado'] == 1) ? true : false,
      'checked2' => ($c['devuelto'] == 1) ? true : false,

      'id_vehiculo' => $c['id_vehiculo'],
      'id_refer' => $c['id_refer'],
      'id_departamento' => $c['id_departamento'],
      'id_municipio' => $c['id_municipio'],
      'referencia' => $c['referencia'],
      'caracteristicas' => $c['caracteristicas'],

    );

    $data[] = $sub_array;
  }


  echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;