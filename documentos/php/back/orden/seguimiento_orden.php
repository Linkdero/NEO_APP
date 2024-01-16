<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  //ORDEN ANULADA 0 anulado // liberar facturas
  //1 creado,
  //2 enviado presupuesto cur compromiso,
  //3 recibido presupuesto cur compromiso,
  //4 devuelto a compras errores,
  //5 devuelto compras anexo,
  //6 compras anexo,
  //7 entregado presupuesto cur devengado
  //8 recibido presupuesto  cur devengado
  //9 entragdo cur aprobacion
  //10 cur aprobado
  //11 solicitud de pago



  $creador = $_SESSION['id_persona'];

  $clased = new documento;
  $estado = $_POST['estado_nuevo'];
  $oficio = (!empty($_POST['oficio'])) ? $_POST['oficio'] : NULL;
  $fecha = (!empty($_POST['fecha'])) ? date(date('Y-m-d H:i:s', strtotime($_POST['fecha'])) : NULL;
  $registros = $_POST['registros'];

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO DoctoPedPagoPresupuestoBitacora (id_pago, tipo_operacion, operado_en, operado_por, id_seguimiento_padre, observaciones, persona_encargada)
             VALUES (?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $nro_cheque,
        $orden_compra_id
      )
    );

    $sql0 = "UPDATE docto_ped_pago SET cheque_nro = ?
             WHERE orden_compra_id = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $nro_cheque,
        $orden_compra_id
      )
    );

    //$clased->insert_bitacora_factura($nro_cheque, NULL ,$creador, 'Se asignó número de cheque',NULL,1,NULL);

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
