<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  /*$creador = $_SESSION['id_persona'];

  $ped_num = (!empty($_POST['ped_num'])) ? $_POST['ped_num'] : NULL;
  $fecha = $_POST['fecha'];
  $unidad = $_POST['unidad'];
  $observaciones = $_POST['observaciones'];
  $pac_id = (!empty($_POST['pacName'])) ? $_POST['pacName'] : NULL;
  $urgente = (!empty($_POST['urgente']) == true) ? 1 : 0;
  $insumos = $_POST['insumos'];
  $fecha_cre = date('Y-m-d H:i:s');

  $clased = new documento;
  $uni_cor = $clased->get_unidad_pos($unidad);
  $u_cor = $uni_cor['Uni_cor'];

  if($_SESSION['id_persona']== 5449){
    $u_cor = 97;
  }
  $num_interno = 0;

  if($u_cor == 63){
    $ped_interno = $clased->genera_correlativo_pedido_interno($u_cor);
    if(!empty($ped_interno['num_interno'])){
      $num_interno = $ped_interno['num_interno']+1;
    }else{
      $num_interno = 1;
    }

  }

  $ped_automatico = $clased->genera_correlativo_pedido_automatico();

  $yes='';
  //$yes = array('msg'=>'ERROR','id'=>'');
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    $sql0 = "INSERT INTO APP_POS.dbo.PEDIDO_E (
      Ent_id,Ped_fec,Fh_prg,Uni_cor,Ser_ser,Ped_num,Tdoc_id,
      Ped_fop,Ped_obs,Ped_estdoc,Ped_estcom,
      Ped_justificacion,persona_id, Ped_num_interno,Pac_id,Ped_urgente
    )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      '00001',$fecha,1,$u_cor,$ped_automatico['pedido_serie'],$ped_automatico['pedido_num'],'013',$fecha_cre, $observaciones,
      'P','P',0,$creador,$num_interno,$pac_id, $urgente
    ));

    //$cg=$clased->get_correlativo_generado($creador);
    $cgid = $pdo->lastInsertId();

    $sql1 = "UPDATE APP_POS.dbo.PEDIDO_CD SET
      pedido_status = ?, ped_tra = ?, ped_utilizado_por = ?, ped_utilizado_en = ? WHERE pedido_num = ? ";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      1,
      $cgid,
      $creador,
      $fecha_cre,
      $ped_automatico['pedido_num']
    ));


    $x = 0;
    foreach($insumos AS $i){
      $x ++;
      $sql2 = "INSERT INTO APP_POS.dbo.PEDIDO_D (Ent_id, Ped_tra, Ppr_id, Pedd_can, Pedd_ord)
      values(?,?,?,?,?)";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array('00001',$cgid,$i['Ppr_id'],$i['Ppr_can'],$x));
    }


    $yes = array('msg'=>'OK','id'=>$cgid);
    $pdo->commit();
    //echo json_encode($yes);
    $destinatarios = 'helen.cuyan@saas.gob.gt; carmen.trejo@saas.gob.gt';
    $subject = 'PEDIDO CREADO';
    $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el pedido No. <strong>'.$ped_automatico['pedido_num'].'</strong> fue creado exitosamente';
    $body.='Siendo las: '.date('H:i:s',strtotime($fecha_cre)).' del '.date('d-m-Y', strtotime($fecha_cre));
    $body.='<br><br>Favor revisarlo';

    $body.='<br><br><br>Correo enviado desde SAAS APP - Módulo control de Pedidos y Remesas';

    /*$insumos =*/ //documento::enviar_correo_estado("'".$destinatarios."'", "'".$subject."'", "'".$body."'");


  /*}catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  echo json_encode($yes);

  Database::disconnect_sqlsrv();*/

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
