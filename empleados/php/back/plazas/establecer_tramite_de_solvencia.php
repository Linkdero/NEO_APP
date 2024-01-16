<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions_plaza.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona=$_POST['id_persona'];
  $id_plaza=$_POST['id_plaza'];
  $fecha_remocion=date('Y-m-d', strtotime($_POST['fecha']));
  $fecha_acuerdo=date('Y-m-d', strtotime($_POST['fecha_acuerdo']));
  $fecha_baja= (!empty($_POST['id_fecha_baja'])) ? date('Y-m-d', strtotime($_POST['id_fecha_baja'])) : NULL;
  $acuerdo=$_POST['nro_acuerdo'];
  $observaciones=$_POST['observaciones'];
  $tipo_baja=$_POST['tipo_baja'];
  $asignacion=$_POST['asignacion'];
  $remocion_reingreso = ($_POST['remocion_reingreso'] == 'true')?true:false;
  $id_remocion_reingreso = ($_POST['remocion_reingreso'] == 'true')?1:0;
  $pdo = Database::connect_sqlsrv();

  //echo $id_remocion_reingreso;
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql3 = "UPDATE B
            SET
              B.nro_acuerdo_baja=?,
              B.fecha_acuerdo_baja=?,
              B.fecha_efectiva_resicion=?,
              B.id_status=?,
              B.id_remocion_reingreso = ?
            FROM rrhh_hst_plazas A
            INNER JOIN rrhh_empleado_plaza B ON A.id_plaza=B.id_plaza
            INNER JOIN rrhh_empleado C ON B.id_empleado=C.id_empleado
            INNER JOIN rrhh_plaza D ON B.id_plaza=D.id_plaza
            WHERE C.id_persona=? AND B.id_asignacion=?";
    $q3 = $pdo->prepare($sql3);
    $q3->execute(array($acuerdo,$fecha_acuerdo,$fecha_remocion,$tipo_baja,$id_remocion_reingreso,$id_persona,$asignacion));

    $sql4 = "UPDATE C
            SET

              C.id_status=?

            FROM rrhh_hst_plazas A
            INNER JOIN rrhh_empleado_plaza B ON A.id_plaza=B.id_plaza
            INNER JOIN rrhh_empleado C ON B.id_empleado=C.id_empleado
            INNER JOIN rrhh_plaza D ON B.id_plaza=D.id_plaza
            WHERE C.id_persona=? AND B.id_asignacion=?";
    $q4 = $pdo->prepare($sql4);
    $q4->execute(array($tipo_baja,$id_persona,$asignacion));

    $sql5 = "UPDATE D
            SET
              D.id_status=?

            FROM rrhh_hst_plazas A
            INNER JOIN rrhh_empleado_plaza B ON (A.id_plaza=B.id_plaza)
            INNER JOIN rrhh_empleado C ON B.id_empleado=C.id_empleado
            INNER JOIN rrhh_plaza D ON B.id_plaza=D.id_plaza
            WHERE C.id_persona=? AND B.id_asignacion=?";
    $q5 = $pdo->prepare($sql5);
    $q5->execute(array(890,$id_persona,$asignacion));

    $clase = new plaza();
    $sueldo = $clase->get_sueldo_actual_by_plaza($id_plaza);
    $reng = $clase->get_reng_siguiente_by_historial($asignacion);
    //echo $sueldo['id_sueldo'].'<br>';
    //echo $reng;
    $sql2 = "INSERT INTO rrhh_empleado_plaza_hst (id_asignacion,reng_num,id_plaza,descripcion,fecha_modificacion, id_status,id_sueldo_plaza)
             VALUES(?,?,?,?,?,?,?)";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($asignacion,$reng,$id_plaza,$observaciones,date('Y-m-d H:i:s'),$tipo_baja,$sueldo['id_sueldo']));

    if($remocion_reingreso == false){
      //inicio
      $sqlv = "UPDATE APP_VACACIONES.dbo.DIAS_ASIGNADOS SET dia_liq = ? WHERE emp_id = ?  AND (dia_asi - dia_goz) > ?";
      $qv = $pdo->prepare($sqlv);
      $qv->execute(array(1,$id_persona,0));
      //fin
    }

    $valor_anterior = array(
      'id_persona'=>$id_persona,
      'estado'=>891
      //'estado'=>1051
    );

    $valor_nuevo = array(
      'id_persona'=>$id_persona,
      'descripcion'=>'Se creó remoción del empleado y se liquidaron los períodos vacacionales que no gozó',
      'id_contrato'=>$reng_num
    );

    $log = "VALUES(82, 1163, 'rrhh_empleado_plaza', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array());

    createLog(175, 1163, 'rrhh_empleado_plaza','Se estableció en trámite de solvencia al empleado = id_persona: '.$id_persona.' de la plaza = id_plaza:'.$id_plaza,'', '');
    $pdo->commit();
  }catch (PDOException $e){
    echo $e;
    try{ $pdo->rollBack();}catch(Exception $e2){
      echo $e2;
    }
  }

  /*$sql = "UPDATE A
          SET
            A.flag_ubicacion_actual=?,
            A.fecha_modificacion=?
          FROM rrhh_hst_plazas A
          INNER JOIN rrhh_empleado_plaza B ON A.id_plaza=B.id_plaza
          INNER JOIN rrhh_empleado C ON B.id_empleado=C.id_empleado
          INNER JOIN rrhh_plaza D ON B.id_plaza=D.id_plaza
          WHERE C.id_persona=? AND A.flag_ubicacion_actual=? AND B.id_status=? AND B.id_asignacion=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(0,date('Y-m-d H:i:s'),$id_persona,1,891,$asignacion));*/
  Database::disconnect_sqlsrv();


  else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>


<?php
/*include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona=$_POST['id_persona'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE rrhh_empleado
          SET
            id_status=?
          WHERE id_persona=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(
    5610,
    $id_persona,

  ));
  Database::disconnect_sqlsrv();

  else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

*/
?>
