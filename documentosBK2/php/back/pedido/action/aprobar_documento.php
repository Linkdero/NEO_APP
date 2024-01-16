<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  include_once '../../../../../empleados/php/back/functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $ped_tra=$_POST['ped_tra'];
  $reng_num = $_POST['reng_num'];
  $observaciones = $_POST['docto_obs'];
  $tipo = $_POST['tipo'];
  $fecha = date('Y-m-d H:i:s');

  $yes='';
  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT id_status, observaciones, revisado_por, revisado_en FROM docto_ped_doctos_respaldo WHERE ped_tra = ? AND reng_num = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $ped_tra, $reng_num
    ));
    $ant = $q0->fetch();

    $sql1 = "UPDATE docto_ped_doctos_respaldo SET id_status = ?, observaciones = ?, revisado_por = ?, revisado_en = ? WHERE ped_tra = ? AND reng_num = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      $tipo,$observaciones,$_SESSION['id_persona'],$fecha,$ped_tra, $reng_num
    ));
    $valor_anterior = array(
      'ped_tra'=>$ped_tra,
      'reng_num'=>$reng_num,
      'valor'=>''
    );

    $valor_nuevo = array(
      'ped_tra'=>$ped_tra,
      'campo'=>'observaciones',
      'fecha'=>$fecha,
      'valor'=>$observaciones
    );

    $log = "VALUES(325, 8017, 'docto_ped_doctos_respaldo', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $message = ($tipo == 2) ? 'Documento aprobado' : 'Documento anulado';
    $message2 = ($tipo == 2) ? 'aprobado' : 'anulado';
    $yes = array('msg'=>'OK','valor_nuevo'=>$observaciones, 'message' => $message);
    //echo json_encode($yes);
    $pdo->commit();

    $sql55 = "SELECT a.persona_id, b.persona_user, REPLACE(STR(a.Ped_num, 5), SPACE(1), '0') AS ped_num
              FROM APP_POS.dbo.PEDIDO_E a
              INNER JOIN rrhh_persona_usuario b ON  a.persona_id = b.id_persona
              WHERE a.Ped_tra = ?";
    $q55 = $pdo->prepare($sql55);
    $q55->execute(array($ped_tra));
    $correo = $q55->fetch();

    $destinatarios = $correo['persona_user'];

    $subject = $message.' - PYR: '.$correo['ped_num'];
    $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el documento de respaldo en el Pedido No. <strong>'.$correo['ped_num'].'</strong> fue '.$message2;
    $body.='<br>Siendo las: '.date('H:i:s').' del '.date('d-m-Y');
    $body.='<br><br>Favor revisarlo en el sistema para más información';

    $body.='<br><br><br>Correo enviado desde SAAS APP - Módulo control de Pedidos y Remesas';

    //echo $body;
    /*$insumos =*/ documento::enviar_correo_estado("'".$destinatarios."'", "'".$subject."'", "'".$body."'");

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
