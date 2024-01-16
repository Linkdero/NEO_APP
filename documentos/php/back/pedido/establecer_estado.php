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
        $sql = "INSERT INTO docto_ped_seguimiento_asignado (ped_tra,id_persona_asignada,reng_num,status_id,fecha_asignacion, operador,id_tipo )
                            VALUES (?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($ped_tra,$id_persona,$ra,1,date('Y-m-d H:i:s'),$_SESSION['id_persona'],3));
        $motivo = 'Persona asignada para fase de cotización';
      }
      if($estado_id == 8139 && !empty($id_persona)){
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
        $sql = "INSERT INTO docto_ped_seguimiento_asignado (ped_tra,id_persona_asignada,reng_num,status_id,fecha_asignacion, operador,id_tipo )
                            VALUES (?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($ped_tra,$id_persona,$ra,1,date('Y-m-d H:i:s'),$_SESSION['id_persona'],2));
        $motivo = 'Persona asignada para fase de revisión';
      }
      $sql = "INSERT INTO docto_pedido_seguimiento_bitacora (ped_tra,ped_tipo_seguimiento_id,ped_reng_num,persona_id,
                          ped_seguimiento_fecha,persona_propietario_id,ped_observaciones,ped_tipo_seguimiento_status,ped_seguimiento_id)
                          VALUES (?,?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($ped_tra,$estado_id,$reng_actual,$_SESSION['id_persona'],date('Y-m-d H:i:s'),$id_persona,$motivo,1,$status));


      if($estado_id == 8157 || $estado_id == 8161 || $estado_id == 8148 || $estado_id == 8149 || $estado_id == 8145 || $estado_id == 8139){
        $sql0 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_bitacora_id=? WHERE Ped_tra=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($estado_id,$ped_tra));
      }else{
        $sql0 = "UPDATE APP_POS.dbo.PEDIDO_E SET Ped_status=?, Ped_bitacora_id=? WHERE Ped_tra=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($estado_id,0,$ped_tra));
      }

      if($estado_id == 9109){
        generarToken(8017,'APP_POS.dbo.PEDIDO_E',$ped_tra,$motivo);
      }


    $pdo->commit();
    $mail = array();

    $tipo = 0;

    if($estado_id == 8140 || $estado_id == 8141 || $estado_id == 8143 || $estado_id == 8144){
      $sql55 = "SELECT a.persona_id, b.persona_user, REPLACE(STR(a.Ped_num, 5), SPACE(1), '0') AS ped_num
                FROM APP_POS.dbo.PEDIDO_E a
                INNER JOIN rrhh_persona_usuario b ON  a.persona_id = b.id_persona
                WHERE a.Ped_tra = ?";
      $q55 = $pdo->prepare($sql55);
      $q55->execute(array($ped_tra));
      $correo = $q55->fetch();

      $sql77 = "SELECT b.persona_user
                FROM rrhh_persona_usuario b
                WHERE b.id_persona = ?";
      $q77 = $pdo->prepare($sql77);
      $q77->execute(array($_SESSION['id_persona']));
      $emisor = $q77->fetch();

      $destinatarios = $correo['persona_user'];
      $titulo = '';
      if($estado_id == 8140){
        $titulo = 'aprobado en Planificación';
      } else
      if($estado_id == 8141){
        $titulo = 'rechazado en Planificación';
      } else if($estado_id == 8143){
        $titulo = 'autorizado en Subsecretaría Administrativa';
      }else if($estado_id == 8144){
        $titulo = 'anulado en Subsecretaría Administrativa';
      }
      $subject = 'PEDIDO '.strtoupper($titulo);;
      $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el pedido No. <strong>'.$correo['ped_num'].'</strong> fue '.$titulo;
      $body.='<br>Siendo las: '.date('H:i:s').' del '.date('d-m-Y');
      $body.='<br><br>Favor revisarlo en el sistema para más información';

      $body.='<br><br><br>Correo enviado desde SAAS APP - Módulo control de Pedidos y Remesas';

      //echo $body;
      /*$insumos =*/ //documento::enviar_correo_estado("'".$destinatarios."'", "'".$subject."'", "'".$body."'");
      $mail = array(
        'emisor'=>$emisor['persona_user'],
        'receptor'=>$destinatarios,
        'subject'=>$subject,
        'body'=>$body,
      );
      $tipo = 1;
    }

    $yes = array('msg'=>'OK', 'id'=>$tipo,'mail'=>$mail);
    echo json_encode($yes);
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
