<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_asignacion=$_POST['id_asignacion'];
  $reng_num=$_POST['reng_num'];
  $opc=$_POST['opc'];

  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE rrhh_asignacion_puesto_historial_detalle SET status=? WHERE id_asignacion=? AND reng_num=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($opc,$id_asignacion,$reng_num));


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
