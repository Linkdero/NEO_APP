<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_contratos.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona=$_POST['id_persona'];
  $reng_num=$_POST['reng_num'];
  $id_tipo_renglon=$_POST['id_tipo_renglon'];

  $fecha_acuerdo=date('Y-m-d', strtotime($_POST['fecha_acuerdo']));
  $fecha_finalizacion=date('Y-m-d', strtotime($_POST['fecha_finalizacion']));
  $nro_acuerdo=(!empty($_POST['nro_acuerdo'])) ? $_POST['nro_acuerdo'] : NULL;
  $observaciones=$_POST['observaciones'];

  $tipo_baja=$_POST['tipo_baja'];

  //echo $id_empleado;
  $clase=new contrato;
  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //seleccionar asignación actual
    $estado_empleado = 0;
    $estado_contrato = 0;
    if($id_tipo_renglon == '029'){
      if($tipo_baja == 8281){
        $estado_contrato = 909;
        $estado_empleado = 909;
      }else{
        $estado_contrato = 3734;
        $estado_empleado = 3734;
      }
    }else{
      if($tipo_baja == 8265 || $tipo_baja == 8267 || $tipo_baja == 8268 || $tipo_baja == 8269 ||$tipo_baja == 8270){
        $estado_contrato = 3734;
        $estado_empleado = 3734;
      }else if($tipo_baja == 9999999){
        $estado_contrato = 3733;
        $estado_empleado = 3733;
      }
      else {
        $estado_contrato = 911;
        $estado_empleado = 911;
      }
    }



    $sql0 = "UPDATE rrhh_empleado_contratos
    SET id_status=?, nro_acuerdo_resicion=?, fecha_acuerdo_resicion=?,fecha_efectiva_resicion=?, id_tipo_baja=? WHERE reng_num=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($estado_contrato,$nro_acuerdo,$fecha_acuerdo,$fecha_finalizacion,$tipo_baja,$reng_num));

    //update empleado contrato
    $sql1 = "UPDATE rrhh_empleado SET id_contrato=?, id_status=? WHERE id_persona=?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(1075,$estado_empleado,$id_persona));

    //liquidar vacaciones
    $sql1 = "UPDATE APP_VACACIONES.dbo.DIAS_ASIGNADOS SET dia_liq = ? WHERE emp_id = ?  AND (dia_asi - dia_goz) > ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(1,$id_persona,0));

    $valor_anterior = array(
      'id_persona'=>$id_persona,
      'estado'=>891
      //'estado'=>1051
    );

    $valor_nuevo = array(
      'id_persona'=>$id_persona,
      'descripcion'=>'Se finalizó el contrato de la persona y se liquidaron los períodos vacacionales que no gozó',
      'id_contrato'=>$reng_num
    );

    $log = "VALUES(82, 1163, 'rrhh_empleado_plaza', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array());

  $pdo->commit();
  }catch (PDOException $e){
    echo $e;
    try{ $pdo->rollBack();}catch(Exception $e2){
      echo $e2;
    }
  }



  Database::disconnect_sqlsrv();

  else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
