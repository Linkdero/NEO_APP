<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_escolaridad = (!empty($_POST['id_escolaridad']))?$_POST['id_escolaridad']:NULL;

  $id_grado_academico = (!empty($_POST['id_grado_academico']))?$_POST['id_grado_academico']:NULL;
  $id_establecimiento = (!empty($_POST['id_establecimiento'])) ? $_POST['id_establecimiento'] : NULL;
  
  $id_profesion = (!empty($_POST['id_profesion']))?$_POST['id_profesion']:NULL;
  $year = (!empty($_POST['year']))?$_POST['year']:NULL;
  $fecha_titulo = (!empty($_POST['fecha_titulo']))?date('Y-m-d', strtotime($_POST['fecha_titulo'])):NULL;
  $nro_colegiado = (!empty($_POST['nro_colegiado']))?$_POST['nro_colegiado']:NULL;
  $fecha_vencimiento = (!empty($_POST['fecha_vencimiento']))?date('Y-m-d', strtotime($_POST['fecha_vencimiento'])):NULL;
  $id_observaciones = (!empty($_POST['id_observaciones']))?$_POST['id_observaciones']:NULL;
  $flag_finalizado = (!empty($_POST['flag_finalizado']))?true:false;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_escolaridad (id_persona,flag_terminado,id_grado_academico,ano_grado_academico,
               id_establecimiento,id_titulo_obtenido,fecha_titulo,nro_colegiado,fec_venc_colegiado,observaciones)
              VALUES (?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$flag_finalizado,$id_grado_academico,$year,$id_establecimiento,$id_profesion,$fecha_titulo,$nro_colegiado,$fecha_vencimiento,$id_observaciones
        )
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Escolaridad creada');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_escolaridad SET flag_terminado=?,id_grado_academico=?,ano_grado_academico=?,
               id_establecimiento=?,id_titulo_obtenido=?,fecha_titulo=?,nro_colegiado=?,fec_venc_colegiado=?,observaciones=?
               WHERE id_escolaridad = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($flag_finalizado,$id_grado_academico,$year,$id_establecimiento,$id_profesion,$fecha_titulo,$nro_colegiado,$fecha_vencimiento,$id_observaciones,$id_escolaridad)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Escolaridad actualizada');
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
