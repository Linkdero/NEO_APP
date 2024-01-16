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

  $ped_tra = $_POST['ped_tra'];
  $ppr_id = $_POST['ppr_id'];
  $cantidad = $_POST['cantidad'];
  $total_lineas = $_POST['total_lineas'];
  $lineas_insumo = $_POST['lineas_insumo'];

  $yes='';
  $pdo = Database::connect_sqlsrv();
  //echo $ped_tra .' | '.$campo. ' | '.$valor;
  $sqlx = "SELECT Ppr_id FROM APP_POS.dbo.PEDIDO_D WHERE ped_tra = ? AND Ppr_id = ?";
  $qx = $pdo->prepare($sqlx);
  $qx->execute(array(
    $ped_tra,
    $ppr_id
  ));
  $existente = $qx->fetch();

  if(empty($existente['Ppr_id'])){
    if($total_lineas+$lineas_insumo < 108){
      try{
        $pdo->beginTransaction();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $orden = 1;
        $sql0 = "SELECT TOP 1 Pedd_ord FROM APP_POS.dbo.PEDIDO_D WHERE ped_tra = ?
                ORDER BY Pedd_ord DESC";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          $ped_tra
        ));
        $ord = $q0->fetch();

        if(!empty($ord['Pedd_ord'])){
          $orden +=$ord['Pedd_ord'];
        }

        $sql0 = "INSERT INTO APP_POS.dbo.PEDIDO_D (Ent_id, Ped_tra, Ppr_id, Pedd_can, Pedd_ord) VALUES (?,?,?,?,?)";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          '00001',
          $ped_tra,
          $ppr_id,
          $cantidad,
          $orden
        ));
        /*$ant = $q0->fetch();

        $sql1 = "UPDATE APP_POS.dbo.PEDIDO_E SET $campo = ? WHERE ped_tra = ?";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(
          $valor,$ped_tra
        ));
        $valor_anterior = array(
          'ped_tra'=>$ped_tra,
          'campo'=>$campo,
          'valor'=>$ant[$campo]//$estado_actual['id_status']
        );

        $valor_nuevo = array(
          'ped_tra'=>$ped_tra,
          'campo'=>$campo,
          'fecha'=>date('Y-m-d H:i:s'),
          'valor'=>$valor
        );

        $log = "VALUES(325, 8017, 'APP_POS.dbo.PEDIDO_E', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
        $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
        $q = $pdo->prepare($sql);
        $q->execute(array());
        $val = '';
        if($campo == 'Ped_fec'){
          $val = fecha_dmy($valor);
        }else{
          $val=strtoupper($valor);
        }*/
        $yes = array('msg'=>'OK','valor_nuevo'=>'','message'=>'Insumo agregado');
        //echo json_encode($yes);
        $pdo->commit();
      }catch (PDOException $e){

        $yes = array('msg'=>'ERROR','message'=>$e);
        //echo json_encode($yes);
        try{ $pdo->rollBack();}catch(Exception $e2){
          $yes = array('msg'=>'ERROR','message'=>$e2);
        }
      }

    }else{
      $yes = array('msg'=>'ERROR','message'=>'El insumo que desea agregar no se agregó porque supera el espacio en el formulario electrónico');
    }

  }else{
    $yes = array('msg'=>'ERROR','message'=>'El insumo ya está en la lista');
  }

  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
