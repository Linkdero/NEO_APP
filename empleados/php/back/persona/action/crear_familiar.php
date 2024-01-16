<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_familiar = (!empty($_POST['id_familiar']))?$_POST['id_familiar']:NULL;

  $id_tipo_referencia = (!empty($_POST['id_tipo_referencia']))?$_POST['id_tipo_referencia']:NULL;
  $id_tipo_familiar = (!empty($_POST['id_tipo_familiar']))?$_POST['id_tipo_familiar']:NULL;
  $fecha_nacimiento = (!empty($_POST['fecha_nacimiento']))?date('Y-m-d',strtotime($_POST['fecha_nacimiento'])):NULL;
  $genero = (!empty($_POST['genero']))?$_POST['genero']:NULL;
  $primer_nombre = (!empty($_POST['primer_nombre']))?$_POST['primer_nombre']:NULL;
  $segundo_nombre = (!empty($_POST['segundo_nombre']))?$_POST['segundo_nombre']:NULL;
  $primer_apellido = (!empty($_POST['primer_apellido']))?$_POST['primer_apellido']:NULL;
  $segundo_apellido = (!empty($_POST['segundo_apellido']))?$_POST['segundo_apellido']:NULL;
  $empresa = (!empty($_POST['empresa']))?$_POST['empresa']:NULL;
  $direccion = (!empty($_POST['direccion']))?$_POST['direccion']:NULL;
  $telefono = (!empty($_POST['telefono']))?$_POST['telefono']:NULL;
  $profesion = (!empty($_POST['profesion']))?$_POST['profesion']:NULL;
  $fam_observaciones = (!empty($_POST['fam_observaciones']))?$_POST['fam_observaciones']:NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      //echo $id_persona.'!'.$flag_privado.'!'.$flag_activo.'!'.$flag_principal.'!'.$id_tipo_referencia.'!'.$id_tipo_telefono.'!'.$nro_telefono.'!'.$id_observaciones;
      //echo $flag_privado;
      $sql0 = "INSERT INTO rrhh_persona_referencias (id_persona, tipo_referencia, id_parentesco, id_ocupacion, id_genero, primer_nombre,
               segundo_nombre,primer_apellido,segundo_apellido, empresa_trabaja, empresa_direccion, empresa_telefono, fecha_nacimiento)
              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$id_tipo_referencia,$id_tipo_familiar,$profesion,
        $genero,$primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$empresa,$direccion,$telefono,$fecha_nacimiento
        )
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Familiar creado');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_referencias SET tipo_referencia=?, id_parentesco=?, id_ocupacion=?, id_genero=?, primer_nombre=?,
               segundo_nombre=?,primer_apellido=?,segundo_apellido=?, empresa_trabaja=?, empresa_direccion=?, empresa_telefono=?, fecha_nacimiento=?
               WHERE id_referencia = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($id_tipo_referencia,$id_tipo_familiar,$profesion,
      $genero,$primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$empresa,$direccion,$telefono,$fecha_nacimiento,$id_familiar)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Familiar actualizado');
    }


    $pdo->commit();

  }catch (PDOException $e){
    $msg = array('msg'=>'ERROR','message'=>$e);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $msg = array('msg'=>'ERROR','message'=>$e2);

    }
  }
  echo json_encode($msg);
	//echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
