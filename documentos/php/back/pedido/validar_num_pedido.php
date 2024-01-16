<?php
include_once '../../../../inc/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  $ped_num = null;

  if(!empty($_GET['ped_num'])){
      $ped_num = $_REQUEST['ped_num'];
  }

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT Ped_num FROM APP_POS.dbo.PEDIDO_E
          WHERE Ped_num LIKE ? AND Ped_estdoc = ? AND Ser_ser ='' ";
  $p = $pdo->prepare($sql);
  $p->execute(array($ped_num,'P'));
  $numero = $p->fetch();

  Database::disconnect_sqlsrv();

  if(empty($numero['Ped_num'])){
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
