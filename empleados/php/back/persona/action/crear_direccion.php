<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];
  $id_direccion = (!empty($_POST['id_direccion']))?$_POST['id_direccion']:NULL;

  $id_tipo_referencia = (!empty($_POST['id_tipo_referencia']))?$_POST['id_tipo_referencia']:NULL;

  $id_tipo_calle = (!empty($_POST['id_tipo_calle']))?$_POST['id_tipo_calle']:NULL;
  $nro_calle = (!empty($_POST['nro_calle']))?$_POST['nro_calle']:NULL;
  $nro_casa = (!empty($_POST['nro_casa']))?$_POST['nro_casa']:NULL;
  $calle_tope = (!empty($_POST['calle_tope']))?$_POST['calle_tope']:NULL;
  $id_zona = (!empty($_POST['id_zona']))?$_POST['id_zona']:NULL;

  $departamento = (!empty($_POST['departamento']))?$_POST['departamento']:NULL;
  $municipio = (!empty($_POST['municipio']))?$_POST['municipio']:NULL;
  $poblado = (!empty($_POST['poblado']))?$_POST['poblado']:NULL;
  $flag_actual = (!empty($_POST['flag_actual']))?true:false;

  $nro_apto_oficina = (!empty($_POST['nro_apto_oficina']))?$_POST['nro_apto_oficina']:NULL;
  $observaciones = (!empty($_POST['observaciones']))?$_POST['observaciones']:NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_direcciones (id_persona, flag_actual, id_tipo_referencia,
               nro_calle_avenida, calle_tope, tipo_calle, nro_casa, zona, id_depto,  id_muni, id_aldea, nro_apto_oficina, observaciones)
              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$flag_actual,$id_tipo_referencia,$nro_calle,$calle_tope,$id_tipo_calle,$nro_casa,$id_zona,$departamento,$municipio,$poblado,$nro_apto_oficina,$observaciones
        )
      );

      if(!empty($_POST['flag_actual'])){
        $cgid = $pdo->lastInsertId();

        $sql1 = "UPDATE rrhh_persona_direcciones SET flag_actual=?
                 WHERE id_persona = ? AND id_direccion <> ?";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(
          array(0,$id_persona, $cgid)
        );
      }
      $msg = array('msg'=>'OK','id'=>'','message'=>'Dirección creada');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_direcciones SET flag_actual=?, id_tipo_referencia=?,
               nro_calle_avenida=?, calle_tope=?, tipo_calle=?, nro_casa=?, zona=?, id_depto=?,  id_muni=?, id_aldea=?, nro_apto_oficina=?, observaciones=?
               WHERE id_direccion = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($flag_actual,$id_tipo_referencia,$nro_calle,$calle_tope,$id_tipo_calle,$nro_casa,$id_zona,$departamento,$municipio,$poblado,$nro_apto_oficina,$observaciones,$id_direccion)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Dirección actualizada');
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
