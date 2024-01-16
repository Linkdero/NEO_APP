<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  include_once '../../../../../empleados/php/back/functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_director_financiero = (!empty($_POST['id_director_financiero'])) ? $_POST['id_director_financiero'] : NULL;
  $id_jefes_compras = (!empty($_POST['id_jefes_compras'])) ? $_POST['id_jefes_compras'] : NULL;
  $id_tipo_pago = (!empty($_POST['id_tipo_pago'])) ? $_POST['id_tipo_pago'] : NULL;
  $id_fecha_acta = (!empty($_POST['id_fecha_acta'])) ? date('Y-m-d H:i:s', strtotime($_POST['id_fecha_acta'])) : NULL;
  $id_monto = (!empty($_POST['id_monto'])) ? $_POST['id_monto'] : NULL;
  $id_proveedor = (!empty($_POST['id_proveedor'])) ? $_POST['id_proveedor'] : NULL;
  $id_ped_tra = (!empty($_POST['id_ped_tra'])) ? $_POST['id_ped_tra'] : NULL;
  $docto_obs = (!empty($_POST['docto_obs'])) ? $_POST['docto_obs'] : NULL;

  $id_tipo_seleccion = (!empty($_POST['id_tipo_seleccion'])) ? $_POST['id_tipo_seleccion'] : NULL;
  $id_tipo_compra = (!empty($_POST['id_tipo_compra'])) ? $_POST['id_tipo_compra'] : NULL;

  $creador = $_SESSION['id_persona'];

  $newDate = strtotime('+10 minute', strtotime($id_fecha_acta));
  $finalizacion = date('Y-m-d H:i:s', $newDate);
  //echo $finalizacion;

  $yes='';
  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $val_nuevo = array(
      'id_director_financiero'=>$id_director_financiero,
      'id_jefes_compras'=>$id_jefes_compras,
      'id_tipo_pago'=>$id_tipo_pago,
      'id_fecha_acta'=>$id_fecha_acta,
      'id_monto'=>$id_monto,
      'id_proveedor'=>$id_proveedor,
      'id_ped_tra'=>$id_ped_tra,
      'docto_obs'=>$docto_obs,
    );

    $sql0 = "INSERT INTO APP_POS.dbo.PEDIDO_A (acta_fecha, acta_justificacion, acta_monto,
      nit_proveedor, id_director, id_jefe, id_tecnico, id_modalidad, id_tipo_pago, acta_finalizacion,
             creado_por, creado_en, id_tipo_adjudicacion, id_tipo_compra) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $id_fecha_acta, $docto_obs, $id_monto, $id_proveedor, $id_director_financiero, $id_jefes_compras, $creador, 1, $id_tipo_pago, $finalizacion, $creador, date('Y-m-d H:i:s'),$id_tipo_seleccion,$id_tipo_compra
    ));

    $cgid = $pdo->lastInsertId();

    foreach ($id_ped_tra AS $value) {
      // code...
      $sql0 = "INSERT INTO APP_POS.dbo.PEDIDO_AD (acta_id, ped_tra) VALUES (?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array(
        $cgid, $value
      ));
      $sql0 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_justificacion = ? WHERE Ped_tra = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array(
        1, $value
      ));
    }


    $valor_anterior = array(
      'valor_inicial'=>NULL,

    );

    $valor_nuevo = array(
      'acta_id'=>$cgid,
      'valor'=>$val_nuevo
    );

    $log = "VALUES(325, 8017, 'APP_POS.dbo.PEDIDO_A', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());

    $yes = array('msg'=>'OK','valor_nuevo'=>'', 'message' => 'Acta generada');
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
