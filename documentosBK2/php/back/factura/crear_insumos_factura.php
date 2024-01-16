<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $orden_id=$_POST['orden_id'];
  $insumos=$_POST['insumos'];


  //$uni_cor = documento::get_unidad_pos($unidad);

  $reng_actual = 1;


  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ped_tra=0;
    $x = 0;
    foreach($insumos AS $i){
      $x ++;
      if($i['checked']== 'true'){
        $ped_tra = $i['Ped_tra'];
        //echo $i['v_rec']+$i['Pedd_canf'].' |-| ';
        $sql2 = "INSERT INTO docto_ped_pago_detalle (orden_compra_id, reng_num, Ppr_id, Ppr_can, Ppr_status)
        values(?,?,?,?,?)";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($orden_id, $x, $i['Ppr_id'],$i['v_rec'],1));

        $sql3 = "UPDATE APP_POS.dbo.PEDIDO_D SET Pedd_canf = ? WHERE Ped_tra=? AND Ppr_id = ?";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array(($i['v_rec']+$i['Pedd_canf']),$ped_tra, $i['Ppr_id']));
      }

    }

    $sql0 = "UPDATE docto_ped_pago SET status=?, asignado_en=?, asignado_por=?, ped_tra=?
             WHERE orden_compra_id=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        1,
        date('Y-m-d H:i:s'),
        $creador,
        $ped_tra,
        $orden_id
      )
    );


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
