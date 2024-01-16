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

  $state = array(0 => 'Registrado', 1 => 'Activo', 2 => 'Aprobado', 3=>'Rechazado');
  $color = array(0 => 'text-warning', 1 => 'text-info', 2 => 'text-success', 3=>'text-danger');

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "SELECT TOP 1 ped_tra, reng_num, archivo, id_status, subido_por, subido_en, descripcion, observaciones FROM docto_ped_doctos_respaldo WHERE ped_tra = ?
          ORDER BY reng_num DESC";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(
    $ped_tra
  ));
  $ant = $q0->fetch();

  $data = array();
  $data = array(
    'ped_tra'=>(!empty($ant['ped_tra'])) ? $ant['ped_tra'] : 0,
    'reng_num'=>$ant['reng_num'],
    'archivo'=>$ant['archivo'],
    'subido_por'=>$ant['subido_por'],
    'subido_en'=>date('d-m-Y H:i:s',strtotime($ant['subido_en'])),
    'id_status'=>$ant['id_status'],
    'estado'=>$state[$ant['id_status']],
    'color'=>$color[$ant['id_status']],
    'observaciones'=>$ant['observaciones']
  );

  echo json_encode($data);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
