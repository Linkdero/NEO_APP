<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true):

  $id_tipo = $_GET['id_tipo'];
  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';

  $clase = new vehiculos;
  $bombas = array();
  $bombas = $clase->get_bomba_comb($id_tipo);

  $data = array();
  $sub_array = array(
    'id_item' => '',
    'item_string' => '- Seleccionar -'
  );
  $data[] = $sub_array;
  foreach ($bombas as $bomba) {

    $sub_array = array(
      'id_item' => $bomba['id_bomba_despacho'],
      'item_string' => $bomba['descripcion']
    );
    $data[] = $sub_array;

  }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>