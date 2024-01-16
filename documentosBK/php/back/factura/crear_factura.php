<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $fecha_factura=date('Y-m-d', strtotime($_POST['fecha_factura']));
  $factura_serie=$_POST['factura_serie'];
  $factura_nro=$_POST['factura_nro'];
  $factura_monto=floatval($_POST['factura_monto']);
  $id_nog=(!empty($_POST['id_nog']))?$_POST['id_nog']:NULL;
  $proveedor=$_POST['proveedor'];
  $id_modalidad = (!empty($_POST['id_modalidad'])) ? $_POST['id_modalidad'] : NULL;
  $pedido_interno=(!empty($_POST['pedido_interno']))?$_POST['pedido_interno']:NULL;
  $regimen = (!empty($_POST['id_regimen'])) ? $_POST['id_regimen'] : NULL;


  //$uni_cor = documento::get_unidad_pos($unidad);
  $clased = new documento;
  $p_i = $clased->get_pedido_by_pedido_interno($pedido_interno,date('Y'));
  $ped_tra = (!empty($p_i['Ped_tra']))?$p_i['Ped_tra']:NULL;

  $reng_actual = 1;

  //echo $ped_tra;


  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO docto_ped_pago (status, asignado_en, asignado_por, reng_num, id_status, factura_fecha, factura_serie,
             factura_num, factura_total,proveedor_id,ped_nog, ped_tra, pedido_interno,regimen_proveedor)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        1,
        date('Y-m-d H:i:s'),
        $creador,
        $reng_actual,
        8337,
        $fecha_factura,
        $factura_serie,
        $factura_nro,
        $factura_monto,
        $proveedor,
        $id_nog,
        $ped_tra,
        $pedido_interno,
        $regimen
      )
    );

    $cgid = $pdo->lastInsertId();

    if($id_modalidad == 1){
      $sql0 = "UPDATE docto_ped_pago SET tipo_pago=?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_modalidad,
          $cgid
        )
      );
    }else{
      $sql0 = "UPDATE docto_ped_pago SET tipo_pago=?, modalidad_pago = ?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_modalidad,
          $id_modalidad,
          $cgid
        )
      );
    }

    $clased->insert_bitacora_factura($cgid, 8337,NULL, 'Registro de Factura',1,NULL);


  $yes = array('msg'=>'OK','id'=>'');
    echo json_encode($yes);
     $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  //echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
