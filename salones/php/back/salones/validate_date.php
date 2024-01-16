<?php
include_once '../../../../inc/functions.php';
sec_session_start();

if (function_exists('verificar_session') && verificar_session() == true) {
  date_default_timezone_set('America/Guatemala');
  include_once '../../functions.php';

  $fecha = $_POST["fecha"];
  $id_salon = $_POST["salon"];
  $salon = new Salon();
  $response = $salon::verify_date($fecha, $id_salon);
  echo json_encode($response);
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
