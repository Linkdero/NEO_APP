<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];

  $id_experiencia=(!empty($_POST['id_experiencia'])) ? $_POST['id_experiencia'] : NULL;
  $id_empresa_rama=(!empty($_POST['id_empresa_rama'])) ? $_POST['id_empresa_rama'] : NULL;
  $empresa_nombre=(!empty($_POST['empresa_nombre'])) ? strtoupper($_POST['empresa_nombre']) : NULL;
  $empresa_direccion=(!empty($_POST['empresa_direccion'])) ? strtoupper($_POST['empresa_direccion']) : NULL;
  $empresa_telefonos=(!empty($_POST['empresa_telefonos'])) ? $_POST['empresa_telefonos'] : NULL;
  $id_puesto_ocupado=(!empty($_POST['id_puesto_ocupado'])) ? $_POST['id_puesto_ocupado'] : NULL;
  $puesto_atribuciones=(!empty($_POST['puesto_atribuciones'])) ? strtoupper($_POST['puesto_atribuciones']) : NULL;
  $fecha_ingreso=(!empty($_POST['fecha_ingreso'])) ? date('Y-m-d', strtotime($_POST['fecha_ingreso'])) : NULL;
  $fecha_salida=(!empty($_POST['fecha_salida'])) ? date('Y-m-d', strtotime($_POST['fecha_salida'])) : NULL;
  $salario_inicial=(!empty($_POST['salario_inicial'])) ? $_POST['salario_inicial'] : NULL;
  $salario_final=(!empty($_POST['salario_final'])) ? $_POST['salario_final'] : NULL;
  $personas_subordinadas=(!empty($_POST['personas_subordinadas'])) ? $_POST['personas_subordinadas'] : NULL;
  $personas_subordinadas_puesto=(!empty($_POST['personas_subordinadas_puesto'])) ? strtoupper($_POST['personas_subordinadas_puesto']) : NULL;
  $jefe_inmediato=(!empty($_POST['jefe_inmediato'])) ? strtoupper($_POST['jefe_inmediato']) : NULL;
  $jefe_inmediato_telefono=(!empty($_POST['jefe_inmediato_telefono'])) ? $_POST['jefe_inmediato_telefono'] : NULL;
  $motivo_retiro=(!empty($_POST['motivo_retiro'])) ? strtoupper($_POST['motivo_retiro']) : NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_exp_laboral (
                id_persona
                ,empresa_nombre
                ,empresa_direccion
                ,empresa_telefonos
                ,id_empresa_rama
                ,id_puesto_ocupado
                ,puesto_atribuciones
                ,fecha_ingreso
                ,fecha_salida
                ,salario_inicial
                ,salario_final
                ,personas_subordinadas
                ,personas_subordinadas_puesto
                ,jefe_inmediato
                ,jefe_inmediato_telefono
                ,motivo_retiro
            )
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$empresa_nombre,$empresa_direccion,$empresa_telefonos,$id_empresa_rama,$id_puesto_ocupado,$puesto_atribuciones,
          $fecha_ingreso,$fecha_salida,$salario_inicial,$salario_final,$personas_subordinadas,$personas_subordinadas_puesto,
          $jefe_inmediato,$jefe_inmediato_telefono,$motivo_retiro
        )
      );

      $msg = array('msg'=>'OK','id'=>'','message'=>'Trabajo ingresado');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_exp_laboral SET empresa_nombre = ?,empresa_direccion = ?,empresa_telefonos = ?,
              id_empresa_rama = ?,id_puesto_ocupado = ?,puesto_atribuciones = ?,fecha_ingreso = ?,fecha_salida = ?,salario_inicial = ?,
              salario_final = ?,personas_subordinadas = ?,personas_subordinadas_puesto = ?,jefe_inmediato = ?,jefe_inmediato_telefono = ?
              ,motivo_retiro = ?
               WHERE id_experiencia = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($empresa_nombre,$empresa_direccion,$empresa_telefonos,$id_empresa_rama,$id_puesto_ocupado,$puesto_atribuciones,
        $fecha_ingreso,$fecha_salida,$salario_inicial,$salario_final,$personas_subordinadas,$personas_subordinadas_puesto,
        $jefe_inmediato,$jefe_inmediato_telefono,$motivo_retiro,$id_experiencia)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Trabajo actualizado');
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
