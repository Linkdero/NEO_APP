<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_contratos.php';
  date_default_timezone_set('America/Guatemala');

  $tipo_accion = $_POST['tipo_de_accion'];
  $id_reng_num = (!empty($_POST['reng_num']))?$_POST['reng_num']:NULL;
  $id_persona=$_POST['id_gafete'];
  $id_empleado=(!empty($_POST['id_empleado']))?$_POST['id_empleado']:NULL;

  $id_nro_acuerdo=$_POST['id_nro_acuerdo_ct'];
  $id_fecha_aprobacion=date('Y-m-d', strtotime($_POST['id_fecha_aprobacion_ct']));
  $id_monto=$_POST['id_monto_ct'];
  $id_monto_mensual=$_POST['id_monto_ct_mensual'];
  $id_tipo_contrato=$_POST['id_tipo_contrato_ct'];

  $id_nro_contrato=$_POST['id_nro_contrato_ct'];
  $id_fecha_contrato=date('Y-m-d', strtotime($_POST['id_fecha_contrato_ct']));
  $id_fecha_inicio=date('Y-m-d', strtotime($_POST['id_fecha_inicio_ct']));
  $id_fecha_fin=date('Y-m-d', strtotime($_POST['id_fecha_fin_ct']));

  $observaciones=$_POST['id_detalle_asignacio_p_ct'];

  $tipo_de_servicio_ct = (!empty($_POST['tipo_de_servicio_ct'])) ? $_POST['tipo_de_servicio_ct'] : NULL;

  $id_categoria=$_POST['id_categoria_ct'];
  $id_secretaria_funcional=4;
  $id_subsecretaria_funcional=(!empty($_POST['idSubSecretariaF']))?$_POST['idSubSecretariaF']:NULL;
  $id_direccion_funcional=(!empty($_POST['idDireccionF']))?$_POST['idDireccionF']:NULL;
  $id_subdireccion_funcional=(!empty($_POST['idSubDireccionF']))?$_POST['idSubDireccionF']:NULL;
  $id_depto_funcional=(!empty($_POST['idDepartamentoF']))?$_POST['idDepartamentoF']:NULL;
  $id_seccion_funcional=(!empty($_POST['idSeccionF']))?$_POST['idSeccionF']:NULL;
  $id_puesto_f=(!empty($_POST['idPuestoF']))?$_POST['idPuestoF']:NULL;
  $id_nivel_funcional=(!empty($_POST['idNivelF']))?$_POST['idNivelF']:NULL;

  //echo $id_puesto_f;

  //echo $id_empleado;
  $clase=new contrato;
  $pdo = Database::connect_sqlsrv();

  $yes = '';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $cod_empleado = 0;
    $puesto_servicio = '';
    if($id_tipo_contrato == 8){
      $puesto_servicio = 7089;
    }else if($id_tipo_contrato == 9){
      $puesto_servicio = 1322;
    }
    if($tipo_accion == 1){
      //agregar nuevo
      //seleccionar asignación actual
      if(!empty($id_empleado)){
        $asignacion_actual=$clase->get_contrato_activo($id_empleado);


        //actualizar asignación actual si es ascenso
        $ascenso='';
        if(!empty($asignacion_actual['reng_num'])){
          if($asignacion_actual['id_status']==908){
            $sql0 = "UPDATE rrhh_empleado_contratos
                        SET id_status=?, nro_acuerdo_resicion=?, fecha_efectiva_resicion=?  WHERE reng_num=?";
            $q0 = $pdo->prepare($sql0);
            $q0->execute(array(911,$id_nro_acuerdo,$id_fecha_aprobacion,$asignacion_actual['reng_num']));
          }
        }
      }else{
        $clase_e = new empleado;
        $nro_actual = $clase_e->get_nro_empleado();
        $sql1 = "INSERT INTO rrhh_empleado (nro_empleado,id_persona,id_contrato,fecha_ingreso,id_status,observaciones)
                      VALUES(?,?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array($nro_actual,$id_persona,1075,date('Y-m-d H:i:s'),891,$observaciones));

        $id_empleado = $pdo->lastInsertId();
        $cod_empleado = $pdo->lastInsertId();
        $ascenso = '';
      }

      if (is_numeric($id_empleado)) {
        // code...
        //insertar empleado contrato
        $sql1 = "INSERT INTO rrhh_empleado_contratos
                            (id_empleado, nro_contrato,tipo_contrato, fecha_contrato, fecha_inicio,fecha_finalizacion,nro_acuerdo_aprobacion,
                            fecha_acuerdo_aprobacion,id_status,monto_contrato,monto_mensual, id_puesto_servicio, id_nivel_servicio, id_secretaria_servicio, id_subsecretaria_servicio,
                          id_direccion_servicio, id_subdireccion_servicio, id_depto_servicio, id_seccion_servicio,id_categoria,id_puesto_funcional,observaciones,id_tipo_servicio)
                      VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);



        $q1->execute(array($id_empleado,$id_nro_contrato,$id_tipo_contrato,$id_fecha_contrato,$id_fecha_inicio,$id_fecha_fin,$id_nro_acuerdo,
                           $id_fecha_aprobacion,908,$id_monto,$id_monto_mensual,$puesto_servicio,$id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,
                           $id_direccion_funcional,$id_subdireccion_funcional,$id_depto_funcional,$id_seccion_funcional,$id_categoria,$id_puesto_f,$observaciones,$tipo_de_servicio_ct));

        $sql0 = "UPDATE rrhh_empleado
        SET id_status=?, id_sueldo_plaza=?, id_contrato=?  WHERE id_persona=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(891,0,1075,$id_persona));

        $sql22 = "UPDATE rrhh_persona
        SET id_tipo_aspirante = ?, tipo_persona = ?, id_status = ?  WHERE id_persona=?";
        $q22 = $pdo->prepare($sql22);
        $q22->execute(array(NULL,1050,1026,$id_persona));

      }

    }else if($tipo_accion == 2){
      //actualizar información

      $sql1 = "UPDATE rrhh_empleado_contratos
                SET nro_contrato=?,tipo_contrato=?, fecha_contrato=?, fecha_inicio=?,fecha_finalizacion=?,nro_acuerdo_aprobacion=?,
                    fecha_acuerdo_aprobacion=?,monto_contrato=?,monto_mensual=?, id_puesto_servicio=?, id_nivel_servicio=?,
                    id_secretaria_servicio=?, id_subsecretaria_servicio=?,
                    id_direccion_servicio=?, id_subdireccion_servicio=?, id_depto_servicio=?, id_seccion_servicio=?,id_categoria=?,id_puesto_funcional=?,observaciones=?, id_tipo_servicio = ?
                WHERE reng_num = ?";
      $q1 = $pdo->prepare($sql1);

      $q1->execute(array($id_nro_contrato,$id_tipo_contrato,$id_fecha_contrato,$id_fecha_inicio,$id_fecha_fin,$id_nro_acuerdo,
                         $id_fecha_aprobacion,$id_monto,$id_monto_mensual,$puesto_servicio,$id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,
                         $id_direccion_funcional,$id_subdireccion_funcional,$id_depto_funcional,$id_seccion_funcional,$id_categoria,$id_puesto_f,$observaciones,$tipo_de_servicio_ct,
                       $id_reng_num));
    }

    echo $cod_empleado;

  $pdo->commit();
  }catch (PDOException $e){
    $yes =  $e;
    try{ $pdo->rollBack();}catch(Exception $e2){
      echo $e2;
    }
  }

  echo json_encode($yes);



  Database::disconnect_sqlsrv();

  else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
