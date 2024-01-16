<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];
  $creado_en = date('Y-m-d H:i:s');
  $estado = 8338;
  $status = 0;
  $tipo = $_POST['id_tipo'];

  if($tipo == 5){
    $estado = 8341;
    $status = 1;
  }

  $orden_id=explode(',',$_POST['id_facts']);
  $empleado = (!empty($_POST['id_empleados_list'])) ? $_POST['id_empleados_list'] : NULL;
  $id_direccion = (!empty($_POST['id_direccion'])) ? $_POST['id_direccion'] : NULL;
  $observaciones = (!empty($_POST['obs'])) ? $_POST['obs'] : NULL;
  $id_oficio = (!empty($_POST['id_oficio'])) ? $_POST['id_oficio'] : NULL;
  $fecha_oficio = (!empty($_POST['fecha_oficio'])) ? $_POST['fecha_oficio'] : NULL;

  $creador = $_SESSION['id_persona'];
  //echo $id_persona;

  $clased = new documento;

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo count($orden_id);
    $sql10 = "INSERT INTO DoctoPedPagoOficioEnvioDir (oficionNum,oficioFecha,oficioDescripcion,oficioFacturas,operadoPor,operadoEn,direccionId, personaId, oficioStatus, movimientoId  )
              VALUES (?,?,?,?,?,?,?,?,?,?)";
    $q10 = $pdo->prepare($sql10);
    $q10->execute(
      array(
        $id_oficio,
        $fecha_oficio,
        $observaciones,
        $_POST['id_facts'],
        $creador,
        $creado_en,
        $id_direccion,
        $empleado,
        $estado,
        $status
      )
    );

    $cgid = $pdo->lastInsertId();

    foreach ($orden_id AS $value) {
      // code...
      //echo $value;
      if(!empty($value)){
        $sql0 = "UPDATE docto_ped_pago SET id_direccion=?, id_status=?
                 WHERE orden_compra_id=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_direccion,
            $estado,
            $value
          )
        );

        $sql10 = "INSERT INTO DoctoPedPagoOficioEnvioDirDet (oficioId, facturaId, estado )
                  VALUES (?,?,?)";
        $q10 = $pdo->prepare($sql10);
        $q10->execute(
          array(
            $cgid,
            $value,
            0
          )
        );
      }

      $clased->insert_bitacora_factura($value, $estado ,$empleado, $observaciones,$estado,1,$id_direccion);
    }




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
