<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $arreglo = "(".$_POST['id_arreglo'].")";
  $facturas=explode(',',$_POST['id_arreglo']);
  $id_tipo_ope = $_POST['id_tipo_ope'];
  $id_forma = (!empty($_POST['id_forma'])) ? $_POST['id_forma'] : NULL;
  $clase_proceso = (!empty($_POST['clase_proceso'])) ? $_POST['clase_proceso'] : NULL;
  $nro_cheque = (!empty($_POST['nro_cheque'])) ? $_POST['nro_cheque'] : NULL;
  $nro_orden = (!empty($_POST['nro_orden'])) ? $_POST['nro_orden'] : NULL;
  $nro_cyd = (!empty($_POST['nro_cyd'])) ? $_POST['nro_cyd'] : NULL;
  $creado_en = date('Y-m-d H:i:s');

  if($clase_proceso == 2){
    $nro_cyd = (!empty($_POST['nro_COM-DEV'])) ? $_POST['nro_COM-DEV'] : NULL;
  }

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    if($id_tipo_ope == 1){
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
        $sql0 = "INSERT INTO docto_ped_pago_presupuesto (id_tipo_pago, id_year, id_status, creado_en, creado_por,nro_registro,id_bitacora)
                 VALUES(?,?,?,?,?,?,?)";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $clase_proceso,
            date('Y'),
            1,
            $creado_en,
            $_SESSION['id_persona'],
            $valor,
            1
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
      foreach ($facturas AS $value) {
        // code...
        if(is_numeric($value)){
          $sql0 = "INSERT INTO DoctoPedPagoPresupuestoDetalle (idPago, idFactura, idStatus)
                   VALUES(?,?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(
            array(
              $id_pago,
              $value,
              1
            )
          );
        }
      }

      if(empty($existe['nro_registro'])){

        $sql10 = "INSERT INTO DoctoPedPagoPresupuestoBitacora (id_bitacora,id_seguimiento,operador_por,operado_en,observaciones,persona_responsable,oficio_nro,oficio_fecha) VALUES (?,?,?,?,?,?,?,?)";
        $q10 = $pdo->prepare($sql10);
        $q10->execute(array(1,NULL,$creador,$creado_en,NULL,NULL,NULL,NULL));

        $cgid = $pdo->lastInsertId();
        $sql10 = "INSERT INTO DoctoPedPagoPresupuestoBitacoraDet (id_control,id_pago,reng_num,operado_en,operado_por)
                  VALUES (?,?,?,?,?)";
        $q10 = $pdo->prepare($sql10);
        $q10->execute(
          array(
            $cgid,
            $id_pago,
            1,
            $creado_en,
            $creador
          )
        );
      }



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
