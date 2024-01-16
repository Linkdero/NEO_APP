<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  //include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $ped_tra=$_POST['ped_tra'];
  $estado_id=$_POST['estado_id'];
  $id_persona=(!empty($_POST['id_persona']))?$_POST['id_persona']:0;
  $motivo=$_POST['motivo'];

  $clased = new documento;

  $reng_actual=1;
  $reng = $clased->genera_correlativo_bitacora_pedido($ped_tra,$estado_id);
  if(!empty($reng['reng_num'])){
    $reng_actual=$reng['reng_num']+1;
  }

  $state = $clased->get_estado_pedido($ped_tra);

  $status = (!empty($state['Ped_status']))?$state['Ped_status']:0;

  /*$e = $clased->get_estado_pedido($ped_tra);
  $estado_id=(!empty($estado['Ped_status']))?$e['Ped_status']:0;*/

  $pdo = Database::connect_sqlsrv();
  $yes='';

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if($estado_id == 8145){
        $ra=1;
        $reng_ = $clased->genera_correlativo_persona_asignada($ped_tra);
        if(!empty($reng_['reng_num'])){
          $ra=$reng_['reng_num']+1;
          if(!empty($reng_['reng_num'])){
            //$ra=$reng_['reng_num']+1;
            $sqlp = "UPDATE docto_ped_seguimiento_asignado SET status_id = ? WHERE reng_num = ? AND ped_tra = ?";
            $qp = $pdo->prepare($sqlp);
            $qp->execute(array(0,$reng_['reng_num'],$ped_tra));
          }
        }
        $sql = "INSERT INTO docto_ped_seguimiento_asignado (ped_tra,id_persona_asignada,reng_num,status_id,fecha_asignacion, operador )
                            VALUES (?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($ped_tra,$id_persona,$ra,1,date('Y-m-d H:i:s'),$_SESSION['id_persona']));
        $motivo = 'Persona asignada para fase de cotización';
      }
      $sql = "INSERT INTO docto_pedido_seguimiento_bitacora (ped_tra,ped_tipo_seguimiento_id,ped_reng_num,persona_id,
                          ped_seguimiento_fecha,persona_propietario_id,ped_observaciones,ped_tipo_seguimiento_status,ped_seguimiento_id)
                          VALUES (?,?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($ped_tra,$estado_id,$reng_actual,$_SESSION['id_persona'],date('Y-m-d H:i:s'),$id_persona,$motivo,1,$status));


      if($estado_id == 8157 || $estado_id == 8161 || $estado_id == 8148 || $estado_id == 8149 || $estado_id == 8145){
        $sql0 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_bitacora_id=? WHERE Ped_tra=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($estado_id,$ped_tra));
      }else{
        $sql0 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_status=?, Ped_bitacora_id=? WHERE Ped_tra=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($estado_id,0,$ped_tra));
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
