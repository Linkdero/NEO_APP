<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $tipo_accion = $_POST['tipo_accion'];

  $id_capacitacion = (!empty($_POST['id_capacitacion'])) ? $_POST['id_capacitacion'] : NULL;
  $id_curso_p = (!empty($_POST['id_curso_p'])) ? $_POST['id_curso_p'] : NULL;
  $id_patrocinador = (!empty($_POST['id_patrocinador'])) ? $_POST['id_patrocinador'] : NULL;
  $fecha_ini = (!empty($_POST['fecha_ini'])) ? date('Y-m-d', strtotime($_POST['fecha_ini'])) : NULL;
  $fecha_fin = (!empty($_POST['fecha_fin'])) ? date('Y-m-d', strtotime($_POST['fecha_fin'])) : NULL;
  $horas_curso = (!empty($_POST['horas_curso'])) ? $_POST['horas_curso'] : NULL;
  $horas_completadas = (!empty($_POST['horas_completadas'])) ? $_POST['horas_completadas'] : NULL;
  $id_pais = (!empty($_POST['id_pais'])) ? $_POST['id_pais'] : NULL;
  $modalidad = (!empty($_POST['picked'])) ? $_POST['picked'] : NULL;

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 2){
      $sql0 = "INSERT INTO rrhh_persona_capacitacion (id_persona,id_curso,nombre_curso,descripcion,fecha_inicio,
               fecha_fin,id_pais,horas_curso,horas_completadas,id_patrocinador,id_status,id_modalidad)
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_persona,$id_curso_p,'','',$fecha_ini,$fecha_fin,$id_pais,$horas_curso,$horas_completadas,$id_patrocinador,1,$modalidad
        )
      );

      $msg = array('msg'=>'OK','id'=>'','message'=>'Capacitación creada');
    }else if($tipo_accion == 3){
      $sql1 = "UPDATE rrhh_persona_capacitacion SET
      id_curso = ?,
      fecha_inicio = ?,
      fecha_fin = ?,
      id_pais = ?,
      horas_curso = ?,
      horas_completadas = ?,
      id_patrocinador = ?,
      id_modalidad = ?
               WHERE id_capacitacion = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array($id_curso_p,$fecha_ini,$fecha_fin,$id_pais,$horas_curso,$horas_completadas,$id_patrocinador,$modalidad,$id_capacitacion)
      );
      $msg = array('msg'=>'OK','id'=>'','message'=>'Capacitación actualizada');
    }

    $valor_anterior = array(
      'id_capacitacion'=>$id_capacitacion,
      'estado'=>1
    );

    $valor_nuevo = array(
      'id_capacitacion'=>$id_capacitacion,
      'id_curso_p'=>$id_curso_p,
      'id_patrocinador'=>$id_patrocinador,
      'fecha_ini'=>$fecha_ini,
      'fecha_fin'=>$fecha_fin,
      'horas_curso'=>$horas_curso,
      'horas_completadas'=>$horas_completadas,
      'id_pais'=>$id_pais,
      'modalidad'=>$modalidad,
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
