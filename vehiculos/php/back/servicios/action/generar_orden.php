<?php

include_once '../../../../../inc/functions.php';
include_once '../../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');


	$response = array();
  $creador = $_SESSION['id_persona'];

  $id_orden = (!empty($_POST['id_orden'])) ? $_POST['id_orden']: NULL;
  $id_tipo_servicio = (!empty($_POST['id_tipo_servicio'])) ? $_POST['id_tipo_servicio']: NULL;
  $id_quien_lleva = (!empty($_POST['id_quien_lleva'])) ? $_POST['id_quien_lleva']: NULL;
  $id_descripcion = (!empty($_POST['id_descripcion'])) ? $_POST['id_descripcion']: NULL;
  $id_destino_c = (!empty($_POST['id_destino_c'])) ? $_POST['id_destino_c']: NULL;
  $id_vehiculo_ = (!empty($_POST['id_vehiculo_'])) ? $_POST['id_vehiculo_']: NULL;
  $id_km_actual = (!empty($_POST['id_km_actual'])) ? $_POST['id_km_actual']: NULL;
  $hora = date('Y-m-d H:i:s');
  $msg='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql1 = "INSERT INTO dayf_vehiculo_servicios (id_vehiculo, id_vehiculo_asignacion, id_tipo_servicio, km_actual, recepcion_id_persona_recibe,
             recepcion_id_persona_entrega, nro_orden, fecha_recepcion, descripcion_solicitado, id_status)
             VALUES (?,?,?,?,?,?,?,?,?,?)";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(
      array($id_vehiculo_,$id_vehiculo_,$id_tipo_servicio,$id_km_actual,$creador,$id_quien_lleva,$id_orden,$hora,$id_descripcion,5487)
    );
    $msg = array('msg'=>'OK','id'=>'','message'=>'Orden generada');
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
