<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions_plaza.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona=$_POST['id_persona'];
  $id_plaza=$_POST['id_plaza'];
  $asignacion=$_POST['asignacion'];
  $fecha_baja=date('Y-m-d', strtotime($_POST['id_fecha_baja']));
  $tipo_baja=$_POST['id_tipo_baja'];
  $id_detalle_baja=$_POST['id_detalle_baja'];


  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql3 = "UPDATE B
            SET
              B.id_status=?
            FROM rrhh_hst_plazas A
            INNER JOIN rrhh_empleado_plaza B ON A.id_plaza=B.id_plaza
            INNER JOIN rrhh_empleado C ON B.id_empleado=C.id_empleado
            INNER JOIN rrhh_plaza D ON B.id_plaza=D.id_plaza
            WHERE C.id_persona=? AND B.id_asignacion=?";
    $q3 = $pdo->prepare($sql3);
    $q3->execute(array($tipo_baja,$id_persona,$asignacion));

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

    /*$sql5 = "UPDATE D
            SET
              D.id_status=?

            FROM rrhh_hst_plazas A
            INNER JOIN rrhh_empleado_plaza B ON (A.id_plaza=B.id_plaza)
            INNER JOIN rrhh_empleado C ON B.id_empleado=C.id_empleado
            INNER JOIN rrhh_plaza D ON B.id_plaza=D.id_plaza
            WHERE C.id_persona=? AND B.id_asignacion=?";
    $q5 = $pdo->prepare($sql5);
    $q5->execute(array(890,$id_persona,$asignacion));*/

    $clase = new plaza();
    $sueldo = $clase->get_sueldo_actual_by_plaza($id_plaza);
    $reng = $clase->get_reng_siguiente_by_historial($asignacion);

    $sql2 = "INSERT INTO rrhh_empleado_plaza_hst (id_asignacion,reng_num,id_plaza,descripcion,fecha_modificacion, id_status,id_sueldo_plaza)
             VALUES(?,?,?,?,?,?,?)";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($asignacion,$reng,$id_plaza,$id_detalle_baja,date('Y-m-d H:i:s'),$tipo_baja,$sueldo['id_sueldo']));
    $pdo->commit();

    createLog(175, 1163, 'rrhh_empleado_plaza', 'Se creó remoción del empleado = id_persona: '.$id_persona.' de la plaza = id_plaza:'.$id_plaza.', tipo de baja = id_baja:'.$tipo_baja,'', '');
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
