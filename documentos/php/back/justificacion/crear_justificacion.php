<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $id_justificacion=$_POST['id_justificacion'];
  $id_pedido=$_POST['id_pedido'];
  $fecha_doc=date('Y-m-d',strtotime($_POST['fecha_pedido']));
  $id_especificaciones=$_POST['id_especificaciones'];
  $id_necesidad=$_POST['id_necesidad'];
  $id_temporalidad=$_POST['id_temporalidad'];
  $id_finalidad=$_POST['id_finalidad'];
  $id_resultado=$_POST['id_resultado'];
  $tipo_compra=$_POST['tipo_compra'];
  $tipo_diagnostico=$_POST['tipo_diagnostico'];
  $lista='';
  $categoria=8091;//justificacion de compra.

  $clase = new empleado;
  $clased = new documento;
  $e = $clase->get_empleado_by_id_ficha($creador);

  //obtiene el correlativo actual

  $correlativo_actual=1;
  $depto=(!empty($e['id_depto_funcional']))?$e['id_depto_funcional']:0;


  $ca=$clased->genera_correlativo_documento($e['id_dirf'],$depto,8091,1);
  if(!empty($ca['id'])){
    $correlativo_actual=$ca['id']+1;
  }
  //echo $e['id_dirf'].' '.$depto.' <br>';
  //echo $correlativo_actual;
  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO docto_encabezado (
      docto_titulo,
      docto_descripcion,
      docto_categoria,
      docto_direccion_id,
      docto_fecha,
      docto_correlativo,
      docto_year,
      docto_creador,
      docto_fechacreacion,
      docto_status,
      docto_depto_id,
      docto_tipo_emision,

      docto_nombre,
      docto_temporalidad,
      docto_finalidad,
      docto_resultados
    )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $id_justificacion,
      $id_especificaciones,
      $categoria,
      $e['id_dirf'],
      $fecha_doc,
      $correlativo_actual,
      date('Y'),
      $creador,
      date('Y-m-d H:i:s'),
      1,
      $depto,1,
      $id_necesidad,
      $id_temporalidad,
      $id_finalidad,
      $id_resultado));

    //$cg=$clased->get_correlativo_generado($creador);
    $cgid = $pdo->lastInsertId();

    $sql2 = "INSERT INTO docto_pedido (docto_id,pedido_tra,pedido_tipo,pedido_diagnostico,pedido_diagnostico_list)
    values(?,?,?,?,?)";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($cgid,$id_pedido,$tipo_compra,$tipo_diagnostico,$lista));

    $sql3 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_justificacion=? WHERE Ped_tra=?";
    $q3 = $pdo->prepare($sql3);
    $q3->execute(array(1,$id_pedido));

    $yes = array('msg'=>'OK','id'=>$cgid);
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
