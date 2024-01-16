<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  date_default_timezone_set('America/Guatemala');



  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "SELECT * FROM ChequeDetalle WHERE chequeStatus = ?
          ORDER BY chequeId ASC";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(
    1
  ));
  $cheques = $q0->fetchAll();

  $data = array();
  foreach ($cheques as $key => $c) {
    // code...
    $sub_array = array(
      //'disponible'=>($key == 0) ? false : true,
      'disponible'=> false,
      'id_item'=>$c['chequeId'],
      'item_string'=>$c['chequeNum']
    );

    $data[] = $sub_array;

  }

  echo json_encode($data);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
