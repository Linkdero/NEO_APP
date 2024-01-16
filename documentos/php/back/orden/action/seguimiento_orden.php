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
  $arreglo = $_POST['arreglo'];
  $id_bitacora = $_POST['id_bitacora'];
  $id_seguimiento = ($_POST['id_seguimiento'] > 0) ? $_POST['id_seguimiento'] : NULL;
  $respuesta = $_POST['respuesta'];
  $registros=explode(',',$_POST['arreglo']);
  $observaciones = (!empty($_POST['observaciones'])) ? $_POST['observaciones'] : NULL;
  $persona = (!empty($_POST['id_empleados_list'])) ? $_POST['id_empleados_list'] : NULL;

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo count($orden_id);


    $sql10 = "INSERT INTO DoctoPedPagoPresupuestoBitacora (id_bitacora,id_seguimiento,operador_por,operado_en,observaciones,persona_responsable,oficio_nro,oficio_fecha) VALUES (?,?,?,?,?,?,?,?)";
    $q10 = $pdo->prepare($sql10);
    $q10->execute(array($id_bitacora,$id_seguimiento,$creador,$creado_en,$observaciones,$persona,NULL,NULL));

    $cgid = $pdo->lastInsertId();

    foreach ($registros AS $value) {
      if(!empty($value)){
        $sql10 = "UPDATE docto_ped_pago_presupuesto SET id_bitacora = ?, id_seguimiento = ? WHERE id_pago = ?";
        $q10 = $pdo->prepare($sql10);
        $q10->execute(
          array(
            $id_bitacora,$id_seguimiento,$value
          )
        );

        $sql10 = "INSERT INTO DoctoPedPagoPresupuestoBitacoraDet (id_control,id_pago,reng_num,operado_en,operado_por)
                  VALUES (?,?,?,?,?)";
        $q10 = $pdo->prepare($sql10);
        $q10->execute(
          array(
            $cgid,
            $value,
            1,
            $creado_en,
            $creador

          )
        );
      }
    }
    $yes = array('msg'=>'OK','id'=>'','message'=>$respuesta);
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
