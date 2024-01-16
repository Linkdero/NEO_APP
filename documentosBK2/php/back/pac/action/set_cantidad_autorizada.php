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

  $pac_id=$_POST['pac_id'];
  $id_mes = $_POST['id_mes'];
  $cantidad = $_POST['cantidad'];
  $monto = $_POST['monto'];


  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT cantidad_real, monto_real FROM APP_POS.dbo.PAC_D WHERE pac_id = ? AND pac_id_mes = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $pac_id
      $id_mes
    ));
    $ant = $q0->fetch();

    $sql2 = "UPDATE APP_POS.dbo.PAC_D
               SET cantidad_real = ?, monto_real = ?, id_status = ?
               WHERE pac_id = ? AND pac_id_mes = ?";
    $q2 = $pdo->prepare($sql2);

    $valor_anterior = array(
      'pac_id'=>$pac_id,
      'id_mes'=>$id_mes,
      'cantidad'=>$ant['cantidad_real'],
      'monto'=>$ant['monto_real']//$estado_actual['id_status']
    );

    $valor_nuevo = array(
      'pac_id'=>$pac_id,
      'id_mes'=>$id_mes,
      'cantidad'=>$cantidad,
      'monto'=>$monto,
      'fecha'=>date('Y-m-d H:i:s'),
    );

    $log = "VALUES(325, 8017, 'APP_POS.dbo.PAC_D', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $yes = array('msg'=>'OK','valor_nuevo'=>$valor);
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
