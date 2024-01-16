<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_capacitacion = $_POST['id_capacitacion'];
  $estado = $_POST['estado'];

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql1 = "UPDATE rrhh_persona_capacitacion SET id_status = ?
             WHERE id_capacitacion = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(
      array($estado,$id_capacitacion)
    );
    $message = ($estado == 2) ? ' Curso aprobado  ' : ' Información anulada';

    $msg = array('msg'=>'OK','id'=>'','message'=>$message, 'filtro'=>$filtro);
    $valor_anterior = array(
      'id_persona'=>$id_capacitacion,
      'estado'=>1
    );

    $valor_nuevo = array(
      'id_capacitacion'=>$id_capacitacion,
      'estado'=>$estado,

    );

    $log = "VALUES(146, 1163, 'rrhh_persona_capacitacion', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
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
