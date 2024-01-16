<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {


  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $pac_id = $_POST['pac_id'];

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql2 = "UPDATE APP_POS.dbo.PAC_E
               SET id_status = ?,
               pac_compra_aut	= ?,
               pac_compra_fecha	= ?
               WHERE pac_id = ?";
    $q2 = $pdo->prepare($sql2);

    $q2->execute(array(4,$creador,date('Y-m-d H:i:s'),$pac_id));
    $yes = array('msg'=>'OK','message'=>'Plan anulado');
    //echo json_encode($yes);
    $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }

  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
