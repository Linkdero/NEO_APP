<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $clased = new documento;
  $orden_compra_id = "(".substr($_POST['orden_id_c'], 1).")";
  $nro_cheque = (!empty($_POST['nro_cheque'])) ? $_POST['nro_cheque'] : NULL;



  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "UPDATE docto_ped_pago SET cheque_nro = ?
             WHERE orden_compra_id IN $orden_compra_id";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $nro_cheque,
        $orden_compra_id
      )
    );

    $sql0 = "UPDATE ChequeDetalle SET chequeStatus = ?, chequeUtilizadoEn = ?, chequeUtilizadoPor = ?, chequeTipoUso = ?
             WHERE chequeId = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        2,
        date('Y-m-d H:i:s'),
        $_SESSION['id_persona'],
        1,
        $nro_cheque
      )
    );

    //$clased->insert_bitacora_factura($nro_cheque, NULL ,$creador, 'Se asignó número de cheque',NULL,1,NULL);

    $yes = array('msg'=>'OK','id'=>'', 'message'=>'Cheque asignado');
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
