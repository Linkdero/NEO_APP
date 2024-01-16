<?php
include_once '../../../../inc/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  $proveedor_nit = null;

  if(!empty($_GET['proveedor_nit'])){
      $proveedor_nit = $_REQUEST['proveedor_nit'];
  }

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT Prov_id FROM APP_POS.dbo.PROVEEDOR
          WHERE Prov_id LIKE ? AND Prov_est = ?";
  $p = $pdo->prepare($sql);
  $p->execute(array($proveedor_nit,1));
  $nit = $p->fetch();

  Database::disconnect_sqlsrv();

  if(empty($nit['Prov_id'])){
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
