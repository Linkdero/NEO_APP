<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true):

  $id_destino = $_GET['id_destino'];
  $id_tipo = (!empty($_GET['id_tipo'])) ? $_GET['id_tipo'] : 0;
  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';

  $clase = new vehiculos;
  $placas = array();

  if ($id_destino == 1144) { // vehiculos (propios)
    $placas = $clase->get_placas($id_tipo);
  } else if ($id_destino == 1147) { // vehiculos arrendados
    $placas = $clase->get_placas_ex($id_tipo);
  } else if ($id_destino == 1145) { // vehiculos arrendados

  }

  $data = array();
  $sub_array = array(
    'item_string' => '- Seleccionar -',
    'id_item' => '',
    'tipo_combustible' => ''
  );
  $data[] = $sub_array;
  foreach ($placas as $p) {

    $sub_array = array(
      'item_string' => $p['nro_placa'],
      'id_item' => $p['id_vehiculo'],
      'tipo_combustible' => $p['id_tipo_combustible']
    );
    $data[] = $sub_array;
  }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;