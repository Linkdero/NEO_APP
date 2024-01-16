<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $docto_id=$_POST['docto_id'];
  $dictamen=$_POST['dictamen'];
  $fecha=date('Y-m-d', strtotime($_POST['fecha']));

  $pdo = Database::connect_sqlsrv();
  $yes='';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 reng_num as reng FROM docto_dictamen WHERE docto_id=? ORDER BY reng_num DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($docto_id));
    $reng_num=$q0->fetch();

    $reng=1;
    if(!empty($reng_num['reng'])){
      $reng+=$reng_num['reng'];
    }

    $sql1 = "INSERT INTO docto_dictamen (
      docto_id,
      reng_num,
      docto_dictamen,
      docto_fecha,
      status
    )
    VALUES(?,?,?,?,?)";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      $docto_id,
      $reng,
      $dictamen,
      $fecha,
      1
      ));

    //$cg=$clased->get_correlativo_generado($creador);


    $pdo->commit();
    //echo json_encode($yes);
  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);
      echo json_encode($yes);
    }
  }

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
