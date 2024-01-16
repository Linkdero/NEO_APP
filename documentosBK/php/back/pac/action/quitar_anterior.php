<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  include_once '../../../../../empleados/php/back/functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $pac_id=$_GET['pac_id'];
  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT pac_ejercicio_ant,pac_ejercicio_ant_desc FROM APP_POS.dbo.PAC_E WHERE pac_id = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $pac_id
    ));
    $ant = $q0->fetch();

    $sql1 = "UPDATE APP_POS.dbo.PAC_E SET pac_ejercicio_ant = ?, pac_ejercicio_ant_desc = ? WHERE pac_id = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      NULL,NULL,$pac_id
    ));


    $valor_anterior = array(
      'pac_id'=>$pac_id,
      'valor'=>'EJERCICIO: '.$ant['pac_ejercicio_ant'].', DESCRIPCION: '.$ant['pac_ejercicio_ant_desc']
    );

    $valor_nuevo = array(
      'pac_id'=>$pac_id,
      'campo'=>'pac_ejercicio_ant, pac_ejercicio_ant_desc',
      'fecha'=>date('Y-m-d H:i:s'),
      'valor'=>'Se quitó de ejercicio anterior'
    );

    $log = "VALUES(325, 8017, 'APP_POS.dbo.PAC_E', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());

    $yes = array('msg'=>'OK','valor_nuevo'=>'');

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
