<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $orden=$_POST['orden'];
  $tipo = $_POST['tipo'];
  $cur = $_POST['cur'];
  $estado = $_POST['estado'];
  $observaciones = $_POST['obs'];

  $campo = '';
  $modalidad_pago = 0;

  //echo $tipo;

  if($tipo == 1){
    $campo = 'cur';
  }
  if($tipo == 2){
    $campo = 'cur_devengado';
  }

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    $sql0 = "UPDATE docto_ped_pago SET $campo=?, id_status = ?
             WHERE nro_orden=? AND YEAR(asignado_en) = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $cur,
        $estado,
        $orden,
        date('Y')
      )
    );

    $sql1 = "SELECT orden_compra_id FROM docto_ped_pago
             WHERE nro_orden=? AND YEAR(asignado_en) = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(
      array(
        $orden,
        date('Y')
      )
    );
    $ordenes = $q1->fetchAll();

    foreach($ordenes AS $o){
      documento::insert_bitacora_factura($o['orden_compra_id'], $estado ,NULL, $observaciones,1,NULL);
    }



    $yes = array('msg'=>'OK','id'=>$id,'tipo'=>$tipo);

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
