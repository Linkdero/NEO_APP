<?php
class empleado
{
  function get_empleados($tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo == 5 || $tipo == 3){
      $sql = "SELECT emp.id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,a.id_persona,a.tipo_persona,a.fecha_ingreso,
                     a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,a.id_tipo_servicio,a.id_genero,
                     a.id_procedencia,a.id_auditoria, '' AS estado_emple, e.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                     CASE WHEN a.id_status = 1029 THEN 1 ELSE 0 END AS estado_persona, '' AS asignacion, '' AS id_empleado, '' AS fecha_toma_posesion,
                     '' AS fecha_inicio, '' AS fecha_baja, '' AS fecha_efectiva_resicion, '' AS direccion
                     FROM rrhh_persona a
                     LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                     LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                     LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                     LEFT JOIN tbl_catalogo_detalle d ON a.tipo_persona = d.id_item
                     LEFT JOIN tbl_catalogo_detalle e ON a.id_status = e.id_item ";
                     if($tipo == 5){//aspirantes
                       if(evaluar_flag($_SESSION['id_persona'],1163,38,'flag_actualizar')==1 || evaluar_flag($_SESSION['id_persona'],1163,280,'flag_actualizar')==1)
                       {
                         //echo 'c';
                         $sql.="WHERE a.tipo_persona = 1051 AND a.id_status = 1067 OR a.id_status = 1029 ";
                         createLog(84, 1163, 'rrhh_persona','Visualizando aspirantes en proceso','', '');
                       }else
                       if(evaluar_flag($_SESSION['id_persona'],1163,175,'flag_actualizar')==1)
                       {
                         //echo 'a';
                         $sql.="WHERE a.id_status = 1029 AND ISNULL(a.id_tipo_aspirante,0) = 1 ";
                         createLog(84, 1163, 'rrhh_persona','Visualizando aspirantes en proceso');
                       }else
                       if(evaluar_flag($_SESSION['id_persona'],1163,167,'flag_actualizar')==1)
                       {
                         //echo 'b';
                         $sql.="WHERE (ISNULL(a.id_tipo_aspirante,0) = 2 OR ISNULL(a.id_tipo_aspirante,0) = 3) AND a.id_status = 1029";
                         createLog(84, 1163, 'rrhh_persona','Visualizando aspirantes en proceso','', '');
                       }else{
                         $sql.="WHERE a.id_persona = 1000000"; // filtro si no tiene privilegios
                       }

                     }else if($tipo == 3){//denegados
                       $sql.="WHERE a.id_status IN (1027,1028,1031,1032,5611) ";
                       createLog(84, 1163, 'rrhh_persona','Visualizando empleados denegados','', '');
                     }
                     $sql.="ORDER BY a.id_status,a.id_persona DESC";
    }
    else if($tipo == 1){
      $sql = "SELECT emp.id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,
                     a.id_persona,a.tipo_persona,a.fecha_ingreso,
                     a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,
                     a.observaciones,a.id_tipo_servicio,a.id_genero,a.id_procedencia,
                     a.id_auditoria,b.descripcion AS estado_emple, f.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                     CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
                     (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164)
                     AND a.id_status IN (2312,1030))
                     THEN 1 ELSE 0 END AS estado_persona,g.id_asignacion AS asignacion,
						         CASE WHEN g.fecha_efectiva_resicion IS NULL THEN '' ELSE convert(varchar(50),g.fecha_efectiva_resicion,105) END AS ultima_salida ,
								 CASE WHEN g.tipo_ingreso = 'R' THEN convert(varchar(50),g.inicio,105) ELSE convert(varchar(50),j.inicio,105) END AS fecha_inicio ,
						         CASE WHEN h.fecha_efectiva_resicion IS NULL THEN ''
                     ELSE convert(varchar(50),h.fecha_efectiva_resicion,105) END AS fecha_baja,i.direccion
                     FROM rrhh_persona a
                     LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                     LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                     LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                     LEFT JOIN tbl_catalogo_detalle f ON a.id_status = f.id_item
                     LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion, T.inicio, T.rnk, T.tipo_ingreso
                       FROM
                        (
							SELECT mm.id_asignacion, mm.id_empleado, mm.fecha_toma_posesion,  ROW_NUMBER() OVER (PARTITION BY mm.id_empleado ORDER BY mm.id_asignacion ASC) AS rnk,
							b.fecha_efectiva_resicion, mm.fecha_toma_posesion AS inicio, 'R' AS tipo_ingreso
							FROM rrhh_empleado_plaza mm

						  INNER JOIN (
							SELECT DISTINCT l.*,  ROW_NUMBER() OVER (PARTITION BY l.id_empleado ORDER BY l.id_asignacion DESC) AS rnk
							FROM rrhh_empleado_plaza l
							WHERE l.id_status IN (892,893,1032,1033,3727,3733,3734,3735,3805,5509,5610,6646,7349,7350) AND ISNULL(l.id_remocion_reingreso,0) = 0
							--ORDER BY mm.id_asignacion ASC
							--GROUP BY mm.id_empleado, mm.fecha_toma_posesion, mm.id_asignacion
						  ) AS b ON mm.id_asignacion > b.id_asignacion AND b.id_empleado = mm.id_empleado

						  --AND mm.id_empleado = 617 --AND b.rnk = 15
						 AND b.rnk = 1
                        ) T
                        WHERE T.rnk = 1 --AND T.id_empleado = 1504
                      ) AS g ON g.id_empleado = emp.id_empleado
                      LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion
                        FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion DESC) AS rnk FROM rrhh_empleado_plaza a
                        ) T
                        WHERE T.rnk = 1
                      ) AS h ON h.id_empleado = emp.id_empleado
                      LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion, T.direccion
                        FROM
                        (
                          SELECT c.id_plaza, a.reng_num, c.id_empleado, c.id_asignacion,
            						  c.fecha_efectiva_resicion,d.descripcion AS direccion,
            						  ROW_NUMBER() OVER (PARTITION BY a.id_plaza ORDER BY a.reng_num DESC) AS rnk
            						  FROM rrhh_hst_plazas a
            						  --INNER JOIN rrhh_plaza b ON a.id_plaza = b.id_plaza
                                      INNER JOIN rrhh_empleado_plaza c ON a.id_plaza = c.id_plaza
            						  INNER JOIN rrhh_direcciones d ON a.id_direccion_funcional = d.id_direccion
            						  WHERE   c.id_status = 891

                        ) T
                        WHERE T.rnk = 1
                      ) AS i ON i.id_empleado = emp.id_empleado
					  LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.inicio, T.rnk, T.tipo_ingreso
                       FROM
                        (
							SELECT mm.id_asignacion, mm.id_empleado, mm.fecha_toma_posesion,  ROW_NUMBER() OVER (PARTITION BY mm.id_empleado ORDER BY mm.id_asignacion ASC) AS rnk,
							mm.fecha_toma_posesion AS inicio, 'R' AS tipo_ingreso
							FROM rrhh_empleado_plaza mm
                        ) T
                        WHERE T.rnk = 1 --AND T.id_empleado = 1504
                      ) AS j ON j.id_empleado = emp.id_empleado
                      WHERE emp.id_status IN (891) --AND a.id_persona = 7610
                      ORDER BY estado_persona DESC,a.id_persona ASC


                      ";

                      createLog(85, 1163, 'rrhh_persona','Visualizando empleados activos','', '');
    }else if($tipo == 2){//empleados de baja
      $sql = "SELECT emp.id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,
                     a.id_persona,a.tipo_persona,a.fecha_ingreso,
                     a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,
                     a.observaciones,a.id_tipo_servicio,a.id_genero,a.id_procedencia,
                     a.id_auditoria,b.descripcion AS estado_emple, f.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                     CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350,909) OR
                     (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164)
                     AND a.id_status IN (2312,1030))
                     THEN 1 ELSE 0 END AS estado_persona,g.id_asignacion AS asignacion,
						         CASE WHEN g.fecha_toma_posesion IS NULL THEN '' ELSE convert(varchar(50),g.fecha_toma_posesion,105) END AS fecha_inicio ,
						         CASE WHEN h.fecha_efectiva_resicion IS NULL THEN ''
                     ELSE convert(varchar(50),h.fecha_efectiva_resicion,105) END AS fecha_baja, '' AS direccion
                     FROM rrhh_persona a
                     LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                     LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                     LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                     LEFT JOIN tbl_catalogo_detalle f ON a.id_status = f.id_item
                     LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_toma_posesion
                       FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion ASC) AS rnk FROM rrhh_empleado_plaza a
                        ) T
                        WHERE T.rnk = 1
                      ) AS g ON g.id_empleado = emp.id_empleado
                      LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion
                        FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion DESC) AS rnk FROM rrhh_empleado_plaza a
                        ) T
                        WHERE T.rnk = 1
                      ) AS h ON h.id_empleado = emp.id_empleado
                      WHERE emp.id_status IN (7,3727,3733,1034,1026,3805,3734,1032,1012,892,3733,3735,893,6646,5610,1033,909)
                      OR a.id_status IN (9189)
                      ORDER BY estado_persona DESC,a.id_persona ASC
                      ";

                      createLog(84, 1163, 'rrhh_persona','Visualizando empleados de baja','', '');
    }
    else if($tipo == 4){//personal de apoyo
      $sql = "SELECT 0 AS id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,a.id_persona,a.tipo_persona,a.fecha_ingreso,
                     a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,a.id_tipo_servicio,a.id_genero,
                     a.id_procedencia,a.id_auditoria, '' AS estado_emple, e.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                     1 estado_persona, '' AS asignacion, '' AS id_empleado, '' AS fecha_toma_posesion,
                     '' AS fecha_inicio, '' AS fecha_baja, '' AS fecha_efectiva_resicion, '' AS direccion
                     FROM rrhh_persona a
                     --LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                     LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                     --LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                     LEFT JOIN tbl_catalogo_detalle d ON a.tipo_persona = d.id_item
                     LEFT JOIN tbl_catalogo_detalle e ON a.id_status = e.id_item
                     INNER JOIN rrhh_persona_apoyo f ON a.id_persona = f.id_persona
                     WHERE a.id_status = 2312
                     UNION
                     SELECT 0 AS id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,a.id_persona,a.tipo_persona,a.fecha_ingreso,
                                    a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,a.id_tipo_servicio,a.id_genero,
                                    a.id_procedencia,a.id_auditoria, '' AS estado_emple, e.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                                    1 estado_persona, '' AS asignacion, '' AS id_empleado, '' AS fecha_toma_posesion,
                                    '' AS fecha_inicio, '' AS fecha_baja, '' AS fecha_efectiva_resicion, '' AS direccion
                                    FROM rrhh_persona a
                                    --LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                                    LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                                    --LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                                    LEFT JOIN tbl_catalogo_detalle d ON a.tipo_persona = d.id_item
                                    LEFT JOIN tbl_catalogo_detalle e ON a.id_status = e.id_item
                                    INNER JOIN rrhh_persona_practicante f ON a.id_persona = f.id_persona
                                    WHERE a.id_status = 9102";

                     createLog(86, 1163, 'rrhh_persona', 'Visualizando personal de apoyo','', '');

    }
    else if($tipo==6){
      $sql = "SELECT emp.id_empleado
                  ,a.primer_nombre
                  ,a.segundo_nombre
                  ,a.tercer_nombre
                  ,a.primer_apellido
                  ,a.segundo_apellido
                  ,a.tercer_apellido
                  ,a.id_persona
                  ,a.tipo_persona
                  ,a.fecha_ingreso
                  ,a.fecha_modificacion
                  ,a.id_status
                  ,a.nisp
                  ,a.nit
                  ,a.afiliacion_igss
                  ,a.correo_electronico
                  ,a.id_estado_civil
                  ,a.id_profesion
                  ,a.observaciones
                  ,a.id_tipo_servicio
                  ,a.id_genero
                  ,a.id_procedencia
                  ,a.id_auditoria
                  ,b.descripcion
                  ,c.fecha_nacimiento
                  ,
                      CASE
                          WHEN NOT hst.id_direccion_funcional IS NULL THEN dirf.descripcion
                          ELSE CASE WHEN NOT cnt.id_direccion_servicio IS NULL THEN dirc.descripcion
                              ELSE CASE WHEN NOT apy.id_direccion_servicio IS NULL THEN dira.descripcion
                                  ELSE 'S/D' END END
                              END AS dir_general
                          ,isnull(dirf.descripcion,isnull(dirsubf.descripcion,'S/D')) AS dir_funcional
                          ,
                              CASE
                                  WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164) AND a.id_status IN (2312,1030)) THEN 1
                                  ELSE 0
                              END AS estado_persona
                          ,e.id_asignacion AS asignacion
                          ,e.id_empleado
                          ,e.fecha_toma_posesion
                          ,
                              CASE
                                  WHEN e.fecha_toma_posesion IS NULL THEN ''
                                  ELSE convert(varchar(50),e.fecha_toma_posesion,105)
                              END AS fecha_inicio
                          ,
                              CASE
                                  WHEN e.fecha_efectiva_resicion IS NULL THEN ''
                                  ELSE convert(varchar(50),e.fecha_efectiva_resicion,105)
                              END AS fecha_baja
                          ,isnull(e.fecha_efectiva_resicion,'') AS fecha_efectiva_resicion
                      FROM rrhh_persona a
                              left JOIN
                              rrhh_empleado emp
                              ON emp.id_persona=a.id_persona
                                  left JOIN
                                  rrhh_persona_complemento c
                                  ON a.id_persona=c.id_persona
                                      left JOIN
                                      tbl_catalogo_detalle b
                                      ON emp.id_status=b.id_item
                                          left JOIN
                                          rrhh_empleado_plaza AS e
                                          ON (e.id_empleado = emp.id_empleado
                                              AND e.id_status = 891)
                                              left OUTER JOIN
                                              dbo.rrhh_persona_apoyo AS apy
                                              ON a.id_persona = apy.id_persona
                                                  left OUTER JOIN
                                                  dbo.rrhh_empleado_contratos AS cnt
                                                  ON emp.id_empleado = cnt.id_empleado
                                                  AND cnt.id_status = 908
                                                      left JOIN
                                                      rrhh_hst_plazas AS hst
                                                      ON (e.id_plaza = hst.id_plaza
                                                          AND hst.flag_ubicacion_actual = 1)
                                                          left JOIN
                                                          rrhh_direcciones AS dirf
                                                          ON hst.id_direccion_funcional = dirf.id_direccion
                                                              left JOIN
                                                              rrhh_direcciones AS dirsubf
                                                              ON hst.id_subsecretaria_funcional = dirsubf.id_direccion
                                                                  left OUTER JOIN
                                                                  dbo.rrhh_direcciones AS dirc
                                                                  ON cnt.id_direccion_servicio = dirc.id_direccion
                                                                      left OUTER JOIN
                                                                      dbo.rrhh_direcciones AS dira
                                                                      ON dira.id_direccion = apy.id_direccion_servicio
                                                              ORDER BY estado_persona DESC,a.id_persona ASC";
    }

    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_contratos_listados($tipo){

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql='';
    if($tipo == 1){
      $sql= "SELECT emp.id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,
                     a.id_persona,a.tipo_persona,a.fecha_ingreso,
                     a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,
                     a.observaciones,a.id_tipo_servicio,a.id_genero,a.id_procedencia,
                     a.id_auditoria,b.descripcion AS estado_emple, f.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                     CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
                     (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164)
                     AND a.id_status IN (2312,1030))
                     THEN 1 ELSE 0 END AS estado_persona,g.id_asignacion AS asignacion,
						         CASE WHEN g.fecha_toma_posesion IS NULL THEN '' ELSE convert(varchar(50),g.fecha_toma_posesion,105) END AS fecha_inicio ,
						         CASE WHEN h.fecha_efectiva_resicion IS NULL THEN ''
                     ELSE convert(varchar(50),h.fecha_efectiva_resicion,105) END AS fecha_baja,i.direccion, cd.archivo, ce.archivor
                     FROM rrhh_persona a
                     LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                     LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                     LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                     LEFT JOIN tbl_catalogo_detalle f ON a.id_status = f.id_item
                     LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_toma_posesion
                       FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion ASC) AS rnk FROM rrhh_empleado_plaza a
                          WHERE   id_status IN (1210,891)
                        ) T
                        WHERE T.rnk = 1
                      ) AS g ON g.id_empleado = emp.id_empleado
                      LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion
                        FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion DESC) AS rnk FROM rrhh_empleado_plaza a
                        ) T
                        WHERE T.rnk = 1
                      ) AS h ON h.id_empleado = emp.id_empleado
                      LEFT JOIN (SELECT  T.id_empleado, T.reng_num, T.fecha_efectiva_resicion, T.direccion
                        FROM
                        (
                          SELECT a.reng_num, a.id_empleado, d.descripcion AS direccion, a.fecha_efectiva_resicion,
            						  ROW_NUMBER() OVER (PARTITION BY a.id_empleado ORDER BY a.reng_num DESC) AS rnk
            						  FROM rrhh_empleado_contratos a
            						  --INNER JOIN rrhh_plaza b ON a.id_plaza = b.id_plaza
									  INNER JOIN rrhh_empleado b ON a.id_empleado = b.id_empleado
            						  INNER JOIN rrhh_direcciones d ON a.id_direccion_servicio = d.id_direccion
            						  WHERE b.id_status = 891

                        ) T
                        WHERE T.rnk = 1
                      ) AS i ON i.id_empleado = emp.id_empleado
                      LEFT JOIN  (SELECT T.archivo, T.reng_num, T.correlativo
                        FROM
                         (
                           SELECT archivo, reng_num, correlativo,  ROW_NUMBER() OVER (PARTITION BY reng_num ORDER BY correlativo DESC) AS rnk FROM rrhh_empleado_contrato_detalle a
                           WHERE   tipo_documento = 1
                         ) T
                         WHERE T.rnk = 1
                       ) AS cd ON cd.reng_num = i.reng_num
                       LEFT JOIN  (SELECT T.archivo AS archivor, T.reng_num, T.correlativo
                         FROM
                          (
                            SELECT archivo, reng_num, correlativo,  ROW_NUMBER() OVER (PARTITION BY reng_num ORDER BY correlativo DESC) AS rnk FROM rrhh_empleado_contrato_detalle a
                            WHERE   tipo_documento = 2
                          ) T
                          WHERE T.rnk = 1
                        ) AS ce ON ce.reng_num = i.reng_num
                      WHERE emp.id_status IN (891) AND emp.id_contrato = 1075
                      ORDER BY estado_persona DESC,a.id_persona ASC
                      ";

    }else if($tipo == 2){//empleados de baja
      $sql= "SELECT emp.id_empleado,a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,
                     a.id_persona,a.tipo_persona,a.fecha_ingreso,
                     a.fecha_modificacion,a.id_status,a.nisp,a.nit,a.afiliacion_igss,a.correo_electronico,a.id_estado_civil,a.id_profesion,
                     a.observaciones,a.id_tipo_servicio,a.id_genero,a.id_procedencia,
                     a.id_auditoria,b.descripcion AS estado_emple, f.descripcion AS estado_per,c.fecha_nacimiento,'' as dir_general,'' AS dir_funcional,
                     CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
                     (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164)
                     AND a.id_status IN (2312,1030))
                     THEN 1 ELSE 0 END AS estado_persona,g.id_asignacion AS asignacion,
						         CASE WHEN g.fecha_toma_posesion IS NULL THEN '' ELSE convert(varchar(50),g.fecha_toma_posesion,105) END AS fecha_inicio ,
						         CASE WHEN h.fecha_efectiva_resicion IS NULL THEN ''
                     ELSE convert(varchar(50),h.fecha_efectiva_resicion,105) END AS fecha_baja, '' AS direccion, '' AS archivo
                     FROM rrhh_persona a
                     LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
                     LEFT JOIN rrhh_persona_complemento c ON a.id_persona=c.id_persona
                     LEFT JOIN tbl_catalogo_detalle b ON emp.id_status=b.id_item
                     LEFT JOIN tbl_catalogo_detalle f ON a.id_status = f.id_item
                     LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_toma_posesion
                       FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion ASC) AS rnk FROM rrhh_empleado_plaza a
                        ) T
                        WHERE T.rnk = 1
                      ) AS g ON g.id_empleado = emp.id_empleado
                      LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion
                        FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_empleado ORDER BY id_asignacion DESC) AS rnk FROM rrhh_empleado_plaza a
                        ) T
                        WHERE T.rnk = 1
                      ) AS h ON h.id_empleado = emp.id_empleado
                      WHERE emp.id_status IN (7,3727,3733,1034,1026,3805,3734,1032,1012,892,3733,3735,893,6646,5610,1033) AND emp.id_contrato = 1075
                      ORDER BY estado_persona DESC,a.id_persona ASC
                      ";
    }
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleados_con_accesos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,b.conteo,
            c.descripcion,
            CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
            (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164) AND
            a.id_status IN (2312,1030)) THEN 1 ELSE 0 END AS estado_persona

            FROM rrhh_persona a
            LEFT JOIN (SELECT id_persona, COUNT(*) as conteo
                       FROM tbl_accesos_usuarios
                       GROUP BY id_persona) AS b ON b.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
            ORDER BY a.id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }
  function get_empleados_busqueda()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre, a.tercer_nombre, a.primer_apellido, a.segundo_apellido,a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,b.conteo,
            c.descripcion
            FROM rrhh_persona a
            LEFT JOIN (SELECT id_persona, COUNT(*) as conteo
                       FROM tbl_accesos_usuarios
                       GROUP BY id_persona) AS b ON b.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            ORDER BY a.id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }
  function get_empleado_info($id_persona){
    {
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT
              a.id_persona,a.tipo_persona,
              a.id_tipo_aspirante,n.id_empleado, a.primer_nombre, a.segundo_nombre, a.tercer_nombre, a.primer_apellido, a.segundo_apellido, a.tercer_apellido
              FROM rrhh_persona a
              LEFT JOIN rrhh_empleado n ON n.id_persona=a.id_persona
              WHERE a.id_persona=?
              ORDER BY a.id_persona ASC";
      $p = $pdo->prepare($sql);
      $p->execute(array($id_persona, $id_persona));
      $empleado = $p->fetch();
      Database::disconnect_sqlsrv();
      return $empleado;
    }
  }
  function get_empleado_by_id($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT LOWER( a.primer_nombre) AS primer_nombre,  LOWER(a.segundo_nombre) AS segundo_nombre,  LOWER(a.tercer_nombre) AS tercer_nombre,  LOWER(a.primer_apellido) AS primer_apellido,  LOWER(a.segundo_apellido) AS segundo_apellido, LOWER(a.tercer_apellido) AS tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,--b.conteo,
            c.descripcion,
            --d.id_fotografia,d.id_tipo_fotografia,d.fotografia_principal,d.fotografia,
            --d.descripcion,d.id_auditoria,--e.profesion,
            f.descripcion AS genero,h.descripcion AS procedencia,
            CASE WHEN  f.descripcion='FEMENINO'  AND a.id_estado_civil IN (1,2,3,4,6,5635) THEN Left(g.descripcion, Len(g.descripcion) - 1) + 'A'  ELSE g.descripcion  END
            AS estado_civil, i.descripcion AS tipo_personal,
            k.descripcion AS religion, j.fecha_nacimiento,
            l.nombre AS municipio, m.nombre AS departamento,
            o.descripcion AS tipo_contrato,n.observaciones as emp_observaciones,
            n.id_status AS status_empleado, n.id_empleado, ISNULL(p.nro_registro, ' ') AS cui,
            dbo.fn_rrhh_persona_edad(q.fecha_nacimiento, GETDATE()) AS edad,
            j.id_muni_nacimiento,j.id_depto_nacimiento, j.id_aldea_nacimiento,j.id_tipo_curso,j.id_promocion,j.id_religion,
            a.id_tipo_aspirante

            FROM rrhh_persona a
            /*LEFT JOIN (SELECT id_persona, COUNT(*) as conteo
                       FROM tbl_accesos_usuarios
                       GROUP BY id_persona) AS b ON b.id_persona=a.id_persona*/
            LEFT JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            --LEFT JOIN rrhh_persona_fotografia d ON d.id_persona=a.id_persona
            /*LEFT JOIN (SELECT TOP 1 b.descripcion AS profesion, b.id_item,
                       a.id_titulo_obtenido, a.fecha_titulo, a.nro_colegiado,
                       a.id_persona
                       FROM rrhh_persona_escolaridad a
                       INNER JOIN tbl_catalogo_detalle b ON a.id_titulo_obtenido=b.id_item
                       WHERE a.id_persona=?
                       ORDER BY a.id_escolaridad DESC
                     ) AS e ON e.id_persona=a.id_persona*/
            LEFT JOIN tbl_catalogo_detalle f ON a.id_genero=f.id_item
            LEFT JOIN tbl_catalogo_detalle g ON a.id_estado_civil=g.id_item
            LEFT JOIN tbl_catalogo_detalle h ON a.id_procedencia=h.id_item
            LEFT JOIN tbl_catalogo_detalle i ON a.id_tipo_servicio=i.id_item
            LEFT JOIN rrhh_persona_complemento j ON j.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle k ON j.id_religion=k.id_item
            LEFT JOIN tbl_municipio l ON j.id_muni_nacimiento=l.id_municipio
            LEFT JOIN tbl_departamento m ON j.id_depto_nacimiento=m.id_departamento
            LEFT JOIN rrhh_empleado n ON n.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle o ON n.id_contrato=o.id_item
            LEFT JOIN rrhh_persona_documentos AS p ON a.id_persona=p.id_persona AND p.id_tipo_identificacion = 1238
            LEFT JOIN rrhh_persona_complemento AS q ON a.id_persona=q.id_persona
            WHERE a.id_persona=?
            ORDER BY a.id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona, $id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }
  static function get_empleado_fotografia($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona,id_fotografia,id_tipo_fotografia,fotografia_principal,fotografia,
                   descripcion,id_auditoria
            FROM rrhh_persona_fotografia
            WHERE id_persona=? AND fotografia_principal = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,1));
    $foto = $p->fetch();
    Database::disconnect_sqlsrv();
    return $foto;
  }

  function get_direcciones_by_empleado($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM xxx_rrhh_ficha_persona_direccion
            WHERE id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $direcciones = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $direcciones;
  }

  function get_direccion_by_id($id_direccion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM xxx_rrhh_ficha_persona_direccion
            WHERE id_direccion=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_direccion));
    $direccione = $p->fetch();
    Database::disconnect_sqlsrv();
    return $direccione;
  }

  function get_documentos_by_empleado($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM xxx_rrhh_ficha_persona_documentos
            WHERE id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $direcciones = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $direcciones;
  }

  function get_documento_by_id($id_docto)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona, id_documento, id_tipo_identificacion,
            id_tipo_documento, id_orden_cedula, nro_registro,
            fecha_vencimiento, id_departamento, id_municipio, id_aldea
            FROM rrhh_persona_documentos
            WHERE id_documento=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_docto));
    $documento = $p->fetch();
    Database::disconnect_sqlsrv();
    return $documento;
  }

  function get_telefonos_by_empleado($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona,
              id_telefono,
              id_tipo_referencia,
              id_tipo_telefono,
    observaciones,
              nombre_tipo_referencia,
              nombre_tipo_telefono,
              ISNULL(nro_telefono,0) AS nro_telefono,
              Telefono_Privado,
              Telefono_Activo,
              Telefono_Principal,
              Telefono_Privado,
              Telefono_Activo,
Telefono_Principal
 FROM xxx_rrhh_ficha_persona_telefono
            WHERE id_persona=? --AND Telefono_Activo = 1";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $telefonos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $telefonos;
  }

  function get_telefono_by_id($id_telefono)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona, id_telefono, flag_privado, flag_activo, flag_principal, tipo, id_tipo_telefono, nro_telefono, observaciones FROM rrhh_persona_telefonos
            WHERE id_telefono=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_telefono));
    $telefonos = $p->fetch();
    Database::disconnect_sqlsrv();
    return $telefonos;
  }

  function get_empleado_by_id_ficha($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT *
            FROM xxx_rrhh_Ficha
            WHERE id_persona=?
            ORDER BY id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona, $id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }
  static function get_empleados_ficha()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT *
            FROM xxx_rrhh_Ficha
            WHERE estado=?
            ORDER BY primer_apellido ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array(1));
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleado_estado_by_id($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,
            b.descripcion,
            CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
            (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164) AND
            a.id_status IN (2312,1030)) THEN 1 ELSE 0 END AS estado_persona
            FROM rrhh_persona a
            INNER JOIN tbl_catalogo_detalle b ON a.id_status=b.id_item
            LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
            WHERE a.id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }

  static function get_direcciones_saas_by_id($id)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT id_direccion, id_nivel, id_superior, id_tipo, descripcion, descripcion_corta
            FROM rrhh_direcciones
            WHERE id_direccion=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id));
    $direccion = $p->fetch();
    Database::disconnect_sqlsrv();
    return $direccion;
  }

  static function get_plazas_estado()
  {
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
                          c.id_contrato, A.id_sueldo_plaza, Ps.monto_sueldo_plaza, Pv.monto_sueldo_base, B.fecha_toma_posesion
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
                                    WHERE (A.flag_ubicacion_actual = 1)
                                    ORDER BY Renglon";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $plazas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $plazas;
  }

  static function get_empleados_por_direccion_funcional()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT a.id_persona, a.nombre, a.p_funcional, a.p_nominal, a.fecha_ingreso,a.id_dirf,
            CASE
            WHEN a.id_tipo=2 THEN '031' WHEN a.id_tipo=3 THEN '029' ELSE '011' END AS renglon, a.estado,
            a.id_tipo,a.dir_nominal,a.dir_funcional,
            CASE
            WHEN a.id_tipo=2 THEN a.dir_funcional WHEN a.id_tipo=4 THEN 'APOYO' ELSE a.dir_nominal END AS direccion,
            sd.SUELDO, f.fecha_toma_posesion, f.fecha_efectiva_resicion,f.id_status
                        FROM xxx_rrhh_Ficha a
            LEFT JOIN rrhh_plazas_sueldo b ON b.id_plaza=a.id_plaza

            LEFT JOIN (SELECT a.id_persona,
            SUM(c.monto_p) AS SUELDO
                        FROM xxx_rrhh_Ficha a
            LEFT JOIN rrhh_plazas_sueldo b ON b.id_plaza=a.id_plaza
            INNER JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo
            INNER JOIN rrhh_plazas_sueldo_conceptos d ON c.id_concepto = d.id_concepto

                        WHERE  actual =1 AND d.aplica_plaza = 1

            group by a.id_persona) AS sd ON sd.id_persona=a.id_persona

            LEFT JOIN rrhh_persona_fotografia d ON d.id_persona=a.id_persona
      LEFT JOIN rrhh_empleado e ON a.id_persona=e.id_persona
      LEFT JOIN rrhh_empleado_plaza f ON f.id_empleado=e.id_empleado

            WHERE a.estado=1 AND f.id_status=891

            union

            select distinct f.id_persona, f.nombre,
  case when f.id_tipo=2 then 'TALLERISTA' else 'ASESOR' end p_funcional,
  case when f.id_tipo=2 then 'TALLERISTA' else 'ASESOR' end p_nominal,
  e.fecha_inicio fecha_ingreso,f.id_dirf,
  case when f.id_tipo=2 then '031' else case when f.id_tipo=3 then '029' else 'PNC' end end renglon,
  f.estado, f.id_tipo, f.dir_nominal, f.dir_nominal dir_funcional,
  CASE WHEN f.id_tipo=2 THEN f.dir_funcional WHEN f.id_tipo=4 THEN 'APOYO' ELSE f.dir_nominal END AS direccion,
  case when f.id_tipo=2 then s.R031 else s.R029 end sueldo,
  e.fecha_inicio fecha_toma, c.fecha_finalizacion fecha_baja, 0
  from xxx_rrhh_Ficha f
    left join rrhh_empleado_contratos c on f.id_empleado=c.id_empleado and c.id_status=908
    left outer join (select id_empleado, min(fecha_inicio) fecha_inicio from rrhh_empleado_contratos group by id_empleado) e
      on f.id_empleado = e.id_empleado
    left outer join (select id_empleado, monto_contrato*30 R031, monto_mensual R029 from rrhh_empleado_contratos where id_status=908 ) s
      on f.id_empleado = s.id_empleado
  where f.estado=1 and f.id_tipo in(2,3)
