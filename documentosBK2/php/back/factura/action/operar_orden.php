<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $id_tipo = $_POST['id_tipo'];
  $id_clase_proceso = $_POST['id_clase_proceso'];
  $nro_orden = $_POST['nro_orden'];
  $id_campo = $_POST['id_campo'];
  $id_pago = $_POST['id_pago'];

  $message = '';

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    if($id_tipo == 0){
      $sql0 = "UPDATE docto_ped_pago SET cur = ?
               WHERE id_pago = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          $id_pago
        )
      );

      $sql0 = "UPDATE docto_ped_pago_presupuesto SET cur_compromiso = ?
               WHERE id_pago = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          $id_pago
        )
      );
      $message = 'CUR de compromiso asignado';
    }else
    if($id_tipo == 1){
      $sql0 = "UPDATE docto_ped_pago SET cur_devengado = ?
               WHERE id_pago = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          $id_pago
        )
      );
      $sql0 = "UPDATE docto_ped_pago_presupuesto SET cur_devengado = ?
               WHERE id_pago = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          $id_pago
        )
      );
      $message = 'CUR de devengado asignado';
    }


  $yes = array('msg'=>'OK','message'=>$message);
    //echo json_encode($yes);
     $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','message'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','message'=>$e2);

    }
  }
  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
