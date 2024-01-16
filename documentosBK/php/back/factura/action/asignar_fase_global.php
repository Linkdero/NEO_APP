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
  $id_tipo_ope = $_POST['id_tipo_ope'];
  $id_forma = (!empty($_POST['id_forma'])) ? $_POST['id_forma'] : NULL;
  $clase_proceso = (!empty($_POST['clase_proceso'])) ? $_POST['clase_proceso'] : NULL;
  $nro_cheque = (!empty($_POST['nro_cheque'])) ? $_POST['nro_cheque'] : NULL;
  $nro_orden = (!empty($_POST['nro_orden'])) ? $_POST['nro_orden'] : NULL;
  $nro_cyd = (!empty($_POST['nro_cyd'])) ? $_POST['nro_cyd'] : NULL;



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

    if($id_tipo_ope == 2 || $id_tipo_ope == 1 && $id_forma == 2){

      $valor = ($clase_proceso == 1) ? $nro_orden : $nro_cyd;
      //echo $valor;
      $sql0 = "UPDATE docto_ped_pago SET nro_orden = ?, clase_proceso = ?, year = ?
               WHERE orden_compra_id IN $arreglo";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $valor,
          $clase_proceso,
          date('Y')
        )
      );
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
