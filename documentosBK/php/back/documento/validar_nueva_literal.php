<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()):
  $docto_id = $_GET['docto_id'];
  $literal = $_GET['id_literal'];

  if(!empty($_GET['docto_id'])){
    $docto_id = $_REQUEST['docto_id'];
  }

  if(!empty($_GET['id_literal'])){
    $literal = $_REQUEST['id_literal'];
  }

  $pdo = Database::connect_sqlsrv();
  set_time_limit(0);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT a.base_literal_nom
          FROM docto_base_literal a
          INNER JOIN docto_base_literal_asignacion b ON b.base_literal_id=a.base_literal_id
          WHERE b.docto_id=? AND a.base_id=? AND a.base_literal_nom=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($docto_id, 8058, $literal));

  Database::disconnect_sqlsrv();

  $l = $q->fetch(PDO::FETCH_ASSOC);

  if($l['base_literal_nom']!=$literal)
  {
    echo 'true';
  }
  else {
    echo 'false';
  }
else:
    header("Location: ../index.php");
endif;

?>
