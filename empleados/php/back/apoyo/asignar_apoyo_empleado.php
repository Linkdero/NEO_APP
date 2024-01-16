<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_contratos.php';
  date_default_timezone_set('America/Guatemala');

  $tipo_accion = (!empty($_POST['tipo_de_accion'])) ? $_POST['tipo_de_accion'] : NULL;
  $id_reng_num = (!empty($_POST['reng_num']))?$_POST['reng_num']:NULL;
  $id_persona=$_POST['id_gafete'];
  $id_empleado=(!empty($_POST['id_empleado']))?$_POST['id_empleado']:NULL;

  $id_fecha_inicio=(!empty($_POST['id_fecha_inicio_apy'])) ? date('Y-m-d', strtotime($_POST['id_fecha_inicio_apy'])) : NULL;
  $observaciones=(!empty($_POST['id_detalle_asignacio_p_apy'])) ? $_POST['id_detalle_asignacio_p_apy'] : NULL;

  $id_categoria=(!empty($_POST['id_categoria_ct'])) ? $_POST['id_categoria_ct'] : NULL;
  $id_secretaria_funcional=4;
  $id_subsecretaria_funcional=(!empty($_POST['idSubSecretariaF']))?$_POST['idSubSecretariaF']:NULL;
  $id_direccion_funcional=(!empty($_POST['idDireccionF']))?$_POST['idDireccionF']:NULL;
  $id_subdireccion_funcional=(!empty($_POST['idSubDireccionF']))?$_POST['idSubDireccionF']:NULL;
  $id_depto_funcional=(!empty($_POST['idDepartamentoF']))?$_POST['idDepartamentoF']:NULL;
  $id_seccion_funcional=(!empty($_POST['idSeccionF']))?$_POST['idSeccionF']:NULL;
  $id_puesto_f=(!empty($_POST['idPuestoF']))?$_POST['idPuestoF']:NULL;
  $id_nivel_funcional=(!empty($_POST['idNivelF']))?$_POST['idNivelF']:NULL;

  $salario_base=(!empty($_POST['salario_base']))?$_POST['salario_base']:NULL;
  $bonificacion=(!empty($_POST['bonificacion']))?$_POST['bonificacion']:NULL;
  $partida_presupuestaria=(!empty($_POST['partida_presupuestaria']))?$_POST['partida_presupuestaria']:NULL;

  $tipo = (!empty($_POST['tipo_ingreso'])) ? $_POST['tipo_ingreso'] : NULL;

  //echo $id_puesto_f;

  //echo $id_empleado;
  $clase=new contrato;
  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($tipo_accion == 1){
      if($tipo == 1){
        //inicio
        //agregar nuevo
        //seleccionar asignación actual
        /*if(!empty($id_empleado)){
        }else{
          $clase_e = new empleado;
          $nro_actual = $clase_e->get_nro_empleado();
          $sql1 = "INSERT INTO rrhh_empleado (nro_empleado,id_persona,id_contrato,fecha_ingreso,id_status,observaciones)
                        VALUES(?,?,?,?,?,?)";
          $q1 = $pdo->prepare($sql1);
          $q1->execute(array($nro_actual,$id_persona,7,date('Y-m-d H:i:s'),2312,$observaciones));

          $id_empleado = $pdo->lastInsertId();
          $ascenso = '';
        }*/


        if (is_numeric($id_persona)) {
          // code...
          $sql1 = "UPDATE rrhh_persona
                    SET tipo_persona=?, id_status=?
                    WHERE id_persona=?";
          $q1 = $pdo->prepare($sql1);

          $q1->execute(array(1052,2312,$id_persona));
          //insertar persona apoyo
          $sql1 = "INSERT INTO rrhh_persona_apoyo
                              (
                                id_persona,
                                id_nivel_servicio,id_secretaria_servicio,id_subsecretaria_servicio,id_direccion_servicio,
                                id_subdireccion_servicio,id_depto_servicio,id_seccion_servicio,id_cargo,
                                id_categoria,salario_base, bonificacion, partida_presupuestaria
                      )
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
          $q1 = $pdo->prepare($sql1);

          $q1->execute(array($id_persona,$id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,
                             $id_direccion_funcional,$id_subdireccion_funcional,$id_depto_funcional,$id_seccion_funcional,$id_puesto_f,$id_categoria,$salario_base,$bonificacion,$partida_presupuestaria));

        }
        $sql22 = "UPDATE rrhh_persona
        SET id_tipo_aspirante=?  WHERE id_persona=?";
        $q22 = $pdo->prepare($sql22);
        $q22->execute(array(NULL,$id_persona));

        //fin
      }else if($tipo == 2){
        //inicio
        if (is_numeric($id_persona)) {
          // code...
          $sql1 = "UPDATE rrhh_persona
                    SET tipo_persona=?, id_status=?
                    WHERE id_persona=?";
          $q1 = $pdo->prepare($sql1);

          $q1->execute(array(9103,9102,$id_persona));
          $reng_num = 1;
          //insertar persona apoyo
          $sql1 = "INSERT INTO rrhh_persona_practicante
                              (
                                id_persona,reng_num,fecha_ini,fecha_fin,horas,establecimiento_id,
                                profesion_id,id_nivel_servicio,id_secretaria_servicio,
                                id_subsecretaria_servicio,id_direccion_servicio,id_subdireccion_servicio,
                                id_depto_servicio,id_seccion_servicio,id_cargo
                      )
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
          $q1 = $pdo->prepare($sql1);

          $q1->execute(array($id_persona,$reng_num,NULL,NULL,NULL,NULL,NULL,$id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,
                             $id_direccion_funcional,$id_subdireccion_funcional,$id_depto_funcional,$id_seccion_funcional,$id_puesto_f));

        }
        $sql22 = "UPDATE rrhh_persona
        SET id_tipo_aspirante=?  WHERE id_persona=?";
        $q22 = $pdo->prepare($sql22);
        $q22->execute(array(NULL,$id_persona));
        //fin
      }

    }else if($tipo_accion == 2){
      //actualizar información

      $sql1 = "UPDATE rrhh_empleado_contratos
                SET nro_contrato=?,tipo_contrato=?, fecha_contrato=?, fecha_inicio=?,fecha_finalizacion=?,nro_acuerdo_aprobacion=?,
                    fecha_acuerdo_aprobacion=?,monto_contrato=?,monto_mensual=?, id_puesto_servicio=?, id_nivel_servicio=?,
                    id_secretaria_servicio=?, id_subsecretaria_servicio=?,
                    id_direccion_servicio=?, id_subdireccion_servicio=?, id_depto_servicio=?, id_seccion_servicio=?,id_categoria=?
                WHERE reng_num = ?";
      $q1 = $pdo->prepare($sql1);

      $q1->execute(array($id_nro_contrato,$id_tipo_contrato,$id_fecha_contrato,$id_fecha_inicio,$id_fecha_fin,$id_nro_acuerdo,
                         $id_fecha_aprobacion,$id_monto,0,$id_puesto_f,$id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,
                         $id_direccion_funcional,$id_subdireccion_funcional,$id_depto_funcional,$id_seccion_funcional,$id_categoria,
                       $id_reng_num));
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