";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  static function get_plaza_historial($partida)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_plaza, a.partida_presupuestaria, b.fecha_toma_posesion, b.fecha_efectiva_resicion, d.primer_nombre, d.segundo_nombre, d.tercer_nombre, d.primer_apellido, d.segundo_apellido, d.tercer_apellido
            FROM rrhh_plaza a
            INNER JOIN rrhh_empleado_plaza b ON b.id_plaza=a.id_plaza
            INNER JOIN rrhh_empleado c ON b.id_empleado=c.id_empleado
            INNER JOIN rrhh_persona d ON c.id_persona=d.id_persona
            WHERE a.id_plaza=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($partida));
    $plaza = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $plaza;
  }



  function save_employee_income($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_control_ingreso(id_persona, id_usuario, fecha, id_punto, ip_address) VALUES(?,?,GETDATE(),1,?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($id_persona, $_SESSION["id_persona"], $_SERVER['REMOTE_ADDR']))) {
      $response = array(
        "msg" => "ok",
        "status" => 200
      );
    } else {
      $response = array(
        "msg" => "error",
        "status" => 400
      );
    }
    Database::disconnect_sqlsrv();
  }

  function get_empleado_puesto_actual_bk($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_persona, b.id_empleado, a.primer_nombre,a.segundo_nombre,a.tercer_nombre,a.primer_apellido,a.segundo_apellido,a.tercer_apellido,
          d.id_nivel_funcional, d.id_secretaria_funcional, d.id_subsecretaria_funcional, d.id_direccion_funcional, d.id_depto_funcional, d.id_seccion_funcional,
          e.descripcion AS nivelf, f.descripcion AS secretariaf,g.descripcion AS subsecretariaf,h.descripcion AS direccionf, i.descripcion AS subdireccionf,
          j.descripcion AS departamentof,

          d.id_nivel_presupuestario, d.id_secretaria_presupuestario, d.id_subsecretaria_presupuestaria, d.id_direccion_presupuestaria, d.id_depto_presupuestario, d.id_seccion_presupuestario,
          r.descripcion AS niveln, l.descripcion AS secretarian,m.descripcion AS subsecretarian,n.descripcion AS direccionn, o.descripcion AS subdireccionn,
          p.descripcion AS departamenton,

          s.descripcion AS puestof,
          u.descripcion AS pueston,
          b.id_status AS emp_estado

          FROM rrhh_persona a
          INNER JOIN rrhh_empleado b ON b.id_persona=a.id_persona
          INNER JOIN rrhh_empleado_plaza c ON b.id_empleado=c.id_empleado
          INNER JOIN rrhh_hst_plazas d ON d.id_plaza=c.id_plaza

          LEFT JOIN rrhh_jerarquia_niveles e ON d.id_nivel_funcional=e.id_jerarquia

          LEFT JOIN rrhh_direcciones f ON d.id_secretaria_funcional=f.id_direccion
          LEFT JOIN rrhh_direcciones g ON d.id_subsecretaria_funcional=g.id_direccion
          LEFT JOIN rrhh_direcciones h ON d.id_direccion_funcional=h.id_direccion
          LEFT JOIN rrhh_subdirecciones i ON d.id_subdireccion_funcional=i.id_subdireccion
          LEFT JOIN rrhh_departamentos j ON d.id_depto_funcional=j.id_departamento
          LEFT JOIN rrhh_secciones k ON d.id_seccion_funcional=k.id_seccion
          LEFT JOIN tbl_catalogo_detalle s ON d.id_puesto=s.id_item


          LEFT JOIN rrhh_direcciones l ON d.id_secretaria_presupuestario=l.id_direccion
          LEFT JOIN rrhh_direcciones m ON d.id_subsecretaria_presupuestaria=m.id_direccion
          LEFT JOIN rrhh_direcciones n ON d.id_direccion_presupuestaria=n.id_direccion
          LEFT JOIN rrhh_subdirecciones o ON d.id_subdireccion_presupuestaria=o.id_subdireccion
          LEFT JOIN rrhh_departamentos p ON d.id_depto_presupuestario=p.id_departamento
          LEFT JOIN rrhh_secciones q ON d.id_seccion_presupuestario=q.id_seccion
          LEFT JOIN rrhh_jerarquia_niveles r ON d.id_nivel_presupuestario=r.id_jerarquia
          INNER JOIN rrhh_plaza t ON d.id_plaza=t.id_plaza
          INNER JOIN rrhh_plazas_puestos u ON t.id_puesto=u.id_puesto

          WHERE a.id_persona=? AND c.id_status=? AND d.flag_ubicacion_actual=? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona, 891, 1));
    $puesto = $p->fetch();
    Database::disconnect_sqlsrv();
    return $puesto;
  }

  function get_empleado_puesto_actual($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT v.reng_num,  v.id_asignacion, b.id_persona, b.id_empleado,
          v.nivelf, v.secretariaf,v.subsecretariaf,v.direccionf,v.subdireccionf,
          v.departamentof,v.direccionfficha,
          w.id_nivel_n, w.id_secretaria_n, w.id_subsecretaria_n, w.id_direccion_n, w.id_departamento_n, w.id_seccion_n,
          w.niveln, w.secretarian,w.subsecretarian,w.direccionn,w.subdireccionn,
          w.departamenton,
          v.puestof,
          w.pueston,
          b.id_status AS emp_estado,
		  w.partida_presupuestaria,
		  w.fecha_toma_posesion,
		  w.fecha_acuerdo, w.fecha_efectiva_resicion, w.nro_acuerdo, w.direccionnficha

          FROM rrhh_empleado b
          --INNER JOIN rrhh_empleado_plaza c ON c.id_empleado=b.id_empleado




		  LEFT JOIN (SELECT  T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion,
		  T.nivelf, T.secretariaf,T.subsecretariaf,T.direccionf, T.subdireccionf,
          T.departamentof,T.puestof, T.rnk,T.reng_num, T.direccionfficha
                        FROM
                        (
                          SELECT  c.id_plaza, a.reng_num, c.id_empleado, c.id_asignacion,
            						  c.fecha_efectiva_resicion,
									  e.descripcion AS nivelf, f.descripcion AS secretariaf,g.descripcion AS subsecretariaf,h.descripcion AS direccionf, i.descripcion AS subdireccionf,
          j.descripcion AS departamentof,s.descripcion AS puestof,ISNULL(h.descripcion,g.descripcion) AS direccionfficha,

            						  ROW_NUMBER() OVER (PARTITION BY c.id_empleado ORDER BY a.id_asignacion DESC, a.reng_num DESC) AS rnk
            						  FROM rrhh_asignacion_puesto_historial_detalle a
            						  --INNER JOIN rrhh_plaza b ON a.id_plaza = b.id_plaza
                                      INNER JOIN rrhh_empleado_plaza c ON a.id_asignacion = c.id_asignacion
            						   LEFT JOIN rrhh_jerarquia_niveles e ON a.nivel_f=e.id_jerarquia

									  LEFT JOIN rrhh_direcciones f ON a.secretaria_f=f.id_direccion
									  LEFT JOIN rrhh_direcciones g ON a.subsecretaria_f=g.id_direccion
									  LEFT JOIN rrhh_direcciones h ON a.direccion_f=h.id_direccion
									  LEFT JOIN rrhh_subdirecciones i ON a.subdireccion_f=i.id_subdireccion
									  LEFT JOIN rrhh_departamentos j ON a.departamento_f=j.id_departamento
									  LEFT JOIN rrhh_secciones k ON a.seccion_f=k.id_seccion
									  LEFT JOIN tbl_catalogo_detalle s ON a.puesto_f=s.id_item






                        ) T
                        WHERE T.rnk = 1
                      ) AS v ON v.id_empleado = b.id_empleado

					  LEFT JOIN (SELECT  T.id_plaza, T.id_nivel_n, T.id_secretaria_n, T.id_subsecretaria_n, T.id_direccion_n, T.id_departamento_n, T.id_seccion_n,
          T.niveln, T.secretarian,T.subsecretarian,T.direccionn,T.subdireccionn,
          T.departamenton,T.pueston,T.id_empleado,T.id_asignacion, T.rnk, T.partida_presupuestaria, T.fecha_toma_posesion,
		  T.fecha_acuerdo, T.fecha_efectiva_resicion, T.nro_acuerdo, T.direccionnficha
                        FROM
                        (
                          SELECT  ROW_NUMBER() OVER (PARTITION BY v.id_asignacion ORDER BY v.id_asignacion DESC) AS rnk,
 u.descripcion AS pueston,
 v.id_empleado, v.id_asignacion, t.id_plaza, d.id_nivel_n, d.id_secretaria_n, d.id_subsecretaria_n, d.id_direccion_n, d.id_departamento_n, d.id_seccion_n,
          r.descripcion AS niveln, l.descripcion AS secretarian,m.descripcion AS subsecretarian,n.descripcion AS direccionn, o.descripcion AS subdireccionn,
          p.descripcion AS departamenton, t.partida_presupuestaria, v.fecha_toma_posesion, v.fecha_acuerdo, v.fecha_efectiva_resicion, v.nro_acuerdo,
          ISNULL(n.descripcion,ISNULL(m.descripcion,l.descripcion)) AS direccionnficha
            						  FROM
									   rrhh_empleado_plaza v
									  INNER JOIN rrhh_plaza_detalle_ubicacion d ON v.id_plaza = d.id_plaza
									  INNER JOIN rrhh_plaza t ON d.id_plaza=t.id_plaza
          INNER JOIN rrhh_plazas_puestos u ON t.id_puesto=u.id_puesto


          LEFT JOIN rrhh_direcciones l ON d.id_secretaria_n=l.id_direccion
          LEFT JOIN rrhh_direcciones m ON d.id_subsecretaria_n=m.id_direccion
          LEFT JOIN rrhh_direcciones n ON d.id_direccion_n=n.id_direccion
          LEFT JOIN rrhh_subdirecciones o ON d.id_subdireccion_n=o.id_subdireccion
          LEFT JOIN rrhh_departamentos p ON d.id_departamento_n=p.id_departamento
          LEFT JOIN rrhh_secciones q ON d.id_seccion_n=q.id_seccion
          LEFT JOIN rrhh_jerarquia_niveles r ON d.id_nivel_n=r.id_jerarquia
		  --where v.id_empleado = 2503
                        ) T
                        WHERE T.rnk = 1
                      ) AS w ON w.id_asignacion =v.id_asignacion

          WHERE b.id_persona=?
		  ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $puesto = $p->fetch();
    Database::disconnect_sqlsrv();
    return $puesto;
  }

  function get_rrhh_direcciones($nivel, $tipo, $superior, $opcion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = '';
    if ($opcion == 1) {
      $sql .= "SELECT id_direccion,id_nivel,id_superior,id_tipo,descripcion
              FROM rrhh_direcciones WHERE id_nivel=? AND id_tipo=? ";
    } else if ($opcion == 2) {
      $sql .= "SELECT id_subdireccion AS id_direccion, descripcion
              FROM rrhh_subdirecciones WHERE id_direccion=? AND id_tipo=? ";
    } else if ($opcion == 3) {
      $sql .= "SELECT id_departamento AS id_direccion, descripcion
              FROM rrhh_departamentos WHERE id_subdireccion=? AND id_tipo=? ";
    } else if ($opcion == 4) {
      $sql .= "SELECT id_seccion AS id_direccion, descripcion
              FROM rrhh_secciones WHERE id_departamento=? AND id_tipo=? ";
    } else if ($opcion == 5) {
      $sql .= "SELECT id_jerarquia AS id_direccion, descripcion
              FROM rrhh_jerarquia_niveles WHERE id_nivel>? AND id_tipo=? ";
    }

    if ($superior != 0) {
      $sql .= "AND id_superior=$superior";
    }
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($nivel, $tipo))) {
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

  function get_items($id_catalogo, $tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_catalogo, id_item, id_status, descripcion FROM tbl_catalogo_detalle WHERE id_catalogo=? AND id_status=? ";
    if ($tipo == 1) {
      $sql .= 'AND id_item NOT IN(891,1012,5610)';
    } else
    if ($tipo == 2) {
      $sql .= 'AND id_item IN (892,893,1012,1032,1034,1083,3727,3733,3734,3735,3805)';
    }
    else if($tipo==3){
      $sql .= "AND id_item IN (1033,1160,1161,1210,5509,6646,1034,5610)";
    }
    else if($tipo==4){//remoción 029
      $sql .= "AND id_item IN (8271,8272,8273,8274,8275,8276,8277,8278,8279,8280,8281)";
    }
    else if($tipo==5){//remoción 031
      $sql .= "AND id_item IN (8265,8267,8268,8269,8270)";
    }
    //$stmt = $pdo->prepare($sql);
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($id_catalogo, 1))) {
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

  function get_catalogo($tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_catalogo, descripcion FROM tbl_catalogo ";
    if($tipo == 1){ //EMPLEADO NUEVO
      $sql.= "WHERE id_catalogo IN (4,30,11,73,79)";
    }
    if($tipo == 2){ // PUESTOS
      $sql.= "WHERE id_catalogo IN (31)";
    }
    if($tipo == 3){
      $sql.= "WHERE id_catalogo IN (4,30,31,11,73,79)";
    }
    if($tipo == 4){ // centros de estudio
      $sql.= "WHERE id_catalogo IN (11,12,13,14,16,32,80,134)";
    }
    if($tipo == 5){ // puestos
      $sql.= "WHERE id_catalogo IN (31,97,102,103)";
    }

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

  static function guardar_historial_asignacion_puesto($id_asignacion,$nivel,$secretaria_f,$subsecretaria_f,$direccion_f,$subidireccion_f,$departamento_f,$seccion_f,$puesto_f,$acuerdo,$observaciones,$fecha_inicio,$fecha_fin,$fecha_toma)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT TOP 1 reng_num
            FROM rrhh_asignacion_puesto_historial_detalle

            ORDER BY reng_num DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_asignacion));
    $reng = $p->fetch();

    $reng_num=1;
    if(!empty($reng['reng_num'])){
      $reng_num=$reng['reng_num']+1;
    }

    $sql2 = "INSERT INTO rrhh_asignacion_puesto_historial_detalle (id_asignacion,reng_num,nivel_f,secretaria_f,subsecretaria_f,direccion_f,subdireccion_f,departamento_f,seccion_f,puesto_f,acuerdo,observaciones,fecha_inicio,fecha_fin,fecha_toma_posesion,operado_por,fecha_asignacion,status)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $p2 = $pdo->prepare($sql2);
    $p2->execute(array($id_asignacion,$reng_num,$nivel,$secretaria_f,$subsecretaria_f,$direccion_f,$subidireccion_f,$departamento_f,$seccion_f,$puesto_f,$acuerdo,$observaciones,$fecha_inicio,$fecha_fin,$fecha_toma,$_SESSION['id_persona'],date('Y-m-d H:i:s'),1));

    Database::disconnect_sqlsrv();

  }
  static function get_ubicaciones_por_asignacion($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT b.id_asignacion,c.id_plaza,c.cod_plaza, d.fecha_inicio, d.fecha_toma_posesion, d.fecha_fin, d.status, d.acuerdo, e.descripcion AS nf,
          f.descripcion AS s, g.descripcion AS ss, h.descripcion AS dir, i.descripcion AS sdir, j.descripcion AS dep, k.descripcion AS sec, l.descripcion AS puesto, d.reng_num
          FROM rrhh_empleado a
          INNER JOIN rrhh_empleado_plaza b ON a.id_empleado=b.id_empleado
          INNER JOIN rrhh_plaza c ON b.id_plaza=c.id_plaza
          INNER JOIN rrhh_asignacion_puesto_historial_detalle d ON d.id_asignacion=b.id_asignacion

          LEFT JOIN rrhh_jerarquia_niveles e ON d.nivel_f=e.id_jerarquia

          LEFT JOIN rrhh_direcciones f ON d.secretaria_f=f.id_direccion
          LEFT JOIN rrhh_direcciones g ON d.subsecretaria_f=g.id_direccion
          LEFT JOIN rrhh_direcciones h ON d.direccion_f=h.id_direccion
          LEFT JOIN rrhh_subdirecciones i ON d.subdireccion_f=i.id_subdireccion
          LEFT JOIN rrhh_departamentos j ON d.departamento_f=j.id_departamento
          LEFT JOIN rrhh_secciones k ON d.seccion_f=k.id_seccion
          LEFT JOIN tbl_catalogo_detalle l ON d.puesto_f=l.id_item
          WHERE a.id_persona=? AND d.status IN (?,?)
          ORDER BY b.id_asignacion DESC, d.reng_num DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona,1,2));
    $response=$stmt->fetchAll();

    Database::disconnect_sqlsrv();
    return $response;
  }
  static function get_ubicacion_by_id($id_asignacion,$reng_num)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT b.id_asignacion,c.id_plaza,c.cod_plaza, d.fecha_inicio, d.fecha_toma_posesion,
          d.fecha_fin, d.status, d.acuerdo, d.nivel_f,d.secretaria_f,d.subsecretaria_f,d.direccion_f,
          d.subdireccion_f,d.departamento_f,d.seccion_f,d.puesto_f,d.reng_num, d.observaciones
          FROM rrhh_empleado_plaza b
          INNER JOIN rrhh_plaza c ON b.id_plaza=c.id_plaza
          INNER JOIN rrhh_asignacion_puesto_historial_detalle d ON d.id_asignacion=b.id_asignacion
          WHERE d.id_asignacion=? AND d.reng_num=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_asignacion,$reng_num));
    $response=$stmt->fetch();

    Database::disconnect_sqlsrv();
    return $response;
  }
  static function get_tipo_persona($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT tipo_persona, id_status FROM rrhh_persona WHERE id_persona=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response=$stmt->fetch();

    Database::disconnect_sqlsrv();
    return $response;
  }
  static function get_tipo_contrato_by_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_empleado, id_contrato, id_status FROM rrhh_empleado WHERE id_persona=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response=$stmt->fetch();

    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_contrato_actual_by_persona($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 b.id_persona, b.id_status AS emp_estado, a.id_empleado,a.reng_num,a.nro_contrato,a.tipo_contrato,a.fecha_contrato,a.fecha_inicio,a.fecha_finalizacion
                  ,a.nro_acuerdo_aprobacion,a.fecha_acuerdo_aprobacion,a.nro_acuerdo_resicion,a.fecha_acuerdo_resicion,a.id_status,
                  a.fecha_efectiva_resicion,a.monto_contrato,a.monto_mensual,a.id_puesto_servicio,a.id_nivel_servicio,
                  a.id_secretaria_servicio,a.id_subsecretaria_servicio,a.id_direccion_servicio,a.id_subdireccion_servicio,
                  a.id_depto_servicio,a.id_seccion_servicio,a.id_jefe_inmediato,a.id_categoria,a.id_auditoria,
                  e.descripcion AS nivelf, f.descripcion AS secretariaf,g.descripcion AS subsecretariaf,h.descripcion AS direccionf,
                  i.descripcion AS subdireccionf,
                  j.descripcion AS departamentof,
                  s.descripcion AS puestof, a.id_puesto_funcional, t.descripcion AS puestofunc,
                  a.id_tipo_servicio,
                  CASE
        				  WHEN a.id_tipo_servicio = 1 THEN 'SERVICIOS TECNICOS'
        				  WHEN a.id_tipo_servicio = 2 THEN 'SERVICIOS PROFESIONALES'
        				  ELSE 'NO SE HA SELECCIONADO' END AS tipo_servicio
            FROM rrhh_empleado_contratos a
            INNER JOIN rrhh_empleado b ON a.id_empleado = b.id_empleado
            LEFT JOIN rrhh_jerarquia_niveles e ON a.id_nivel_servicio=e.id_jerarquia

            LEFT JOIN rrhh_direcciones f ON a.id_secretaria_servicio=f.id_direccion
            LEFT JOIN rrhh_direcciones g ON a.id_subsecretaria_servicio=g.id_direccion
            LEFT JOIN rrhh_direcciones h ON a.id_direccion_servicio=h.id_direccion
            LEFT JOIN rrhh_subdirecciones i ON a.id_subdireccion_servicio=i.id_subdireccion
            LEFT JOIN rrhh_departamentos j ON a.id_depto_servicio=j.id_departamento
            LEFT JOIN rrhh_secciones k ON a.id_seccion_servicio=k.id_seccion
            LEFT JOIN tbl_catalogo_detalle s ON A.id_puesto_servicio=s.id_item
            LEFT JOIN tbl_catalogo_detalle t ON a.id_puesto_funcional = t.id_item


            WHERE b.id_persona = ?
            ORDER BY a.reng_num DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response=$stmt->fetch();

    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_familia_by_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT isnull(par.descripcion,'') parentesco,
            RTRIM(LTRIM(ISNULL(ref.primer_nombre,''))) +
            CASE WHEN LEN(RTRIM(LTRIM(ISNULL(ref.segundo_nombre, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(ref.segundo_nombre, ''))) end +
            CASE WHEN LEN(RTRIM(LTRIM(ISNULL(ref.primer_apellido, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(ref.primer_apellido, ''))) end +
            CASE WHEN LEN(RTRIM(LTRIM(ISNULL(ref.segundo_apellido, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(ref.segundo_apellido, ''))) end nombre,
            isnull(rtrim(ltrim(dbo.fn_rrhh_persona_edad(fecha_nacimiento, GETDATE()))),'') + ' AÑOS' edad,
            isnull(ref.primer_nombre,'') primer_nombre,
            isnull(ref.segundo_nombre,'') segundo_nombre,
            isnull(ref.primer_apellido,'') primer_apellido,
            isnull(ref.segundo_apellido,'') segundo_apellido,
            ref.id_referencia, ref.tipo_referencia, ref.id_parentesco, isnull(ref.id_genero,000) id_genero, ref.fecha_nacimiento, ref.flag_fallecido,
            ref.empresa_trabaja,ref.empresa_direccion, ref.empresa_telefono, ref.id_ocupacion,ref.flag_fallecido
            from rrhh_persona_referencias ref
            left outer join tbl_catalogo_detalle par on par.id_item=ref.id_parentesco
            where id_persona = ? --and flag_fallecido=?
          ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response=$stmt->fetchAll();

    Database::disconnect_sqlsrv();
    return $response;


  }

static function get_nivel_academico($id_persona){
  //nivel academico
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT E.id_escolaridad,a.descripcion nivel, c.descripcion titulo,
        isnull(ltrim(RTRIM(e.nro_colegiado)),' ') nro_colegiado, ISNULL(e.fec_venc_colegiado,'') fec_venc, e.id_escolaridad id, a.id_ref_catalogo id_ref,
        ISNULL(e.fecha_titulo,''),
        E.id_persona,E.flag_terminado,E.id_grado_academico,E.ano_grado_academico,
        E.id_establecimiento,E.id_titulo_obtenido,E.fecha_titulo,E.nro_colegiado,E.fec_venc_colegiado,E.observaciones,D.descripcion AS establecimiento
        FROM rrhh_persona_escolaridad E
        LEFT OUTER JOIN tbl_catalogo_detalle C ON E.id_titulo_obtenido = C.id_item
        LEFT OUTER JOIN tbl_catalogo_detalle A ON E.id_grado_academico = a.id_item
        LEFT JOIN tbl_catalogo_detalle D ON E.id_establecimiento = D.id_item
        WHERE id_persona = ?
        ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_persona));
  $response=$stmt->fetchAll();

  Database::disconnect_sqlsrv();
  return $response;

}

static function get_nivel_academico_by_id($id_escolaridad){
  //nivel academico
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT E.id_escolaridad,a.descripcion nivel, c.descripcion titulo,
        isnull(ltrim(RTRIM(e.nro_colegiado)),' ') nro_colegiado, ISNULL(e.fec_venc_colegiado,'') fec_venc, e.id_escolaridad id, a.id_ref_catalogo id_ref,
        ISNULL(e.fecha_titulo,''),
        E.id_persona,E.flag_terminado,E.id_grado_academico,E.ano_grado_academico,
        E.id_establecimiento,E.id_titulo_obtenido,E.fecha_titulo,E.nro_colegiado,E.fec_venc_colegiado,E.observaciones
        FROM rrhh_persona_escolaridad E
        LEFT OUTER JOIN tbl_catalogo_detalle C ON E.id_titulo_obtenido = C.id_item
        LEFT OUTER JOIN tbl_catalogo_detalle A ON E.id_grado_academico = a.id_item
        WHERE E.id_escolaridad = ?
        ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_escolaridad));
  $response=$stmt->fetch();

  Database::disconnect_sqlsrv();
  return $response;

}

static function get_cuentas_by_empleado($id_persona){
  //nivel academico
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT *
          FROM xxx_rrhh_persona_cuenta_financiera
          WHERE id_persona = ?
        ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_persona));
  $response=$stmt->fetchAll();

  Database::disconnect_sqlsrv();
  return $response;

}

