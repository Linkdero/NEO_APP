<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  //include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $ped_tra=$_POST['ped_tra'];
  $estado_id=$_POST['estado_id'];
  $tipo_verificacion=$_POST['tipo_verificacion'];
  $chequeado=$_POST['chequeado'];

  $clased = new documento;

  $reng_actual=1;
  $reng = $clased->genera_correlativo_bitacora_pedido($ped_tra,$estado_id);
  if(!empty($reng['reng_num'])){
    $reng_actual=$reng['reng_num']+1;
  }

  $nombre_verificacion = $clased->get_nombre_verificacion_by_id($tipo_verificacion);

  /*$e = $clased->get_estado_pedido($ped_tra);
  $estado_id=(!empty($estado['Ped_status']))?$e['Ped_status']:0;*/

  $pdo = Database::connect_sqlsrv();
  $yes='';

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($chequeado==1){
      $sql = "INSERT INTO docto_pedido_seguimiento_bitacora (ped_tra,ped_tipo_seguimiento_id,ped_reng_num,persona_id,
                          ped_seguimiento_fecha,ped_observaciones,ped_tipo_seguimiento_status)
                          VALUES (?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($ped_tra,$estado_id,$reng_actual,$_SESSION['id_persona'],date('Y-m-d H:i:s'),'Se verificó correctamente documento: '.$nombre_verificacion['ped_seguimiento_nom'],1));

      $sql1 = "INSERT INTO docto_ped_seguimiento_detalle (ped_tra  ,ped_seguimiento_id ,ped_seguimiento_status,chequeado_fecha,chequeado_por)
              VALUES (?,?,?,?,?)";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array($ped_tra,$tipo_verificacion,1,date('Y-m-d H:i:s'),$_SESSION['id_persona']));


      $sql0 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_bitacora_id=? WHERE Ped_tra=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($estado_id,$ped_tra));

    }else{
      $sql = "INSERT INTO docto_pedido_seguimiento_bitacora (ped_tra,ped_tipo_seguimiento_id,ped_reng_num,persona_id,
                          ped_seguimiento_fecha,ped_observaciones,ped_tipo_seguimiento_status)
                          VALUES (?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($ped_tra,$estado_id,$reng_actual,$_SESSION['id_persona'],date('Y-m-d H:i:s'),'Se verificó que hay errores en el documento: '.$nombre_verificacion['ped_seguimiento_nom'],1));

      $sql1 = "DELETE FROM docto_ped_seguimiento_detalle WHERE ped_tra=?  AND ped_seguimiento_id=?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array($ped_tra,$tipo_verificacion));
    }


    $pdo->commit();
    echo 'OK';
    //echo json_encode($yes);
  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);
      echo json_encode($yes);
    }
  }

  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
