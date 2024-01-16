<?php
class plaza{
  static function get_plazas_por_empleado($id_persona,$tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT b.id_asignacion, a.id_plaza, a.partida_presupuestaria, b.fecha_toma_posesion, b.fecha_efectiva_resicion, d.primer_nombre, d.segundo_nombre, d.tercer_nombre, d.primer_apellido, d.segundo_apellido, d.tercer_apellido,
                   e.descripcion AS puesto, SUELDO, a.cod_plaza, b.id_status, f.descripcion AS estado, sb.SUELDOB,
                   CASE WHEN b.id_status IN (1034,1212,3805,3734,1032,5509,892,3735,893,6646,5610,1033) THEN 'fin' WHEN b.id_status IN (1210) THEN 'asc' WHEN b.id_status IN (891) THEN 'act' ELSE 'none' END AS validacion,
                   b.id_remocion_reingreso
            FROM rrhh_plaza a
            INNER JOIN rrhh_empleado_plaza b ON b.id_plaza=a.id_plaza
            INNER JOIN rrhh_empleado c ON b.id_empleado=c.id_empleado
            INNER JOIN rrhh_persona d ON c.id_persona=d.id_persona
            LEFT JOIN rrhh_plazas_puestos e ON a.id_puesto=e.id_puesto
            LEFT JOIN tbl_catalogo_detalle f ON b.id_status=f.id_item
            LEFT JOIN (SELECT b.id_plaza,
            SUM(c.monto_p) AS SUELDO
                        FROM rrhh_plazas_sueldo b
            LEFT JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo
            LEFT JOIN rrhh_plazas_sueldo_conceptos d ON c.id_concepto = d.id_concepto
            WHERE b.actual=1 AND d.aplica_plaza = 1
            group by b.id_plaza) AS sd ON sd.id_plaza=a.id_plaza
            LEFT JOIN (SELECT b.id_plaza,
            c.monto_p AS SUELDOB
                        FROM rrhh_plazas_sueldo b
            LEFT JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo
			         WHERE b.actual=1 AND c.id_concepto = 1
            ) AS sb ON sb.id_plaza=a.id_plaza
            WHERE d.id_persona=?
            ";
            if($tipo == 2){
              $sql.=" ORDER BY b.id_asignacion ASC";
            }else{
              $sql.=" ORDER BY b.id_asignacion DESC";
            }

    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $plazas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $plazas;
  }

  function get_empleado_plaza_actual($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 b.nro_acuerdo,c.id_persona, a.id_plaza, a.cod_plaza,
            b.id_asignacion,
            a.partida_presupuestaria,b.fecha_acuerdo,b.descripcion AS desc_asignacion,
            b.fecha_toma_posesion,
            b.fecha_acuerdo_baja, b.fecha_efectiva_resicion,
            c.id_status AS emp_estado, c.id_contrato
            FROM rrhh_plaza a
            INNER JOIN rrhh_empleado_plaza b ON b.id_plaza=a.id_plaza
            INNER JOIN rrhh_empleado c ON b.id_empleado=c.id_empleado
            WHERE c.id_persona=?
            ORDER BY b.id_asignacion DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $plaza = $p->fetch();
    Database::disconnect_sqlsrv();
    return $plaza;
  }

