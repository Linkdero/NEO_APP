<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  $cui = null;

  if(!empty($_GET['cui'])){
    $cui = $_REQUEST['cui'];
  }

  $cui = preg_replace('([^A-Za-z0-9 ])', '', $cui);

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT COUNT(replace(nro_registro, ' ', '')) as conteo FROM rrhh_persona_documentos
  WHERE replace(nro_registro, ' ', '') LIKE ? AND id_tipo_identificacion = ?";
  $p = $pdo->prepare($sql);
  $p->execute(array($cui, 1238));
  $dpi = $p->fetch();
  Database::disconnect_sqlsrv();




  if($dpi['conteo']>0){
    echo 'false';
  }else{
    echo 'true';
  }

else:
    header("Location: ../index.php");
endif;
