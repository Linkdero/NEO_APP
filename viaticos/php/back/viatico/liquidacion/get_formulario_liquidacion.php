<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../../empleados/php/back/functions.php';
  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');
  $clase = new viaticos;

  $vt_nombramiento=$_GET['id_viatico'];
  $id_persona=$_GET['id_persona'];

  $parametros = substr($id_persona, 1);



  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql2 = "SELECT ISNULL(nro_frm_vt_liq,0) formulario FROM vt_nombramiento_detalle WHERE vt_nombramiento = ? AND id_empleado = ?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($vt_nombramiento,$parametros));
    $response = $q2->fetch();

    echo json_encode($response);
    $pdo->commit();

  }catch (PDOException $e){
    echo $e;
    try{ $pdo->rollBack();}catch(Exception $e2){
      echo $e2;
    }
  }

  Database::disconnect_sqlsrv();


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
