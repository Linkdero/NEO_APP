<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_plaza.php';
  date_default_timezone_set('America/Guatemala');

  $tipo_accion=$_POST['tipo_de_accion'];
  $id_persona=$_POST['id_gafete'];
  $id_empleado=(!empty($_POST['id_empleado']))?$_POST['id_empleado']:NULL;
  $id_asignacionA=(!empty($_POST['id_asignacion']))?$_POST['id_asignacion']:NULL;
  //$tipo_persona=$_POST['tipo_persona'];
  $id_plaza=$_POST['id_plaza_pl'];

  $id_nro_acuerdo=$_POST['id_nro_acuerdo_p_pl'];
  $id_fecha_acuerdo_asignacion=date('Y-m-d', strtotime($_POST['id_fecha_acuerdo_asignacion_p_pl']));
  $id_fecha_toma_posesion=date('Y-m-d', strtotime($_POST['id_fecha_toma_posesion_p_pl']));
  $observaciones=$_POST['id_detalle_asignacio_p_pl'];

  $id_secretaria_funcional=4;//(!empty($_POST['idSecretariaF']))?$_POST['idSecretariaF']:NULL;
  $id_subsecretaria_funcional=(!empty($_POST['idSubSecretariaF']))?$_POST['idSubSecretariaF']:NULL;
  $id_direccion_funcional=(!empty($_POST['idDireccionF']))?$_POST['idDireccionF']:NULL;
  $id_subdireccion_funcional=(!empty($_POST['idSubDireccionF']))?$_POST['idSubDireccionF']:NULL;
  $id_depto_funcional=(!empty($_POST['idDepartamentoF']))?$_POST['idDepartamentoF']:NULL;
  $id_seccion_funcional=(!empty($_POST['idSeccionF']))?$_POST['idSeccionF']:NULL;
  $id_puesto_f=(!empty($_POST['idPuestoF']))?$_POST['idPuestoF']:NULL;
  $id_nivel_funcional=(!empty($_POST['idNivelF']))?$_POST['idNivelF']:NULL;
  $check=(!empty($_POST['chkFuncional']))?$_POST['chkFuncional']:NULL;

  //echo $id_empleado;
  $clase=new plaza;
  $pdo = Database::connect_sqlsrv();
  $cod_empleado = 0;
  if($tipo_accion == 1){
    //inicio
    $pd=$clase->get_plaza_by_id($id_plaza);

    if(empty($check)){
      $id_secretaria_funcional=4;//$pd['id_secretaria_funcional'];
      $id_subsecretaria_funcional=$pd['id_subsecretaria_funcional'];
      $id_direccion_funcional=$pd['id_direccion_funcional'];
      $id_subdireccion_funcional=$pd['id_subdireccion_funcional'];
      $id_depto_funcional=$pd['id_depto_funcional'];
      $id_seccion_funcional=$pd['id_seccion_funcional'];
      $id_puesto_f=$pd['id_puesto'];
      $id_nivel_funcional=$pd['id_nivel_funcional'];
    }

    /*try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      /*if(empty($id_empleado)){
        $sql0 = "INSERT INTO rrhh_empleado ()
                    SET fecha=?, descripcion=?,fecha_acuerdo_baja=?,nro_acuerdo_baja=?,fecha_efectiva_resicion=?, id_status=?
                  WHERE id_asignacion=?";
        $q0 = $pdo->prepare($sql0);
      }*/
      $sql22 = "UPDATE rrhh_persona
      SET id_tipo_aspirante = ?, tipo_persona = ?, id_status = ?  WHERE id_persona=?";
      $q22 = $pdo->prepare($sql22);
      $q22->execute(array(NULL,1050,1026,$id_persona));


      //seleccionar asignación actual
      if(!empty($id_empleado)){
        $asignacion_actual=$clase->get_asignacion($id_empleado);

        //actualizar asignación actual si es ascenso
        $ascenso='';
        if(!empty($asignacion_actual['id_asignacion'])){
          if($asignacion_actual['id_status']==891){
            $sql0 = "UPDATE rrhh_empleado_plaza
                        SET fecha=?, descripcion=?,fecha_acuerdo_baja=?,nro_acuerdo_baja=?,fecha_efectiva_resicion=?, id_status=?
                      WHERE id_asignacion=?";
            $q0 = $pdo->prepare($sql0);
            $q0->execute(array(date('Y-m-d H:i:s'),$observaciones,$id_fecha_acuerdo_asignacion,$id_nro_acuerdo,$id_fecha_toma_posesion,1210,$asignacion_actual['id_asignacion']));

            //actualizar plaza actual a estado vacante.
            $sql3 = "UPDATE rrhh_plaza SET id_status=? WHERE id_plaza=?";
            $q3 = $pdo->prepare($sql3);
            $q3->execute(array(890,$asignacion_actual['id_plaza']));

            //ingresa historial de la plaza
            echo json_encode($asignacion_actual);
            $clase->ingresar_historial($asignacion_actual['id_asignacion'],$asignacion_actual['id_plaza'],$observaciones,date('Y-m-d H:i:s'),1210,$asignacion_actual['id_sueldo'],$asignacion_actual['id_sueldo']);
          }
          $ascenso='Descripcion: ASCENSO';
        }

      }else{
        $clase_e = new empleado;
        $nro_actual = $clase_e->get_nro_empleado();
        $sql1 = "INSERT INTO rrhh_empleado (nro_empleado,id_persona,id_contrato,fecha_ingreso,id_status,observaciones)
                      VALUES(?,?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array($nro_actual,$id_persona,7,date('Y-m-d H:i:s'),891,$observaciones));

        $id_empleado = $pdo->lastInsertId();
        $cod_empleado = $pdo->lastInsertId();
        $ascenso = '';
      }

      if(is_numeric($id_empleado)){
        //insertar empleado plaza
        $sql1 = "INSERT INTO rrhh_empleado_plaza
                            (fecha,descripcion,id_empleado,id_plaza,fecha_toma_posesion,fecha_acuerdo,nro_acuerdo,id_status)
                      VALUES(?,?,?,?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(date('Y-m-d'),$observaciones,$id_empleado,$id_plaza,$id_fecha_toma_posesion,$id_fecha_acuerdo_asignacion,$id_nro_acuerdo,891));

        //actualizar empleado
        $sql2 = "UPDATE rrhh_empleado SET id_status=?, id_contrato=? WHERE id_empleado=?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array(891,7,$id_empleado));

        //actualizar plaza a estado ocupado.
        $sql3 = "UPDATE rrhh_plaza SET id_status=? WHERE id_plaza=?";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array(889,$id_plaza));

        //seleccionar asignación actual
        $asignacion_nueva=$clase->get_asignacion($id_empleado);

        //actualizar sueldo
        $sql2 = "UPDATE rrhh_empleado SET id_sueldo_plaza=?, id_sueldo_empleado=? WHERE id_empleado=?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($asignacion_nueva['id_sueldo'],$asignacion_nueva['id_sueldo'],$id_empleado));



        $top_historial=$clase->get_top_hst_plaza($id_plaza);

        if(!empty($top_historial['reng_num'])){
          $sql = "UPDATE rrhh_hst_plazas SET flag_ubicacion_actual=?, fecha_modificacion=? WHERE id_plaza=? AND reng_num=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(0,date('Y-m-d H:i:s'),$top_historial['id_plaza'],$top_historial['reng_num']));

        }

        empleado::guardar_historial_asignacion_puesto($asignacion_nueva['id_asignacion'],$id_nivel_funcional,
        $id_secretaria_funcional,
        $id_subsecretaria_funcional,$id_direccion_funcional,$id_subdireccion_funcional,
        $id_depto_funcional,$id_seccion_funcional,
        $id_puesto_f,$id_nro_acuerdo,$observaciones,
        date('Y-m-d H:i:s',strtotime($id_fecha_toma_posesion)),date('Y-m-d'),$id_fecha_acuerdo_asignacion);





        $clase->ingresar_historial_hst_plaza($id_plaza,$id_puesto_f,$pd['id_nivel_presupuestario'],$pd['id_secretaria_presupuestario'],$pd['id_subsecretaria_presupuestaria'],
                                              $pd['id_direccion_presupuestaria'],$pd['id_subdireccion_presupuestaria'],$pd['id_depto_presupuestario'],$pd['id_seccion_presupuestario'],
                                              $id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,$id_direccion_funcional,$id_subdireccion_funcional,
                                              $id_depto_funcional,$id_seccion_funcional,$asignacion_nueva['id_sueldo']);
                                              //ingresa historial de la plaza
                                              $clase->ingresar_historial($asignacion_nueva['id_asignacion'],$asignacion_nueva['id_plaza'],$observaciones,date('Y-m-d H:i:s'),1210,$asignacion_nueva['id_sueldo'],$asignacion_nueva['id_sueldo']);

        $desc_bitacora="Plaza asignada: ID asignacion: ".$asignacion_nueva['id_asignacion']. "; Fecha de asignacion: ".date('Y-m-d H:i:s')."; ".$ascenso." ID empleado: ".$id_empleado."; ID plaza: ".$id_plaza."; Fecha de toma de posesión: ".$id_fecha_toma_posesion."; Fecha de acuerdo de asignación: ".$id_fecha_acuerdo_asignacion."; Número de acuerdo de Asignación: ".$id_nro_acuerdo."; Estado: 891;";

        insertar_bitacora(2597,$desc_bitacora,187);
        createLog(74, 1163, 'rrhh_empleado_plaza','Se asignó la plaza: id_plaza'.$id_plaza.' empleado id_persona: '.$id_persona,'', '');
      }
    //fin
  }else if($tipo_accion == 2){
    // code...
    $sql0 = "UPDATE rrhh_empleado_plaza
                SET descripcion=?,
                    fecha_toma_posesion=?,
                    fecha_acuerdo=?,
                    nro_acuerdo=?

              WHERE id_asignacion=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($observaciones,$id_fecha_toma_posesion,$id_fecha_acuerdo_asignacion,$id_nro_acuerdo,$id_asignacionA));
  }

  echo $cod_empleado;

  /*$pdo->commit();
  }catch (PDOException $e){
    echo $e;
    try{ $pdo->rollBack();}catch(Exception $e2){
      echo $e2;
    }
  }*/



  Database::disconnect_sqlsrv();

  else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
