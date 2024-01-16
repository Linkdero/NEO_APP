<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_docto = (!empty($_POST['id_docto']))?$_POST['id_docto']:NULL;

  $id_tipo_doc = (!empty($_POST['id_tipo_doc']))?$_POST['id_tipo_doc']:NULL;

  $nro_registro = (!empty($_POST['nro_registro']))?$_POST['nro_registro']:NULL;
  $fecha_vec = (!empty($_POST['fecha_vec']))?$_POST['fecha_vec']:NULL;
  $id_subtipo_doc = (!empty($_POST['id_subtipo_doc']))?$_POST['id_subtipo_doc']:NULL;

  $departamento = (!empty($_POST['departamento']))?$_POST['departamento']:NULL;
  $municipio = (!empty($_POST['municipio']))?$_POST['municipio']:NULL;
  $poblado = (!empty($_POST['poblado']))?$_POST['poblado']:NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_documentos (id_persona, id_tipo_identificacion, id_tipo_documento,
              nro_registro, fecha_vencimiento, id_departamento, id_municipio, id_aldea)
              VALUES (?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$id_tipo_doc,$id_subtipo_doc,$nro_registro,$fecha_vec,$departamento,$municipio,$poblado,
        )
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Documento creado');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_documentos SET id_tipo_identificacion=?, id_tipo_documento=?,
              nro_registro=?, fecha_vencimiento=?, id_departamento=?, id_municipio=?, id_aldea=?
               WHERE id_documento = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($id_tipo_doc,$id_subtipo_doc,$nro_registro,$fecha_vec,$departamento,$municipio,$poblado,$id_docto)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Documento actualizado');
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
