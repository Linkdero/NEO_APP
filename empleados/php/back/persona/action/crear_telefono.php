<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_telefono = (!empty($_POST['id_telefono']))?$_POST['id_telefono']:NULL;

  $id_tipo_referencia = (!empty($_POST['id_tipo_referencia']))?$_POST['id_tipo_referencia']:NULL;
  $id_tipo_telefono = (!empty($_POST['id_tipo_telefono']))?$_POST['id_tipo_telefono']:NULL;
  $nro_telefono = (!empty($_POST['nro_telefono']))?$_POST['nro_telefono']:NULL;
  $id_observaciones = (!empty($_POST['tel_observaciones']))?$_POST['tel_observaciones']:NULL;

  $flag_privado=(!empty($_POST['flag_privado'])  == 'on' )?true:NULL;
  $flag_activo=(!empty($_POST['flag_activo'])  == 'on' )?true:NULL;
  $flag_principal=(!empty($_POST['flag_principal'])  == 'on' )?true:NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      //echo $id_persona.'!'.$flag_privado.'!'.$flag_activo.'!'.$flag_principal.'!'.$id_tipo_referencia.'!'.$id_tipo_telefono.'!'.$nro_telefono.'!'.$id_observaciones;
      //echo $flag_privado;
      $sql0 = "INSERT INTO rrhh_persona_telefonos (id_persona, flag_privado, flag_activo, flag_principal, tipo, id_tipo_telefono, nro_telefono, observaciones)
              VALUES (?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$flag_privado,$flag_activo,$flag_principal,$id_tipo_referencia,$id_tipo_telefono,$nro_telefono,$id_observaciones
        )
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Teléfono creado');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_telefonos SET flag_privado=?, flag_activo=?, flag_principal=?, tipo=?, id_tipo_telefono=?, nro_telefono=?, observaciones=?
               WHERE id_telefono = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($flag_privado,$flag_activo,$flag_principal,$id_tipo_referencia,$id_tipo_telefono,$nro_telefono,$id_observaciones,$id_telefono)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Teléfono actualizado');
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
