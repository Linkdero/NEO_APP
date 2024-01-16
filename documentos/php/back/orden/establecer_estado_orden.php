<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $id_pago = $_POST['id_pago'];
  $estado = $_POST['estado'];



  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    $sql0 = "SELECT id_bitacora, id_seguimiento FROM docto_ped_pago_presupuesto
             WHERE id_pago = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $id_pago
      )
    );
    $info = $q0->fetch();

    if($info['id_bitacora'] == 1 || ($info['id_bitacora'] == 3 && $info['id_seguimiento'] == 13))
    {
      $sql0 = "UPDATE docto_ped_pago_presupuesto SET id_status = ?
               WHERE id_pago = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $estado,
          $id_pago
        )
      );

      if($estado == 3){
        $sql0 = "UPDATE docto_ped_pago SET id_pago = NULL, nro_orden = NULL, year = NULL, clase_proceso = NULL
                 WHERE id_pago = ?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_pago
          )
        );

      }

      $yes = array('msg'=>'OK','id'=>'','message'=>'Registro anulado.','icono'=>'success');
      //echo json_encode($yes);
       $pdo->commit();
    }else{
      $yes = array('msg'=>'OK','id'=>'','message'=>'No se puede anular, recarge su tabla','icono','error');
    }



    /*if($id_tipo_ope == 1){
      $sql0 = "UPDATE docto_ped_pago SET modalidad_pago = ?
               WHERE orden_compra_id IN $arreglo";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_forma
        )
      );
    }

    if($id_forma == 1){
      $sql0 = "UPDATE docto_ped_pago SET cheque_nro = ?
               WHERE orden_compra_id IN $arreglo";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $nro_cheque
        )
      );
    }

    if($id_tipo_ope == 3 || $id_tipo_ope == 2 || $id_tipo_ope == 1 && $id_forma == 2){

      if ($clase_proceso == 1){
        $valor = $nro_orden;
      }else if($clase_proceso == 2 || $clase_proceso == 3){
        $valor = $nro_cyd;
      }
      //echo $valor;

      //inicio
      $id_pago = NULL;
      $sql0 = "SELECT id_pago, nro_registro FROM docto_ped_pago_presupuesto WHERE id_tipo_pago = ? AND id_year = ? AND nro_registro = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $clase_proceso,
          date('Y'),
          $valor
        )
      );
      $existe = $q0->fetch();
      if(empty($existe['nro_registro'])){
        $sql0 = "INSERT INTO docto_ped_pago_presupuesto (id_tipo_pago, id_year, id_status, creado_en, creado_por,nro_registro)
                 VALUES(?,?,?,?,?,?)";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $clase_proceso,
            date('Y'),
            1,
            $creado_en,
            $_SESSION['id_persona'],
            $valor
          )
        );

        $id_pago = $pdo->lastInsertId();
      }else{
        $id_pago = $existe['id_pago'];
      }
      //final
      $sql0 = "UPDATE docto_ped_pago SET nro_orden = ?, clase_proceso = ?, year = ?, id_pago = ?
               WHERE orden_compra_id IN $arreglo";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $valor,
          $clase_proceso,
          date('Y'),
          $id_pago
        )
      );



    }*/



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
