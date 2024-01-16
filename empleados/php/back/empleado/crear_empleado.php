<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();

  $p_nombre = $_POST['p_nombre'];
  $s_nombre = $_POST['s_nombre'];
  $t_nombre = $_POST['t_nombre'];
  $p_apellido = $_POST['p_apellido'];
  $s_apellido = $_POST['s_apellido'];
  $t_apellido = $_POST['t_apellido'];
  $fecha_nac = $_POST['fecha_nac'];
  $email = $_POST['email'];
  $cui = $_POST['cui'];
  $fecha_vencimiento = $_POST['fecha_vencimiento'];
  $departamento = $_POST['departamento'];
  $municipio = $_POST['municipio'];
  $poblado = (!empty($_POST['poblado']))?$_POST['poblado']:NULL;
  $nit = $_POST['nit'];
  //$nisp = $_POST['nisp'];
  $igss = $_POST['igss'];
  $procedencia = $_POST['procedencia'];
  $estado_civil = $_POST['estado_civil'];
  $genero = $_POST['genero'];
  $tipo_servicio = $_POST['tipo_servicio'];
  $religion = $_POST['religion'];
  $profesion = $_POST['profesion'];
  $tipo_curso = (!empty($_POST['tipo_curso']))?$_POST['tipo_curso']:NULL;
  $promocion = (!empty($_POST['promocion']))?$_POST['promocion']:NULL;
  $id_tipo_sangre = (!empty($_POST['id_tipo_sangre'])) ? $_POST['id_tipo_sangre'] : NULL;
  $fecha_cur = $_POST['fecha_cur'];

  $observaciones = $_POST['observaciones'];

  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO rrhh_persona (tipo_persona, fecha_ingreso, fecha_modificacion, id_status, nit, afiliacion_IGSS, primer_nombre, segundo_nombre, tercer_nombre, primer_apellido,
                                        segundo_apellido, tercer_apellido, correo_electronico,id_estado_civil, id_profesion, observaciones, id_tipo_servicio, id_genero, id_procedencia )
             VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        1051,date('Y-m-d H:i:s'),date('Y-m-d H:i:s'),1067,$nit,$igss,$p_nombre,$s_nombre,$t_nombre,$p_apellido,$s_apellido,$t_apellido,
        $email,$estado_civil,$profesion,$observaciones,$tipo_servicio,$genero,$procedencia
      )
    );

    $cgid = $pdo->lastInsertId();

    $sql0 = "INSERT INTO rrhh_persona_complemento (id_persona, fecha_nacimiento, alias, id_depto_nacimiento, id_aldea_nacimiento, id_muni_nacimiento, id_tipo_curso, id_promocion, fecha_curso, id_religion)
             VALUES(?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array($cgid, $fecha_nac,'',$departamento,$poblado,$municipio,$tipo_curso,$promocion,$fecha_cur,$religion)
    );

    $sql1 = "INSERT INTO rrhh_persona_condicion_fisica_medica (id_persona,id_categoria,valor,id_valor,fecha_modificacion)
             VALUES(?,?,?,?,?)";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(
      array($cgid, 10, 0,$id_tipo_sangre, date('Y-m-d H:i:s'))
    );

    $sql0 = "INSERT INTO rrhh_persona_documentos (id_persona, id_tipo_identificacion,
            nro_registro,  id_departamento, id_municipio, id_aldea, fecha_vencimiento)
            VALUES (?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $cgid,1238,$cui,$departamento,$municipio,$poblado,$fecha_vencimiento
      )
    );


  $msg = array('msg'=>'OK','id'=>$cgid);
  $pdo->commit();

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
