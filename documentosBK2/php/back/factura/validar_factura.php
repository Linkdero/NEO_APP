<?php
include_once '../../../../inc/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  $factura_nro = null;
  $factura_serie = null;

  if(!empty($_GET['factura_nro'])){
      $factura_nro = $_REQUEST['factura_nro'];
  }

  if(!empty($_GET['factura_serie'])){
      $factura_serie = $_REQUEST['factura_serie'];
  }

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT factura_num FROM docto_ped_pago
          WHERE factura_num LIKE ? AND factura_serie = ?";
  $p = $pdo->prepare($sql);
  $p->execute(array($factura_nro, $factura_serie));
  $factura = $p->fetch();

  Database::disconnect_sqlsrv();

  if(empty($factura['factura_num'])){
    echo 'true';
  }else{
    echo 'false';
  }

else:
    header("Location: ../index.php");
endif;




/*

*/
?>
