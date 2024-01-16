<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_plaza.php';
  date_default_timezone_set('America/Guatemala');

  $clase=new plaza;

  $id_persona=$_POST['id_persona'];
  $tipo_accion=$_POST['tipo_de_accion'];
  $id_asignacion=(!empty($_POST['id_asignacion']))?$_POST['id_asignacion']:NULL;
  $reng_num=(!empty($_POST['reng_num']))?$_POST['reng_num']:NULL;
  $id_plaza=(!empty($_POST['cod_plaza']))?$_POST['cod_plaza']:NULL;

  $id_secretaria_f=4;//(!empty($_POST['idSecretariaF']))?$_POST['idSecretariaF']:NULL;
  $id_subsecretaria_f=(!empty($_POST['idSubSecretariaF']))?$_POST['idSubSecretariaF']:NULL;
  $id_direccion_f=(!empty($_POST['idDireccionF']))?$_POST['idDireccionF']:NULL;
  $id_subdireccion_f=(!empty($_POST['idSubDireccionF']))?$_POST['idSubDireccionF']:NULL;
  $id_departamento_f=(!empty($_POST['idDepartamentoF']))?$_POST['idDepartamentoF']:NULL;
  $id_seccion_f=(!empty($_POST['idSeccionF']))?$_POST['idSeccionF']:NULL;
  $id_puesto_f=(!empty($_POST['idPuestoF']))?$_POST['idPuestoF']:NULL;
  $id_nivel_f=(!empty($_POST['idNivelF']))?$_POST['idNivelF']:NULL;
  $check=(!empty($_POST['chk_retroactivo']))?$_POST['chk_retroactivo']:NULL;

  $acuerdo=$_POST['id_nro_acuerdo_f_u'];
  $observaciones=$_POST['id_detalle_asignacio_f_u'];
  $fecha_inicio=date('Y-m-d', strtotime($_POST['id_fecha_inicio_f_u']));
  $fecha_fin=(!empty($_POST['id_fecha_fin_f_u']))?date('Y-m-d', strtotime($_POST['id_fecha_fin_f_u'])):NULL;
  $fecha_posesion=(!empty($_POST['id_fecha_inicio_f_u']))?date('Y-m-d', strtotime($_POST['id_fecha_inicio_f_u'])):NULL;

  //echo $id_secretaria_f;

  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo_accion == 1){

      $plaza='';
      if(!empty($id_plaza)){

        $sql = "SELECT b.id_plaza,b.id_asignacion,a.id_empleado
                FROM rrhh_empleado a
                INNER JOIN rrhh_empleado_plaza b ON a.id_empleado=b.id_empleado
                WHERE a.id_persona=? AND b.id_plaza=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id_persona,$id_plaza));
        $plaza=$q->fetch();
      }else{
        $sql = "SELECT b.id_plaza,b.id_asignacion,a.id_empleado
                FROM rrhh_empleado a
                INNER JOIN rrhh_empleado_plaza b ON a.id_empleado=b.id_empleado
                WHERE a.id_persona=? AND b.id_status=?
                ORDER BY id_asignacion DESC";
        $q = $pdo->prepare($sql);
        $q->execute(array($id_persona,891));
        $plaza=$q->fetch();

        $sql2 = "SELECT TOP 1 reng_num
                FROM rrhh_hst_plazas
                WHERE id_plaza=?
                ORDER BY reng_num DESC";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($plaza['id_plaza']));
        $reng=$q2->fetch();

        $pd=$clase->get_plaza_by_id($plaza['id_plaza']);
        $asignacion_nueva=$clase->get_asignacion($plaza['id_empleado']);

        $clase->ingresar_historial_hst_plaza($plaza['id_plaza'],$id_puesto_f,$pd['id_nivel_presupuestario'],$pd['id_secretaria_presupuestario'],$pd['id_subsecretaria_presupuestaria'],
                                              $pd['id_direccion_presupuestaria'],$pd['id_subdireccion_presupuestaria'],$pd['id_depto_presupuestario'],$pd['id_seccion_presupuestario'],
                                              $id_nivel_f,
                                              $id_secretaria_f,
                                              $id_subsecretaria_f,
                                              $id_direccion_f,
                                              $id_subdireccion_f,
                                              $id_departamento_f,
                                              $id_seccion_f,
                                              $asignacion_nueva['id_sueldo']);
        $clase->ingresar_historial($asignacion_nueva['id_asignacion'],$asignacion_nueva['id_plaza'],$observaciones,date('Y-m-d H:i:s'),1210,$asignacion_nueva['id_sueldo'],$asignacion_nueva['id_sueldo']);

        /*$sql3 = "UPDATE rrhh_hst_plazas
                SET
                  id_nivel_funcional=?,
                  id_secretaria_funcional=?,
                  id_subsecretaria_funcional=?,
                  id_direccion_funcional=?,
                  id_subdireccion_funcional=?,
                  id_depto_funcional=?,
                  id_seccion_funcional=?,
                  id_puesto=?,
                  fecha_modificacion=?
                WHERE id_plaza=?
                AND reng_num=?

                ";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array(
          $id_nivel_f,
          $id_secretaria_f,
          $id_subsecretaria_f,
          $id_direccion_f,
          $id_subdireccion_f,
          $id_departamento_f,
          $id_seccion_f,
          $id_puesto_f,
          date('Y-m-d H:i:s'),
          $plaza['id_plaza'],
          $reng['reng_num']
        ));*/

      }

      empleado::guardar_historial_asignacion_puesto($plaza['id_asignacion'],$id_nivel_f,$id_secretaria_f,$id_subsecretaria_f,$id_direccion_f,$id_subdireccion_f,$id_departamento_f,$id_seccion_f,$id_puesto_f,$acuerdo,$observaciones,$fecha_inicio,$fecha_fin,$fecha_posesion);
      createLog(74, 1163, 'rrhh_asignacion_puesto_historial_detalle','Se asignó ubicación funcional al empleado id_persona: '.$id_persona.' - en la asignación id:'.$id_asignacion. 'en la plaza: '.$plaza['id_plaza'],'', '');

    }else if($tipo_accion == 2) {
      // code...
      $sql = "SELECT b.id_plaza,b.id_asignacion,a.id_empleado
              FROM rrhh_empleado a
              INNER JOIN rrhh_empleado_plaza b ON a.id_empleado=b.id_empleado
              WHERE b.id_asignacion=?
              ORDER BY id_asignacion DESC";
      $q = $pdo->prepare($sql);
      $q->execute(array($id_asignacion));
      $plaza=$q->fetch();

      $sql2 = "SELECT TOP 1 reng_num
              FROM rrhh_hst_plazas
              WHERE id_plaza=?
              ORDER BY reng_num DESC";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($plaza['id_plaza']));
      $reng=$q2->fetch();

      $sql2 = "UPDATE rrhh_asignacion_puesto_historial_detalle SET
              nivel_f=?,secretaria_f=?,subsecretaria_f=?,direccion_f=?,subdireccion_f=?,departamento_f=?,seccion_f=?,
              puesto_f=?,acuerdo=?,observaciones=?,fecha_inicio=?,fecha_fin=?,fecha_toma_posesion=?,operado_por=?
              WHERE id_asignacion=? AND reng_num=?";
      $p2 = $pdo->prepare($sql2);
      $p2->execute(array($id_nivel_f,$id_secretaria_f,$id_subsecretaria_f,$id_direccion_f,$id_subdireccion_f,
      $id_departamento_f,$id_seccion_f,$id_puesto_f,
      $acuerdo,$observaciones,$fecha_inicio,$fecha_fin,date('Y-m-d H:i:s',strtotime($fecha_posesion)),$_SESSION['id_persona'],
      $id_asignacion,$reng_num));

      $pd=$clase->get_plaza_by_id($plaza['id_plaza']);

      //echo json_encode($pd);
      if(!empty($plaza['id_empleado'])){
        $asignacion_nueva=$clase->get_asignacion($plaza['id_empleado']);

        //echo json_encode($asignacion_nueva);

        $clase->ingresar_historial_hst_plaza($plaza['id_plaza'],$id_puesto_f,$pd['id_nivel_presupuestario'],$pd['id_secretaria_presupuestario'],$pd['id_subsecretaria_presupuestaria'],
                                              $pd['id_direccion_presupuestaria'],$pd['id_subdireccion_presupuestaria'],$pd['id_depto_presupuestario'],$pd['id_seccion_presupuestario'],
                                              $id_nivel_f,
                                              $id_secretaria_f,
                                              $id_subsecretaria_f,
                                              $id_direccion_f,
                                              $id_subdireccion_f,
                                              $id_departamento_f,
                                              $id_seccion_f,
                                              $asignacion_nueva['id_sueldo']);
        $clase->ingresar_historial($asignacion_nueva['id_asignacion'],$asignacion_nueva['id_plaza'],$observaciones,date('Y-m-d H:i:s'),1210,$asignacion_nueva['id_sueldo'],$asignacion_nueva['id_sueldo']);
      }

      createLog(74, 1163, 'rrhh_asignacion_puesto_historial_detalle','Se actualizó ubicación funcional al empleado id_persona: '.$id_persona.' - en la asignación id:'.$id_asignacion. 'en la plaza: '.$plaza['id_plaza'],'', '');

      /*$sql3 = "UPDATE rrhh_hst_plazas
              SET
                id_nivel_funcional=?,
                id_secretaria_funcional=?,
                id_subsecretaria_funcional=?,
                id_direccion_funcional=?,
                id_subdireccion_funcional=?,
                id_depto_funcional=?,
                id_seccion_funcional=?,
                id_puesto=?,
                fecha_modificacion=?
              WHERE id_plaza=?
              AND reng_num=?

              ";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array(
        $id_nivel_f,
        $id_secretaria_f,
        $id_subsecretaria_f,
        $id_direccion_f,
        $id_subdireccion_f,
        $id_departamento_f,
        $id_seccion_f,
        $id_puesto_f,
        date('Y-m-d H:i:s'),
        $plaza['id_plaza'],
        $reng['reng_num']
      ));
      echo $id_asignacion.'||'.$reng_num.'||'.$sql2;*/
    }

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
