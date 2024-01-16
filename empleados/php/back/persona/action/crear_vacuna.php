<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_vacuna = (!empty($_POST['id_vacuna']))?$_POST['id_vacuna']:NULL;

  $id_tipo_vacuna = (!empty($_POST['id_tipo_vacuna']))?$_POST['id_tipo_vacuna']:NULL;
  $tipo_dosis = (!empty($_POST['id_tipo_dosis']))?$_POST['id_tipo_dosis']:NULL;
  $fecha_vacuna = (!empty($_POST['fecha_vacuna']))?date('Y-m-d', strtotime($_POST['fecha_vacuna'])):NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_vacuna_covid (id_persona, id_vacuna_dosis, id_vacuna_tipo,fecha_vacunacion)
              VALUES (?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($id_persona,$tipo_dosis,$id_tipo_vacuna,date('Y-m-d', strtotime($fecha_vacuna))));
      $msg = array('msg'=>'OK','id'=>'','message'=>'Vacunación ingresada');

      $id_vacuna = $pdo->lastInsertId();
      createLog(175, 1163, 'rrhh_persona_vacuna_covid', 'Se creó vacuna = id_tipo_vacuna:'.$id_tipo_vacuna.', id_vacuna:'.$id_vacuna.', codigo persona = id_persona:'.$id_persona, '','');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_vacuna_covid SET id_vacuna_dosis=?, id_vacuna_tipo=?,fecha_vacunacion=?
               WHERE id_vacuna = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($tipo_dosis,$id_tipo_vacuna,date('Y-m-d', strtotime($fecha_vacuna)),$id_vacuna)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Vacunación actualizada ');
      createLog(175, 1163, 'rrhh_persona_vacuna_covid', 'Se actualizó la vacuna = id_tipo_vacuna:'.$id_tipo_vacuna.', id_vacuna:'.$id_vacuna.', codigo persona = id_persona:'.$id_persona, '','');
    }


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