  static function get_sueldo_actual_by_plaza($id_plaza){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_sueldo, id_plaza, actual, fecha_asignacion, observaciones
            FROM rrhh_plazas_sueldo
            WHERE id_plaza=? AND actual=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_plaza,1));
    $sueldo = $p->fetch();
    Database::disconnect_sqlsrv();
    return $sueldo;
  }

  static function get_reng_siguiente_by_historial($id_asignacion){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP(1) reng_num
            FROM rrhh_empleado_plaza_hst
            WHERE id_asignacion=?
            ORDER BY reng_num DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_asignacion));
    $reng = $p->fetch();
    Database::disconnect_sqlsrv();
    if(empty($reng['reng_num'])){
      return 1;
    }else{
      return $reng['reng_num']+1;
    }

  }

  static function get_plazas_disponibles(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_plaza, a.cod_plaza, a.partida_presupuestaria, a.id_status, a.descripcion, a.id_puesto, b.nombre_direccion_presupuestaria
            FROM rrhh_plaza a
            INNER JOIN xxx_rrhh_hst_plazas b ON b.id_plaza=a.id_plaza
            WHERE a.id_status=? AND b.flag_ubicacion_actual=?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array(890,1))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_plaza_by_id($id_plaza){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 * FROM rrhh_hst_plazas WHERE id_plaza=? ORDER BY reng_num DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_plaza));
    $response=$stmt->fetch();
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_plaza_by_id_sueldo($id_plaza){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT  CASE WHEN c.id_contrato = 7 THEN '011' ELSE -- Renglon 011
                    CASE WHEN c.id_contrato = 1075 AND cnt.tipo_contrato = 8 THEN '031' ELSE -- Renglon 031
                    CASE WHEN c.id_contrato = 1075 and cnt.tipo_contrato = 9 then '029' ELSE -- Renglon 029
                    ' ' END END END AS Renglon, A.id_plaza, A.cod_plaza, A.codigo_puesto_oficial, A.partida_presupuestaria, A.Cod_estado AS cod_estado_plaza, A.Estado AS estado_plaza, A.descripcion AS descripcion_plaza, A.reng_num AS reng_num_plaza,
                         A.codigo_puesto_presupuestario, A.fecha_modificacion, A.id_jerarquia_presupuestario, A.nivel_presupuestario, A.nivel_presupuestario_ubicacion, A.nivel_presupuestario_superior, A.nombre_nivel_presupuestario,
                         COALESCE (A.nombre_seccion_presupuestaria, A.nombre_depto_presupuestario, A.nombre_subdireccion_presupuestaria, A.nombre_direccion_presupuestaria, A.nombre_subsecretaria_presupuestario,
                         A.nombre_secretaria_presupuestario) AS nombre_ubicacion_presupuestaria, A.id_secretaria_presupuestario, A.nombre_secretaria_presupuestario, A.id_subsecretaria_presupuestaria, A.nombre_subsecretaria_presupuestario,
                         A.id_direccion_presupuestaria, A.nombre_direccion_presupuestaria, A.id_subdireccion_presupuestaria, A.nombre_subdireccion_presupuestaria, A.id_depto_presupuestario, A.nombre_depto_presupuestario,
                         A.id_seccion_presupuestario, A.nombre_seccion_presupuestaria, A.Puesto_presupuestario, A.id_jerarquia_funcional, A.nivel_funcional, A.nivel_funcional_ubicacion, A.nivel_funcional_superior, A.nombre_nivel_funcional,
                         COALESCE (A.nombre_seccion_funcional, A.nombre_depto_funcional, A.nombre_subdireccion_funcional, A.nombre_direccion_funcional, A.nombre_subsecretaria_funcional, A.nombre_secretaria_funcional)
                         AS nombre_ubicacion_funcional, A.id_secretaria_funcional, A.nombre_secretaria_funcional, A.id_subsecretaria_funcional, A.nombre_subsecretaria_funcional, A.id_direccion_funcional, A.nombre_direccion_funcional,
                         A.id_subdireccion_funcional, A.nombre_subdireccion_funcional, A.id_depto_funcional, A.nombre_depto_funcional, A.id_seccion_funcional, A.nombre_seccion_funcional, A.id_puesto_funcional, A.nombre_puesto_funcional,
                         ISNULL(C.id_empleado, 0) AS id_empleado, ISNULL(C.nro_empleado, 0) AS nro_empleado, ISNULL(C.id_persona, 0) AS id_persona, ISNULL(C.tipo_persona, 0) AS tipo_persona, ISNULL(C.nombre_tipoPersona, '')
                         AS nombre_tipoPersona, C.Nombre_Completo, ISNULL(C.primer_nombre, '') AS primer_nombre, ISNULL(C.segundo_nombre, '') AS segundo_nombre, ISNULL(C.tercer_nombre, '') AS tercer_nombre, ISNULL(C.primer_apellido, '')
                         AS primer_apellido, ISNULL(C.segundo_apellido, '') AS segundo_apellido, ISNULL(C.tercer_apellido, '') AS tercer_apellido,
                          c.id_contrato, A.id_sueldo_plaza, Ps.monto_sueldo_plaza, Pv.monto_sueldo_base, B.fecha_toma_posesion,
                          A.Cod_estado
              FROM  dbo.xxx_rrhh_hst_plazas AS A
              LEFT OUTER JOIN dbo.rrhh_empleado_plaza AS B ON A.id_plaza = B.id_plaza AND B.id_status = 891
              LEFT OUTER JOIN dbo.xxx_rrhh_empleado_persona AS C ON C.id_empleado = B.id_empleado
              LEFT OUTER JOIN dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = c.id_empleado AND cnt.id_status = 908
              LEFT OUTER JOIN  -- contratos ACTIVOS (029 Y 031)
                (SELECT A.id_sueldo AS id_sueldo_plaza, SUM(B.monto_p) AS monto_sueldo_plaza
                  FROM  dbo.rrhh_plazas_sueldo AS A
                  INNER JOIN dbo.rrhh_plazas_sueldo_detalle AS B ON A.id_sueldo = B.id_sueldo
                  LEFT OUTER JOIN dbo.rrhh_plazas_sueldo_conceptos AS C ON B.id_concepto = C.id_concepto
                  WHERE (A.actual = 1) AND C.aplica_plaza = 1
                  GROUP BY A.id_sueldo) AS Ps ON Ps.id_sueldo_plaza = A.id_sueldo_plaza
                  LEFT OUTER JOIN (SELECT A.id_sueldo AS id_sueldo_plaza, SUM(B.monto_p) AS monto_sueldo_base
                                    FROM dbo.rrhh_plazas_sueldo AS A
                                    INNER JOIN dbo.rrhh_plazas_sueldo_detalle AS B ON A.id_sueldo = B.id_sueldo
                                    LEFT OUTER JOIN dbo.rrhh_plazas_sueldo_conceptos AS C ON B.id_concepto = C.id_concepto
                                    WHERE (A.actual = 1) AND (C.bln_aplica_viatico = 1) AND C.aplica_plaza = 1
                                    GROUP BY A.id_sueldo) AS Pv ON Pv.id_sueldo_plaza = A.id_sueldo_plaza
                                    WHERE (A.flag_ubicacion_actual = 1) AND A.id_plaza=?
                                    ORDER BY Renglon";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_plaza));
    $response=$stmt->fetch();
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_plaza_detalle_by_id($id_plaza){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP (1) * FROM xxx_rrhh_hst_plazas WHERE id_plaza=? ORDER BY reng_num DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_plaza));
    $response=$stmt->fetch();
    Database::disconnect_sqlsrv();
    return $response;
  }

  function get_asignacion($id_empleado){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 a.id_asignacion, a.id_plaza,b.id_sueldo,a.id_status
             FROM rrhh_empleado_plaza a
             INNER JOIN rrhh_plazas_sueldo b ON a.id_plaza=b.id_plaza
             WHERE a.id_empleado=? AND b.actual=? ORDER BY a.id_asignacion DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_empleado,1));
    $asignacion_actual=$q0->fetch();
    Database::disconnect_sqlsrv();
    return $asignacion_actual;
  }

  function get_top_hst_plaza($id_plaza){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP (1) id_plaza, reng_num FROM rrhh_hst_plazas WHERE id_plaza=? ORDER BY reng_num DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_plaza));
    $top_historial=$q0->fetch();
    Database::disconnect_sqlsrv();
    return $top_historial;
  }

  function ingresar_historial($id_asignacion,$id_plaza,$descripcion,$fecha_modificacion,$id_status,$id_sueldo,$id_sueldo_plaza){
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //$sql0 = "SELECT TOP 1 reng_num FROM rrhh_hst_plazas WHERE id_plaza = ? ORDER BY reng_num DESC";
      $sql0 = "SELECT TOP 1 reng_num FROM rrhh_empleado_plaza_hst WHERE id_asignacion = ? ORDER BY reng_num DESC";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($id_asignacion));
      $reng_num=$q0->fetch();


      $reng_ = 1;
      $reng_ = (!empty($reng_num['reng_num'])) ? $reng_num['reng_num'] + 1 : 1;


      $sql = "INSERT INTO rrhh_empleado_plaza_hst (id_asignacion,reng_num,id_plaza,id_plaza_reng,descripcion,fecha_modificacion,id_status,id_sueldo_empleado,id_sueldo_plaza)
                          VALUES(?,?,?,?,?,?,?,?,?)";
      $p = $pdo->prepare($sql);
      $p->execute(array($id_asignacion,$reng_,$id_plaza,$reng_,$descripcion,$fecha_modificacion,$id_status,$id_sueldo,$id_sueldo_plaza));

      $pdo->commit();
    }catch (PDOException $e){
      echo json_encode($e);
      try{ $pdo->rollBack();}catch(Exception $e2){
        echo json_encode($e2);
      }
    }

    Database::disconnect_sqlsrv();

  }

  function ingresar_historial_hst_plaza($id_plaza,$id_puesto,$id_nivel_presupuestario,$id_secretaria_presupuestario,$id_subsecretaria_presupuestaria,
                                        $id_direccion_presupuestaria,$id_subdireccion_presupuestaria,$id_depto_presupuestario,$id_seccion_presupuestario,
                                        $id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,$id_direccion_funcional,$id_subdireccion_funcional,
                                        $id_depto_funcional,$id_seccion_funcional,$id_sueldos){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 reng_num, id_plaza FROM rrhh_hst_plazas WHERE id_plaza=? ORDER BY reng_num DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_plaza));
    $reng_num=$q0->fetch();

    $reng = 1;
    if(!empty($reng_num['reng_num'])){
      $sql1 = "UPDATE rrhh_hst_plazas SET flag_ubicacion_actual = ? WHERE id_plaza=? AND reng_num = ?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array(0,$id_plaza,$reng_num['reng_num']));
      //$reng_num=$q1->fetch();
      $reng = $reng_num['reng_num'] + 1;
    }


    $sql = "INSERT INTO rrhh_hst_plazas (id_plaza,reng_num,id_puesto,observaciones,fecha_modificacion,id_nivel_presupuestario,id_secretaria_presupuestario,id_subsecretaria_presupuestaria,
                                         id_direccion_presupuestaria,id_subdireccion_presupuestaria,id_depto_presupuestario,id_seccion_presupuestario,id_nivel_funcional,id_secretaria_funcional,
                                         id_subsecretaria_funcional,id_direccion_funcional,id_subdireccion_funcional,id_depto_funcional,id_seccion_funcional,id_sueldos,
                                         flag_cambio_sueldo,flag_ubicacion_actual,id_auditoria)
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_plaza,$reng,$id_puesto,'',date('Y-m-d H:i:s'),$id_nivel_presupuestario,$id_secretaria_presupuestario,$id_subsecretaria_presupuestaria,
                                          $id_direccion_presupuestaria,$id_subdireccion_presupuestaria,$id_depto_presupuestario,$id_seccion_presupuestario,
                                          $id_nivel_funcional,$id_secretaria_funcional,$id_subsecretaria_funcional,$id_direccion_funcional,$id_subdireccion_funcional,
                                          $id_depto_funcional,$id_seccion_funcional,$id_sueldos,0,1,0));
    Database::disconnect_sqlsrv();

  }

  function get_puestos_plaza()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_puesto, a.codigo_puesto, a.codigo_puesto_oficial, a.id_categoria, a.id_grupo,
            a.descripcion AS puesto, b.descripcion AS categoria, c.descripcion AS grupo
            FROM rrhh_plazas_puestos a
            INNER JOIN tbl_catalogo_detalle b ON a.id_categoria = b.id_item
            INNER JOIN rrhh_plazas_puestos_grupo c ON a.id_grupo = c.id_grupo

    ";

    //$stmt = $pdo->prepare($sql);
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array())) {
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    } else {
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  function get_sueldos_para_plaza()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_concepto, a.descripcion, a.id_tipo_concepto, a.meses_aplica, a.aplica_plaza
           FROM rrhh_plazas_sueldo_conceptos a WHERE a.id_tipo_concepto = ? AND a.aplica_plaza = ?

    ";

    //$stmt = $pdo->prepare($sql);
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array(944,1))) {
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    } else {
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }


  function get_sueldos_by_id_plaza($id_sueldo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_concepto, a.descripcion, a.id_tipo_concepto, a.meses_aplica, a.aplica_plaza
                  ,b.monto_p, b.monto_n, a.id_tipo_concepto
            FROM rrhh_plazas_sueldo_conceptos a
            LEFT OUTER JOIN rrhh_plazas_sueldo_detalle b ON a.id_concepto = b.id_concepto
            WHERE b.id_sueldo = ? AND a.id_tipo_concepto = ? AND a.aplica_plaza = 1



			OR b.id_concepto IS NULL --AND a.id_tipo_concepto = ? AND a.aplica_plaza = 1
			ORDER BY a.id_concepto ASC
            ";

    //$stmt = $pdo->prepare($sql);
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($id_sueldo,944))) {
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    } else {
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }



  static function convert_direccion_f_from_n($dato){
    switch($dato)
    {
      //sub seguridad
      case 34: return 15; break;
      //sub administrativa
      case 33: return 14; break;

      //seguridad
      //informatica
      case 27: return 8; break;
      //informacion
      case 28: return 9; break;
      //academia
      case 25: return 6; break;
      //asuntos
      case 26: return 7; break;
      //seguridad
      case 31: return 12; break;
      //inspectoria
      case 654: return 655; break;
      //auditoria
      case 21: return 2; break;

    }
  }

  static function convert_subdireccion_f_from_n($sub){
    switch($sub)
    {
      //seguridad
      /*subacademia*/
      case 7: return 22; break;
      /*sub asuntos*/
      case 8: return 23; break;
      /*sub informatica*/
      case 9: return 24; break;
      /*sub informacion*/
      case 10: return 25; break;
      /*sub seguridad*/
      case 13: return 28; break;
    }


  }

  static function convert_departamento_f_from_n($dep){
    switch($dep)
    {
      //seguridad
      /*DEPARTAMENTO ADMINISTRATIVO Y FINANCIERO*/ case 2: return 46; break;
      /*DEPARTAMENTO DE CONTROL ACADEMICO*/ case 8: return 55; break;
      /*DEPARTAMENTO DE INSTRUCCION CIVICA*/ case 17: return 79; break;
      /*DEPARTAMENTO DE INSTRUCCION DE SEGURIDAD*/ case 18: return 66; break;
      /*SECRETARIA, RECEPCION Y ARCHIVO*/ case 36: return 104; break;
      /*DEPARTAMENTO DE CONTROL INTERNO*/ case 9: return 56; break;
      /*DEPARTAMENTO DE INVESTIGACIONES INTERNAS*/ case 19: return 67; break;
      /*SECRETARIA, RECEPCION Y ARCHIVO*/ case 37: return 105; break;
      /*DEPARTAMENTO DE CONTROL INTERNO */ case 126: return 56; break;
      /*DEPARTAMENTO DE DESARROLLO DE APLICACIONES*/ case 12: return 60; break;
      /*DEPARTAMENTO DE INFORMATICA*/ case 16: return 65; break;
      /*DEPARTAMENTO DE RADIOCOMUNICACIONES*/ case 28: return 90; break;
      /*DEPARTAMENTO DE SISTEMAS OPERATIVOS Y REDES*/ case 135: return 136; break;
      /*DEPARTAMENTO DE ANALISIS*/ case 4: return 48; break;
      /*DEPARTAMENTO DE ESTUDIO DE SEGURIDAD*/ case 13: return 61; break;
      /*DEPARTAMENTO DE ESTUDIO DE LOCALIDADES*/ case 14: return 62; break;
      /*DEPARTAMENTO DE ESTUDIOS DE SEGURIDAD*/ case 15: return 61; break;
      /*SECRETARIA, RECEPCION Y ARCHIVO*/ case 38: return 106; break;
      /*DEPARTAMENTO DE ESTUDIO DE FUENTES*/ case 132: return ; break;
      /*DEPARTAMENTO DE CONTROL MAESTRO*/ case 10: return 58; break;
      /*DEPARTAMENTO DE PLANEACION*/ case 23: return 81; break;
      /*DEPARTAMENTO DE SEGURIDAD*/ case 30: return 94; break;
      /*SECRETARIA, INFORMACION Y ARCHIVO*/ case 34: return 102; break;
      /*DEPARTAMENTO DE PROTECCION*/ case 110: return 149; break;

      //administrativa
      /*DEPARTAMENTO ADMINISTRATIVO*/ case 1: return 43; break;
      /*DEPARTAMENTO DE PREPARACION DE ALIMENTOS*/ case 25: return 82; break;
      /*DEPARTAMENTO DE PROTOCOLO*/ case 27: return 89; break;
      /*DEPARTAMENTO DE SERVICIOS*/ case 31: return 130; break;
      /*UNIDAD DE PLANIFICACIÓN*/ case 156: return 157; break;
      /*DEPARTAMENTO DE PREPARACION DE ALIMENTOS*/ case 24: return 83; break;
      /*DEPARTAMENTO DE MANTENIMIENTO Y SERVICIOS GENERALES*/ case 127: return 74; break;
      /*DEPARTAMENTO DE MANTENIMIENTO VEHICULAR*/ case 131: return 70; break;
      /*DEPARTAMENTO DE ACCIONES Y REGISTRO DE PERSONAL*/ case 3: return 47; break;
      /*DEPARTAMENTO DE BIENESTAR LABORAL*/ case 5: return 50; break;
      /*DEPARTAMENTO DE DESARROLLO*/ case 11: return 59; break;
      /*DEPARTAMENTO DE RECLUTAMIENTO Y SELECCION DE PERSONAL*/ case 29: return 92; break;
      /*SECRETARIA, RECEPCION Y ARCHIVO*/ case 39: return 103; break;
      /*DEPARTAMENTO DE COMPRAS*/ case 6: return 51; break;
      /*DEPARTAMENTO DE CONTABILIDAD, INVENTARIO Y ALMACEN*/ case 7: return 54; break;
      /*DEPARTAMENTO DE MANTENIMIENTO DE INSTALACIONES*/ case 20: return 69; break;
      /*DEPARTAMENTO DE MANTENIMIENTO VEHICULAR*/ case 21: return 142; break;
      /*DEPARTAMENTO DE MANTENIMIENTO Y SERVICIOS GENERALES*/ case 22: return 74; break;
      /*DEPARTAMENTO DE PRESUPUESTO*/ case 26: return 87; break;
      /*DEPARTAMENTO DE TESORERIA*/ case 32: return 109; break;
      /*SECRETARIA, RECEPCION Y ARCHIVO*/ case 35: return 108; break;
    }

  }
  static function get_plazas_por_asignacion($id_asignacion){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_asignacion,fecha,descripcion,id_empleado,id_plaza,
            fecha_toma_posesion,fecha_acuerdo,nro_acuerdo,fecha_acuerdo_baja,
            nro_acuerdo_baja,fecha_efectiva_resicion,id_status,id_auditoria
            FROM rrhh_empleado_plaza a
            WHERE id_asignacion = ?
            ORDER BY id_asignacion DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_asignacion));
    $plaza = $p->fetch();
    Database::disconnect_sqlsrv();
    return $plaza;
  }


}

?>
