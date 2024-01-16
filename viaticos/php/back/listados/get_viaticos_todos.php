<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';

  $mes = $_POST['mes'];
  $year = $_POST['year'];

  $viaticos = array();
  $clase = new empleado;
  $clasev = new viaticos;

  $viaticos = $clasev->get_all_viaticos_reporte($mes, $year);
  $data = array();
  foreach ($viaticos as $key => $s) {

    $sub_array = array(
      'total' => $s['total'],
      'direccion' => $s['direccion'],
      'mes' => $s['mes'],
      'año' => $s['año'],
      'departamento' => $s['departamento'],
      'municipio' => $s['municipio'],
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
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
