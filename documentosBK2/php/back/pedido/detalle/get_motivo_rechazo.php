<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  include_once '../../../../../empleados/php/back/functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];
  $ped_tra = $_GET['ped_tra'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "SELECT TOP 1 ped_tra, ped_observaciones FROM docto_pedido_seguimiento_bitacora WHERE (ped_tipo_seguimiento_id = 8141 OR ped_tipo_seguimiento_id = 8144 ) AND ped_tra = ?
          ORDER BY ped_reng_num DESC";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(
    $ped_tra
  ));
  $ant = $q0->fetch();

  $data = array();
  $data = array(
    'ped_tra'=>$ant['ped_tra'],
    'observaciones'=>$ant['ped_observaciones']
  );

  echo json_encode($data);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
