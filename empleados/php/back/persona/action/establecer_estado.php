<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_status = (!empty($_POST['id_siguiente_proceso']))?$_POST['id_siguiente_proceso']:NULL;
  $tipo_aspirante = (!empty($_POST['id_tipo_aspirante']))?$_POST['id_tipo_aspirante']:NULL;
  $motivo = (!empty($_POST['motivo']))?$_POST['motivo']:NULL;

  $estado = ($tipo_accion == 13) ? 1029 : $id_status;
  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql1 = "UPDATE rrhh_persona SET id_status = ?, id_tipo_aspirante = ?, observaciones = ?
             WHERE id_persona = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(
      array($estado,$tipo_aspirante,$motivo,$id_persona)
    );
    $message = ($tipo_accion == 13) ? ' Aspirante aprobado ' : ' Aspirante denegado';
    $filtro = ($tipo_accion == 13) ? 5 : 3;

    $msg = array('msg'=>'OK','id'=>'','message'=>$message, 'filtro'=>$filtro);
    $valor_anterior = array(
      'id_persona'=>$id_persona,
      'estado'=>1051
    );

    $valor_nuevo = array(
      'id_persona'=>$id_persona,
      'estado'=>$estado,
      'motivo'=>$motivo
    );

    $log = "VALUES(82, 1163, 'rrhh_persona', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array());

    $pdo->commit();

  }catch (PDOException $e){
    $msg = array('msg'=>'ERROR','id'=>$e);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $msg = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  echo json_encode($msg);
	//echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
