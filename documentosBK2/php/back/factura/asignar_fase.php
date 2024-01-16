<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $orden_id=$_POST['orden_id'];
  $tipo = $_POST['tipo'];
  $valor=$_POST['valor'];

  $campo = '';
  $modalidad_pago = 0;
  if($tipo == 0){
    $campo = 'tipo_pago';
    if($valor == 2){
      $modalidad_pago = 2;
    }
  }
  //echo $tipo;
  if($tipo == 1){
    $campo = 'cheque_nro';
  }
  if($tipo == 2){
    $campo = 'nro_orden';

  }
  if($tipo == 3){
    $campo = 'cur';
  }
  if($tipo == 4){
    $campo = 'cur_devengado';
  }
  if($tipo == 5){
    $campo = 'modalidad_pago';
    $modalidad_pago = $valor;
  }
  if($tipo == 6){
    $campo = 'clase_proceso';
  }

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    if($modalidad_pago == 0){
      $sql0 = "UPDATE docto_ped_pago SET $campo=?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $valor,
          $orden_id
        )
      );
    }

    if($modalidad_pago > 0){
      $sql0 = "UPDATE docto_ped_pago SET modalidad_pago=?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $modalidad_pago,
          $orden_id
        )
      );
    }

    if($modalidad_pago == 2){
      $sql0 = "UPDATE docto_ped_pago SET tipo_pago=?
               WHERE orden_compra_id=? AND ISNULL(tipo_pago,0) = 0";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          2,
          $orden_id
        )
      );
    }

  $yes = array('msg'=>'OK','id'=>$id);
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
