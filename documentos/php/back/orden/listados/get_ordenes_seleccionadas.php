<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];
  $arreglo = "(".substr($_GET['arreglo'],1).")";

  $clased = new documento;
  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    $sql0 = "SELECT * FROM docto_ped_pago_presupuesto WHERE id_pago IN $arreglo";
    $q0 = $pdo->prepare($sql0);
    $q0->execute();
    $ordenes = $q0->fetchAll();

    $data = array();
    /*if(count($ordenes) == 1){
      $tipoRegistro = $clased->retornaTipoOrden($ordenes[0]['id_tipo_pago']);
      $sub_array = array(
        'id_pago'=>$ordenes[0]['id_pago'],
        'tipo_pago'=>$tipoRegistro['tipo_pago'],
        'nro_registro'=>$ordenes[0]['nro_registro'],
        'id_year'=>$ordenes[0]['id_year']
      );
      $data = $sub_array;
    }else{*/
      foreach ($ordenes as $key => $o) {
        // code...
        $tipoRegistro = $clased->retornaTipoOrden($o['id_tipo_pago']);

        $sub_array = array(
          'id_pago'=>$o['id_pago'],
          'tipo_pago'=>$tipoRegistro['tipo_pago'],
          'nro_registro'=>$o['nro_registro'],
          'id_year'=>$o['id_year']
        );
        $data[] = $sub_array;
      }

    //}

    echo json_encode($data);
    //$pdo->commit();

  }catch (PDOException $e){
    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);
    }
    echo json_encode($yes);
  }


  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
