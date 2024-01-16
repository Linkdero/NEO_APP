<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $orden_id=$_POST['id'];
  //$npg = (!empty($_POST['npg'])) ? $_POST['npg'] : NULL;
  $estado = 8350;
  $clased = new documento;

  //$valor=$_POST['valor'];

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "UPDATE docto_ped_pago SET status=?, id_status = ?
             WHERE orden_compra_id=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        5,
        $estado,
        $orden_id
      )
    );

    $sql0 = "UPDATE DoctoPedPagoPresupuestoDetalle SET idStatus = ?
             WHERE idFactura=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        2,
        $orden_id
      )
    );

    $insumos = $clased->get_insumos_by_factura($orden_id);

    if(!empty($insumos)){
      foreach($insumos as $i){
        $ped_tra = (!empty($i['Ped_tra'])) ? $i['Ped_tra'] : NULL;
        $sql3 = "UPDATE APP_POS.dbo.PEDIDO_D SET Pedd_canf = ISNULL(Pedd_canf,0) - ? WHERE Ped_tra=? AND Ppr_id = ?";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($i['Ppr_can'],$ped_tra, $i['Ppr_id']));
      }
    }


    $clased->insert_bitacora_factura($orden_id, $estado ,NULL, 'Factura Anulada',1,NULL,NULL);

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