static function get_cursos_listado(){
  //nivel academico
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT *
          FROM rrhh_persona_capacitacion
        ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array());
  $response=$stmt->fetchAll();

  Database::disconnect_sqlsrv();
  return $response;

}

static function get_cursos_by_empleado($id_persona,$filtro){
  //nivel academico
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT *
          FROM xxx_rrhh_persona_capacitaciones
          WHERE id_persona = ? AND id_status IN (1,2)
        ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_persona));
  $response=$stmt->fetchAll();

  Database::disconnect_sqlsrv();
  return $response;

}

static function get_trabajos_by_empleado($id_persona){
  //nivel academico
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT *
          FROM xxx_rrhh_persona_experiencia_laboral
          WHERE id_persona = ?
        ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_persona));
  $response=$stmt->fetchAll();

  Database::disconnect_sqlsrv();
  return $response;

}

static function get_carnets_by_empleado($id_empleado){
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT  id_gafete,id_empleado,id_contrato,id_version,puesto,fecha_generado,fecha_vencimiento,
                  fecha_validado,fecha_baja,fecha_impreso,creado_por,baja_por,validado_por,impreso_por,
                  id_status,id_tipo_carnet,id_arma,id_fotografia
          FROM rrhh_empleado_gafete
          WHERE id_empleado = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_empleado));
  $response=$stmt->fetchAll();

  Database::disconnect_sqlsrv();
  return $response;
}

