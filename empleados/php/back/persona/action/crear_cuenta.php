<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_cuenta = (!empty($_POST['id_cuenta']))?$_POST['id_cuenta']:NULL;

  $id_tipo_cuenta = (!empty($_POST['id_tipo_cuenta']))?$_POST['id_tipo_cuenta']:NULL;
  $id_banco = (!empty($_POST['id_banco']))?$_POST['id_banco']:NULL;
  $nro_cuenta = (!empty($_POST['nro_cuenta']))?$_POST['nro_cuenta']:NULL;
  $fecha_apertura = (!empty($_POST['fecha_apertura']))?date('Y-m-d', strtotime($_POST['fecha_apertura'])):NULL;
  $flag_principal = (!empty($_POST['flag_principal']))?true:false;
  $flag_activo = (!empty($_POST['flag_activo']))?true:false;



  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_cuenta_financiera (id_persona, flag_activa, flag_principal, id_tipo,id_banco, nro_cuenta, fecha_apertura)
              VALUES (?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$flag_activo,$flag_principal,$id_tipo_cuenta,$id_banco,$nro_cuenta,$fecha_apertura
        )
      );

      if(!empty($_POST['flag_principal'])){
        $cgid = $pdo->lastInsertId();

        $sql1 = "UPDATE rrhh_persona_cuenta_financiera SET flag_principal=?
                 WHERE id_persona = ? AND id_cuenta <> ?";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(
          array(0,$id_persona, $cgid)
        );
      }

      $msg = array('msg'=>'OK','id'=>'','message'=>'Cuenta creada');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_cuenta_financiera SET flag_activa=?, flag_principal=?, id_tipo=?,id_banco=?, nro_cuenta=?, fecha_apertura=?
               WHERE id_cuenta = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($flag_activo,$flag_principal,$id_tipo_cuenta,$id_banco,$nro_cuenta,$fecha_apertura,$id_cuenta)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Cuenta actualizada');
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
