<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $orden_id=$_POST['orden_id'];
  $estado = $_POST['id_estado'];

  $empleado = (!empty($_POST['id_empleados_list'])) ? $_POST['id_empleados_list'] : NULL;
  $id_direccion = (!empty($_POST['id_direccion'])) ? $_POST['id_direccion'] : NULL;
  $observaciones = (!empty($_POST['obs'])) ? $_POST['obs'] : NULL;
  //$valor=$_POST['valor'];

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "UPDATE docto_ped_pago SET id_status=?
             WHERE orden_compra_id=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $estado,
        $orden_id
      )
    );

    if(!empty($id_direccion)){
      $sql0 = "UPDATE docto_ped_pago SET id_direccion=?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_direccion,
          $orden_id
        )
      );
    }

    documento::insert_bitacora_factura($orden_id, $estado ,$empleado, $observaciones,$estado,1,$id_direccion);

    $yes = array('msg'=>'OK','id'=>'');
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