function get_nro_empleado(){
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT TOP 1 nro_empleado
           FROM rrhh_empleado
           ORDER BY id_empleado DESC";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array());
  $nro_actual=$q0->fetch();
  Database::disconnect_sqlsrv();
  return $nro_actual['nro_empleado']+1;
}

static function get_apoyo_actual_by_persona($id_persona,$tipo){
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($tipo == 2312){
    $query =" rrhh_persona_apoyo ";
  }else if($tipo == 9102){
    $query = "rrhh_persona_practicante";
  }
  $sql = "SELECT TOP 1 b.id_persona, b.id_status AS emp_estado, b.id_status,
            b.tipo_persona,
                a.id_cargo AS id_puesto_servicio,a.id_nivel_servicio,
                a.id_secretaria_servicio,a.id_subsecretaria_servicio,a.id_direccion_servicio,a.id_subdireccion_servicio,
                a.id_depto_servicio,a.id_seccion_servicio,--a.id_categoria,a.id_auditoria,
                e.descripcion AS nivelf, f.descripcion AS secretariaf,g.descripcion AS subsecretariaf,h.descripcion AS direccionf,
                i.descripcion AS subdireccionf,
                j.descripcion AS departamentof,
                s.descripcion AS puestof
          FROM $query a
          INNER JOIN rrhh_persona b ON a.id_persona = b.id_persona
          LEFT JOIN rrhh_jerarquia_niveles e ON a.id_nivel_servicio=e.id_jerarquia

          LEFT JOIN rrhh_direcciones f ON a.id_secretaria_servicio=f.id_direccion
          LEFT JOIN rrhh_direcciones g ON a.id_subsecretaria_servicio=g.id_direccion
          LEFT JOIN rrhh_direcciones h ON a.id_direccion_servicio=h.id_direccion
          LEFT JOIN rrhh_subdirecciones i ON a.id_subdireccion_servicio=i.id_subdireccion
          LEFT JOIN rrhh_departamentos j ON a.id_depto_servicio=j.id_departamento
          LEFT JOIN rrhh_secciones k ON a.id_seccion_servicio=k.id_seccion
          LEFT JOIN tbl_catalogo_detalle s ON a.id_cargo=s.id_item


          WHERE b.id_persona = ? AND b.id_status IN (?,?,?)
          ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_persona, 2312, 5611,9102));
  $response=$stmt->fetch();

  Database::disconnect_sqlsrv();
  return $response;
}
function get_empleados_autoriza($nivel)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT  [id_persona]
                ,[id_status_empleado]
                ,[Estatus_empleado]
                ,[primer_nombre]
                ,[segundo_nombre]
                ,[tercer_nombre]
                ,[primer_apellido]
                ,[segundo_apellido]
                ,[tercer_apellido]
                ,[Nombre_Completo]
                ,[nombre_completo_inverso]
                ,[nivel_funcional]
                ,[ubicacion_nivel_funcional]
                ,[nivel_funcional_superior]
                ,[nombre_nivel_funcional]
                ,[codigo_secretaria_funcional]
                ,[nombre_secretaria_funcional]
                ,[codigo_subsecretaria_funcional]
                ,[nombre_subsecretaria_funcional]
                ,[id_direccion_funcional]
                ,[nombre_direccion_funcional]
                ,[id_subdireccion_funcional]
                ,[nombre_subdireccion_funcional]
                ,[id_depto_funcional]
                ,[nombre_depto_funcional]
                ,[id_seccion_funcional]
                ,[nombre_seccion_funcional]
                ,[id_puesto]
                ,[nombre_puesto]
                ,[id_plaza]
                ,[cod_plaza]
                ,[codigo_puesto_oficial]
                ,[observaciones_empleado]
                ,[observacion_persona]
            FROM [SAAS_APP].[dbo].[xxx_rrhh_persona_ubicacion]
            where id_status_empleado = 891 and nivel_funcional <= 7
            order by nivel_funcional ASC";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }
  static function get_direccion_armada($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT dir.flag_actual,
             dir.id_tipo_referencia id_Tipo,  ref.descripcion cbTipo,
             isnull(dir.tipo_calle,00000) id_CAAV,
             isnull(dir.id_aldea,00000) id_aldea,
             isnull(dir.id_muni,00000) id_muni,
             isnull(dir.id_depto,00000) id_depto,
             isnull(dir.nro_calle_avenida,' ') txCAAV,
             isnull(cAv.descripcion,'') cbCAAV,
             isnull(dir.nro_casa,'') txNroCasa,
             isnull(dir.zona,'') txZona,
             isnull(dep.nombre,'') cbDepto,
             isnull(mun.nombre,'') cbMunicipio,
             isnull(lug.nombre,'') cblugar,
             isnull(dir.nro_apto_oficina,' ') txEdificio,
             isnull(dir.observaciones,' ') edRefer,
             case when dir.nro_apto_oficina is null then '' else RTRIM(LTRIM(dir.nro_apto_oficina)) + ' ' end +
             case when dir.nro_calle_avenida is null or dir.nro_calle_avenida = 0
             then '' else rtrim(ltrim(dir.nro_calle_avenida)) + ' ' + isnull(rtrim(ltrim(cAv.descripcion)),'') +' '  end +
             case when dir.nro_casa is null or dir.nro_casa='' then '' else RTRIM(ltrim(dir.nro_casa))+' ' end +
             case when dir.zona is null or dir.zona = 0 then '' else ' ZONA '+rtrim(ltrim(dir.zona)) +' ' END dir_armada,
             ISNULL(rtrim(ltrim(lug.nombre)),'') lugar_armado,
             isnull(RTRIM(ltrim(mun.nombre))+', ','')+isnull(RTRIM(ltrim(dep.nombre)),'') muni_armado
             from rrhh_persona per left outer join
             rrhh_persona_direcciones dir on per.id_persona=dir.id_persona and flag_actual=1 left outer join
             tbl_catalogo_detalle ref on dir.id_tipo_referencia = ref.id_item left outer join
             tbl_catalogo_detalle cAv on dir.tipo_calle = cAv.id_item LEFT OUTER JOIN
             tbl_municipio mun on dir.id_muni = mun.id_municipio left outer join
             tbl_departamento dep on dir.id_depto = dep.id_departamento left outer join
             tbl_aldea lug on dir.id_aldea = lug.id_aldea
             where per.id_persona = ? and dir.flag_actual=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_persona,1));
    $direccion=$q0->fetch();
    Database::disconnect_sqlsrv();
    return $direccion;
  }

  static function get_vacunas_by_persona($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona, id_vacuna, id_vacuna_dosis, id_vacuna_tipo, fecha_vacunacion,
            CASE
            WHEN id_vacuna_tipo = 1 THEN 'ASTRAZENECA'
            WHEN id_vacuna_tipo = 2 THEN 'J&J'
            WHEN id_vacuna_tipo = 3 THEN 'JANSSEN'
            WHEN id_vacuna_tipo = 4 THEN 'MODERNA'
            WHEN id_vacuna_tipo = 5 THEN 'PFIZER'
            WHEN id_vacuna_tipo = 6 THEN 'SPUTNIK V' ELSE
            'SIN VACUNA' END AS tipo_vacuna
            FROM rrhh_persona_vacuna_covid WHERE id_persona = ?
            ORDER BY id_vacuna ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $vacunas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $vacunas;
  }

  static function get_vacaciones_pendientes($id_persona)
  {

      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT *
              FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
              JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
              WHERE dia_est = 1 AND emp_id = ? AND dia_asi!=dia_goz
              ORDER BY anio_des ASC";

      $stmt = $pdo->prepare($sql);
      if ($stmt->execute(array($id_persona))) {
          $empleados = $stmt->fetchAll();
      } else {
          $empleados = [];
      }
      Database::disconnect_sqlsrv();
      return $empleados;
  }

  static function getLicenciaTipo($id_persona,$tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $licencia = '';
    $sql = "SELECT doc.nro_registro, doc.id_tipo_documento, tipo.descripcion, doc.fecha_vencimiento
             from rrhh_persona_documentos doc left outer join
             tbl_catalogo_detalle tipo on doc.id_tipo_documento=tipo.id_item
             where doc.id_persona = ? and doc.id_tipo_identificacion = ?

            ";

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($id_persona,$tipo))) {
        $licencia = $stmt->fetch();
    } else {
        $licencia = [];
    }
    Database::disconnect_sqlsrv();
    return $licencia;
  }

  static function getArmasByPersona($id_persona){

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $licencia = '';
    $sql = "SELECT * FROM RRHH_PERSONA_ARMAS WHERE ID_PERSONA = ?
            ";

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($id_persona))) {
        $armas = $stmt->fetchAll();
    } else {
        $armas = [];
    }
    Database::disconnect_sqlsrv();
    return $armas;

  }

/*
--- capacitacion
select cur.fecha_inicio, cur.fecha_fin, des.nombre_curso
      from rrhh_persona_capacitacion cur left outer join
      rrhh_persona_curso des on cur.id_curso=des.id_curso
      where id_persona = @id_persona

--- meritos
select * from rrhh_persona_meritos
      where id_persona = @id_persona and inactivo=0
      order by fecha

--- record disciplinario
select d.*, c.descripcion
      from rrhh_persona_disciplina d
      left outer join tbl_catalogo_detalle c on d.id_tipo=c.id_item
      where d.id_persona = @id_persona and d.inactivo=0
      order by d.fecha
*/


}
