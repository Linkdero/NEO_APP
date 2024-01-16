<?php
include_once '../../../../inc/functions.php';
include_once '../../../../inc/Database.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) :

  include_once '../../../../quinta/php/back/functions.php';

  if ($_SERVER['REMOTE_ADDR'] == ('172.16.0.162')) {
    echo 'true';
  } else {

    $carne = $_GET['carne'];
    $row_visita = visita::get_data_by_ip($_SERVER["REMOTE_ADDR"]);
    $id_puerta = $row_visita['id_puerta'];
    if (!empty($_GET['carne'])) {
      $carne = $_REQUEST['carne'];
    }
    $fecha = date('Y-m-d');
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id FROM tbl_control_GafetexCodigo
            WHERE id=? AND estado=? AND id_puerta=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($carne, 0, $id_puerta));
    $estado = $p->fetch();

    Database::disconnect_sqlsrv();

    if ($estado['id'] == $carne) {
      echo 'true';
    } else {
      echo 'false';
    }
  }
else :
  header("Location: ../index.php");
endif;




/*

*/
