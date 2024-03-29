<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $orden_id=explode(',',$_POST['orden_id']);
  $id_persona = $_POST['id_persona'];
  $creador = $_SESSION['id_persona'];
  //echo $id_persona;

  $clased = new documento;

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo count($orden_id);
    foreach ($orden_id AS $value) {
      // code...
      //echo $value;
    $reng_actual=1;
      $reng = $clased->genera_correlativo_asignado_factura($value);
      if(!empty($reng['reng_num'])){
        $reng_actual=$reng['reng_num']+1;
        $sql = "UPDATE docto_ped_pago_asignado SET status = ? WHERE orden_compra_id = ? AND reng_num = ?";
        $q = $pdo->prepare($sql);
        $q->execute(
          array(
            0,
            $value,
            $reng['reng_num']
          )
        );

      }

      $sql0 = "INSERT INTO docto_ped_pago_asignado (orden_compra_id,id_persona, reng_num, status, asignado_en, asignado_por)
               VALUES (?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $value,
          $id_persona,
          $reng_actual,
          1,
          date('Y-m-d H:i:s'),
          $creador
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
