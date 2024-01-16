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

  $ped_tra=$_POST['pk'];
  $campo=$_POST['name'];
  $valor=strtoupper($_POST['value']);

  $yes='';
  $pdo = Database::connect_sqlsrv();
  echo $ped_tra .' | '.$campo. ' | '.$valor;
  try{
    /*$pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT $campo FROM APP_POS.dbo.PAC_E WHERE pac_id = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $pac_id
    ));
    $ant = $q0->fetch();

    $sql1 = "UPDATE APP_POS.dbo.PAC_E SET $campo = ? WHERE pac_id = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      $valor,$pac_id
    ));
    $valor_anterior = array(
      'pac_id'=>$pac_id,
      'campo'=>$campo,
      'valor'=>$ant[$campo]//$estado_actual['id_status']
    );

    $valor_nuevo = array(
      'pac_id'=>$pac_id,
      'campo'=>$campo,
      'fecha'=>date('Y-m-d H:i:s'),
      'valor'=>$valor
    );

    $log = "VALUES(325, 8017, 'APP_POS.dbo.PAC_E', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $yes = array('msg'=>'OK','valor_nuevo'=>$valor);
    //echo json_encode($yes);
    $pdo->commit();*/
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
