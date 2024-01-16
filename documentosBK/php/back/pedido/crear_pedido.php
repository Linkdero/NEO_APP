<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $ped_num = $_POST['ped_num'];
  $fecha = $_POST['fecha'];
  $unidad = $_POST['unidad'];
  $observaciones = $_POST['observaciones'];
  $pac_id = (!empty($_POST['pacName'])) ? $_POST['pacName'] : NULL;
  $insumos = $_POST['insumos'];
  $fecha_cre = date('Y-m-d H:i:s');

  $clased = new documento;
  $uni_cor = $clased->get_unidad_pos($unidad);

  $num_interno = 0;

  if($uni_cor['Uni_cor'] == 63){
    $ped_interno = $clased->genera_correlativo_pedido_interno($uni_cor['Uni_cor']);
    if(!empty($ped_interno['num_interno'])){
      $num_interno = $ped_interno['num_interno']+1;
    }else{
      $num_interno = 1;
    }

  }

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO APP_POS.dbo.PEDIDO_E (
      Ent_id,Ped_fec,Fh_prg,Uni_cor,Ser_ser,Ped_num,Tdoc_id,
      Ped_fop,Ped_obs,Ped_estdoc,Ped_estcom,
      Ped_justificacion,persona_id, Ped_num_interno,Pac_id
    )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      '00001',$fecha,1,$uni_cor['Uni_cor'],'',$ped_num,'013',$fecha_cre, $observaciones,
      'P','P',0,$creador,$num_interno,$pac_id
    ));

    //$cg=$clased->get_correlativo_generado($creador);
    $cgid = $pdo->lastInsertId();


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
    $destinatarios = 'helen.cuyan@saas.gob.gt; tania.marroquin@saas.gob.gt';
    $subject = 'PEDIDO CREADO';
    $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el pedido No. <strong>'.$ped_num.'</strong> fue creado exitosamente';
    $body.='Siendo las: '.date('H:i:s',strtotime($fecha_cre)).' del '.date('d-m-Y', strtotime($fecha_cre));
    $body.='<br><br>Favor revisarlo';

    $body.='<br><br><br>Correo enviado desde SAAS APP - Módulo control de Pedidos y Remsas';

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
