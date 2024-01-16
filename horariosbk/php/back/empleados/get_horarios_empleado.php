<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $sql = "SELECT *
          FROM [SAAS_APP].[dbo].[tbl_control_horario]";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $horarios = $stmt->fetchAll();
  Database::disconnect_sqlsrv();

  $data = array();

  foreach ($horarios as $t) {


    $sub_array = array(
      'id_horario' => $t['id_horario'],
      'horario' => date('H:i', strtotime($t['entrada'])).' - '.date('H:i', strtotime($t['salida'])),
    );

    $data[] = $sub_array;
  }


  echo json_encode($data);


else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
