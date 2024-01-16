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

  $pac_id = (!empty($_POST['pac_id'])) ? strtoupper($_POST['pac_id']) : NULL;
  $meses = $_POST['months'];


  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $compras = false;
    foreach($meses AS $m){
      $status = (($m['checked']) == true) ? 1 : 2;
      if($status == 1 && $m['compras'] == true){
        $compras = true;
        $cant = ($m['checked'] == true) ? intVal($m['cantidad_real']) : NULL;
        $mont = ($m['checked'] == true) ? floatval($m['monto_real']) : NULL;
        $sql2 = "UPDATE APP_POS.dbo.PAC_D
                   SET cantidad_real = ?, monto_real = ?, id_status = ?
                   WHERE pac_id = ? AND pac_id_mes = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($cant,$mont,$status,$pac_id,$m['id_mes']));

        $sql2 = "UPDATE APP_POS.dbo.PAC_D
                   SET cantidad_real = ?, monto_real = ?
                   WHERE pac_id = ? AND pac_id_mes = ? AND cantidad = 0";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array(NULL,NULL,$pac_id,$m['id_mes']));
      }else{
        $cant = ($m['checked'] == true) ? intVal($m['cantidad']) : NULL;
        $mont = ($m['checked'] == true) ? floatval($m['monto']) : NULL;
        $sql2 = "UPDATE APP_POS.dbo.PAC_D
                   SET cantidad = ?, monto = ?, id_status = ?
                   WHERE pac_id = ? AND pac_id_mes = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($cant,$mont,$status,$pac_id,$m['id_mes']));

        $sql2 = "UPDATE APP_POS.dbo.PAC_D
                   SET cantidad = ?, monto = ?
                   WHERE pac_id = ? AND pac_id_mes = ? AND cantidad = 0";
        $q2 = $pdo->prepare($sql2);

        $q2->execute(array(NULL,NULL,$pac_id,$m['id_mes']));
      }
    }

    if($compras == true){
      $sql2 = "UPDATE APP_POS.dbo.PAC_E
                 SET id_status = ?,
                 pac_compra_aut	= ?,
                 pac_compra_fecha	= ?
                 WHERE pac_id = ?";
      $q2 = $pdo->prepare($sql2);

      $q2->execute(array(3,$_SESSION['id_persona'],date('Y-m-d H:i:s'),$pac_id));
    }
    $yes = array('msg'=>'OK','id'=>'');
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
