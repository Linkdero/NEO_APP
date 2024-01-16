<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $id_persona = $_POST['id_persona'];
  $p_nombre = $_POST['p_nombre'];
  $s_nombre = $_POST['s_nombre'];
  $t_nombre = $_POST['t_nombre'];
  $p_apellido = $_POST['p_apellido'];
  $s_apellido = $_POST['s_apellido'];
  $t_apellido = $_POST['t_apellido'];
  //echo $p_nombre;
  $fecha_nac = $_POST['fecha_nac'];
  $email = $_POST['email'];
  $empadronamiento = $_POST['empadronamiento'];
  $departamento = $_POST['departamento'];
  $municipio = $_POST['municipio'];
  $poblado = (!empty($_POST['poblado']))?$_POST['poblado']:NULL;
  $nit = $_POST['nit'];
  $nisp = $_POST['nisp'];
  $igss = $_POST['igss'];
  $procedencia = $_POST['procedencia'];
  $estado_civil = $_POST['estado_civil'];
  $genero = $_POST['genero'];
  $tipo_servicio = $_POST['tipo_servicio'];
  $religion = $_POST['religion'];
  $profesion = $_POST['profesion'];
  $tipo_curso = (!empty($_POST['tipo_curso']))?$_POST['tipo_curso']:NULL;
  $promocion = (!empty($_POST['promocion']))?$_POST['promocion']:NULL;
  $fecha_cur = $_POST['fecha_cur'];
  $id_tipo_sangre = (!empty($_POST['id_tipo_sangre'])) ? $_POST['id_tipo_sangre'] : NULL;

  $cui = $_POST['cui'];
  $cui_ven = $_POST['cui_ven'];

  $observaciones = $_POST['observaciones'];
  $

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "UPDATE rrhh_persona SET
             fecha_modificacion=?, NISP=?, nit=?, afiliacion_IGSS=?, primer_nombre=?,
             segundo_nombre=?, tercer_nombre=?, primer_apellido=?, segundo_apellido=?,
             tercer_apellido=?, correo_electronico=?,id_estado_civil=?, id_profesion=?,
             observaciones=?, id_tipo_servicio=?, id_genero=?, id_procedencia=?
             WHERE id_persona = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        date('Y-m-d H:i:s'),$nisp,$nit,$igss,$p_nombre,$s_nombre,$t_nombre,$p_apellido,$s_apellido,$t_apellido,
        $email,$estado_civil,$profesion,$observaciones,$tipo_servicio,$genero,$procedencia,$id_persona
      )
    );

    $sql1 = "UPDATE rrhh_persona_complemento SET fecha_nacimiento=?, alias=?, id_depto_nacimiento=?, id_aldea_nacimiento=?,
             id_muni_nacimiento=?, id_tipo_curso=?, id_promocion=?, fecha_curso=?, id_religion=?
             WHERE id_persona = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(
      array($fecha_nac,'',$departamento,$poblado,$municipio,$tipo_curso,$promocion,$fecha_cur,$religion,$id_persona)
    );

    $sql0 = "UPDATE rrhh_persona_documentos SET
             nro_registro = ?, fecha_vencimiento = ?
             WHERE id_persona = ? AND id_tipo_identificacion = ?
             AND id_documento = (SELECT TOP 1 id_documento FROM rrhh_persona_documentos WHERE id_persona = ? AND id_tipo_identificacion = ? ORDER BY id_documento DESC)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $cui,$cui_ven,$id_persona,1238,$id_persona,1238
      )
    );

    $sql0 = "UPDATE rrhh_persona_condicion_fisica_medica SET
             id_valor = ?
             WHERE id_persona = ? AND id_categoria = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $id_tipo_sangre, $id_persona, 10
      )
    );

  $msg = array('msg'=>'OK','id'=>'');
  $pdo->commit();

  $valor_anterior = array(
    'id_persona'=>$id_persona,
    //'estado'=>1051
  );

  $valor_nuevo = array(
    'id_persona'=>$id_persona,
    'descripcion'=>'Se actualizó la información del empleado'
  );

  $log = "VALUES(82, 1163, 'rrhh_persona', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
  $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q2 = $pdo->prepare($sql2);
  $q2->execute(array());

  }catch (PDOException $e){

    $msg = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $msg = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  echo json_encode($msg);
	//echo json_encode($response);
else:
echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
