<?php
class Boleta
{
  static function get_asignacion_by_empleado($id_persona, $tipo){

    $response = '';
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT  d.*, e.descripcion AS direccion , g.descripcion AS pueston FROM rrhh_empleado b
            LEFT JOIN rrhh_empleado_plaza c ON c.id_empleado = b.id_empleado
            LEFT JOIN SAAS_APP.dbo.rrhh_asignacion_puesto_historial_detalle d ON d.id_asignacion = c.id_asignacion
            LEFT JOIN SAAS_APP.dbo.rrhh_direcciones e ON d.direccion_f = e.id_direccion
      			INNER JOIN SAAS_APP.dbo.rrhh_plaza f ON c.id_plaza = f.id_plaza
      			INNER JOIN SAAS_APP.dbo.rrhh_plazas_puestos g ON g.id_puesto = f.id_puesto

            WHERE b.id_persona = ?
            --ORDER BY d.fecha_inicio DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_contratos_by_empleado($id_persona){
    $response = '';
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT cnt.tipo_contrato,
           CASE WHEN tipo_contrato=8 THEN '031' ELSE
           CASE WHEN tipo_contrato=9 THEN '029' ELSE ' ' END END Renglon,
           e.id_persona, cnt.id_empleado, e.nombre_completo, cnt.reng_num, cnt.nro_contrato, cnt.nro_acuerdo_aprobacion, cnt.fecha_acuerdo_aprobacion,
           cnt.fecha_contrato, e.fecha_ingreso, cnt.fecha_inicio, cnt.fecha_finalizacion, cnt.monto_contrato, cnt.monto_mensual, cnt.fecha_acuerdo_resicion, cnt.fecha_efectiva_resicion,
		         dir.descripcion AS direccion, cd.descripcion AS pueston,cnt.id_direccion_servicio

           FROM dbo.xxx_rrhh_empleado_persona e LEFT JOIN
           dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = e.id_empleado LEFT JOIN

           dbo.rrhh_direcciones dir ON cnt.id_direccion_servicio = dir.id_direccion
		         LEFT JOIN
		           dbo.tbl_catalogo_detalle cd ON cnt.id_puesto_servicio = cd.id_item
           WHERE
           e.id_persona=?
           ORDER BY cnt.reng_num DESC, cnt.tipo_contrato DESC

            ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();
    return $response;

  }
    function set_estado_badge($est_id, $est_des)
    {
        switch ($est_id) {
            case 1:
                $estado = '<span class="text-warning"><i class="fa fa-check-circle"></i> ' . $est_des . '</span>';
                break;

            case 2:
                $estado = '<span class="text-info"><i class="fa fa-check-circle"></i> ' . $est_des . '</span>';
                break;

            case 3:
                $estado = '<span class="text-warning"><i class="fa fa-check-circle"></i> ' . $est_des . '</span>';
                break;

            case 4:
                $estado = '<span class="text-danger"><i class="fa fa-times-circle"></i> ' . $est_des . '</span>';
                break;

            case 5:
                $estado = '<span class="text-info"><i class="fa fa-check-circle"></i> ' . $est_des . '</span>';
                break;

            case 6:
                $estado = '<span class="text-warning"><i class="fa fa-check-circle"></i> ' . $est_des . '</span>';
                break;

            case 7:
                $estado = '<span class="text-danger"><i class="fa fa-times-circle"></i> ' . $est_des . '</span>';
                break;
        }
        return $estado;
    }
    function dias_horas($dh, $e)
    {
        $d = $dh;
        $h = round(fmod($d, 1) * 8);
        $d = floor($d);
        $hs = ($h == 1) ? $h . " hora " : $h . " horas";
        $ds = ($d == 1) ? $d . " día " : $d . " días ";

        switch ($e) {
            case 0:
                return $ds . $hs;
            case 1:
                return $ds . "<br>" . $hs;
            case 2:
                return $d;
            case 3:
                return $h;
            case 4:
                break;
            case 5:
                break;
            case 6:
                break;
            case 7:
                break;
        }
    }
    function full_fecha($fsol)
    {
        $months = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        return date("j", $fsol) . " de " . $months[date("n", $fsol)] . " de " . date("Y", $fsol);
    }
    function get_empleado_puesto_hist($id_persona, $fecha)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT b.id_asignacion, a.id_plaza, a.partida_presupuestaria, b.fecha_toma_posesion, b.fecha_efectiva_resicion, d.primer_nombre, d.segundo_nombre, d.tercer_nombre, d.primer_apellido, d.segundo_apellido, d.tercer_apellido,
        e.descripcion AS puesto, SUELDO, a.cod_plaza, b.id_status, f.descripcion AS estado
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

        WHERE b.actual=1
        group by b.id_plaza) AS sd ON sd.id_plaza=a.id_plaza
        WHERE d.id_persona=? AND ( ? BETWEEN b.fecha_toma_posesion AND ISNULL(b.fecha_efectiva_resicion, GETDATE()))";

        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona, $fecha));
        $plazas = $p->fetch();
        Database::disconnect_sqlsrv();
        return $plazas;
    }
    function get_boleta_by_id($vac_id)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT TOP (1)  V.vac_id, A.anio_des, V.emp_dir, D2.descripcion AS dir_des,  D.*
            		,V.dia_id, F.id_persona, V.vac_fch_tra, V.vac_fch_sol, V.vac_fch_ini,
            		V.vac_fch_fin, V.vac_fch_pre, A.anio_des, EV.est_id, EV.est_des, vac_dia, vac_dia_goz, vac_sub, vac_sol, vac_pen,
            		vac_obs, dia_est, nombre, dir_funcional, F.p_funcional,
                CASE
					WHEN F.id_tipo = 1 THEN F.p_nominal
					WHEN F.id_tipo = 2 THEN F.p_contrato
					END AS p_nominal, F.dir_general, F.id_dirg, F.id_dirfn, F.id_secre, F.id_subsecre
                    ,V.emp_pue, --CD.descripcion AS pue_des,
					D.id_nivel
            		 ,(Case
             When Year(EP.fecha_toma_posesion) = A.anio_des
             Then EP.fecha_toma_posesion
             When (Year(EP.fecha_toma_posesion) != A.anio_des) And   Year(EP.fecha_toma_posesion) != Year(getdate())
             Then ('01/01/' + A.anio_des )
             When Year(EP.fecha_toma_posesion) < Year(getdate())
             Then ('01/01/' + cast(YEAR(EP.fecha_toma_posesion) as varchar) )
             /*When Year(EP.fecha_toma_posesion) = Year(getdate())
             Then PU.fecha_ingreso*/
             End) As periodo_inicio,
              CONVERT(datetime,('31/12/' + A.anio_des),103)   AS periodo_fin
            		FROM [app_vacaciones].[dbo].[VACACIONES] V
                    INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
                    INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
                    INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
                    INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
                    LEFT JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON F.id_direc=D.id_direccion
            		--LEFT JOIN [SAAS_APP].[dbo].[tbl_catalogo_detalle] CD ON V.emp_pue=CD.id_item
            		LEFT JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D2 ON V.emp_dir=D2.id_direccion
            		LEFT JOIN SAAS_APP.dbo.rrhh_empleado EM ON V.emp_id = EM.id_persona
            		LEFT JOIN SAAS_APP.dbo.rrhh_empleado_plaza EP ON EM.id_empleado = EP.id_empleado
            		--LEFT JOIN saas_app.dbo.xxx_rrhh_persona_ubicacion PU ON PU.id_persona = V.emp_id
        WHERE vac_id=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($vac_id));
        $empleado = $p->fetch();
        Database::disconnect_sqlsrv();
        return $empleado;
    }
    function get_boleta_by_persona($vac_id)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT  V.dia_id, F.id_persona, V.vac_id, V.vac_fch_tra, V.vac_fch_sol, V.vac_fch_ini, V.vac_fch_fin, V.vac_fch_pre, A.anio_des, EV.est_id, EV.est_des, vac_dia, vac_dia_goz, vac_sub, vac_sol, vac_pen, vac_obs, dia_est, nombre, dir_funcional, F.p_funcional
        FROM [app_vacaciones].[dbo].[VACACIONES] V
        INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
        WHERE vac_id=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($vac_id));
        $empleado = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $empleado;
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

    function get_all_solicitudes_by_direccion($direccion, $tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.vt_nombramiento, a.fecha, a.id_rrhh_direccion, b.descripcion AS direccion,
                c.descripcion AS estado, d.nombre as municipio, e.nombre as departamento, f.nombre AS pais,
                a.fecha_salida, a.fecha_regreso,a.motivo, a.id_status,a.id_pais, a.descripcion_lugar
                FROM vt_nombramiento a
                INNER JOIN rrhh_direcciones b ON a.id_rrhh_direccion=b.id_direccion
                INNER JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
                INNER JOIN tbl_municipio d ON a.id_municipio = d.id_municipio
                INNER JOIN tbl_departamento e ON d.id_departamento = e.id_departamento
                INNER JOIN tbl_pais f ON e.id_pais=f.id_pais
                WHERE a.id_rrhh_direccion=?

                ";
        if ($tipo == 2) {
            $sql .= " AND a.id_status IN (932,933,935,936,937,938,939,7959)";
        } else
                if ($tipo == 3) {
            $current_year = date('Y-m-d');
            $last_year = strtotime('-1 year', strtotime(date('Y-m-d')));
            $last_year = date('Y-m-d', $last_year);

            $sql .= " AND convert(varchar, a.fecha, 23) BETWEEN '" . $last_year . "' AND '" . $current_year . "'";
        }
        $sql .= "ORDER BY a.vt_nombramiento DESC";

        //echo $sql;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($direccion));
        $nombramientos = $stmt->fetchAll();
        Database::disconnect_sqlsrv();
        return $nombramientos;
    }

    function get_all_solicitudes($tipo, $dir, $subsec, $sec, $fff1, $fff2)
    {

        if (!is_numeric($tipo) == 1) {
            $tipo = 3;
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        /*$sql = "SELECT DISTINCT V.vac_id,
                                F.id_persona,
                                V.emp_pue,
                                CD.descripcion AS pue_des,
                                F.nombre,
                                F.dir_funcional,
                                V.emp_dir,
                                D.descripcion AS dir_des,
                                V.vac_fch_sol,
                                V.vac_fch_ini,
                                V.vac_fch_fin,
                                V.vac_sol,
                                V.vac_fch_pre,
                                A.anio_des,
                                EV.est_id,
                                EV.est_des,
        CASE WHEN DATEDIFF(day, GETDATE(), V.vac_fch_pre) >=1 THEN
		datediff(dd, GETDATE(), V.vac_fch_pre) - (datediff(wk, GETDATE(), V.vac_fch_pre) * 2) -
		case when datepart(dw, GETDATE()) = 1 then 1 else 0 end +
		case when datepart(dw, V.vac_fch_pre) = 1 then 1 else 0 end
		ELSE 0 END AS diares
        FROM [app_vacaciones].[dbo].[VACACIONES] V
        INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
		INNER JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON V.emp_dir=D.id_direccion
		INNER JOIN [SAAS_APP].[dbo].[tbl_catalogo_detalle] CD ON V.emp_pue=CD.id_item
        ";*/

        $sql = "SELECT * FROM (SELECT DISTINCT F.id_persona, V.vac_id, K.direccion, k.rnk,
ISNULL(k.id_drec,V.emp_dir) AS id_drec,
                                --F.id_persona,
                                --V.emp_pue,
								k.puesto_f AS emp_pue,
                                CD.descripcion AS pue_des,
								ISNULL(UPPER(F.primer_nombre),'')+' '+ISNULL(UPPER(F.segundo_nombre),'')+' '+ISNULL(UPPER(F.tercer_nombre),'')+' '+
								ISNULL(UPPER(F.primer_apellido),'')+' '+ISNULL(UPPER(F.segundo_apellido),'')+' '+ISNULL(UPPER(F.tercer_apellido),'') AS nombre,
                                --F.nombre,
                                --F.dir_funcional,

								ISNULL(k.direccion,D.descripcion) AS dir_funcional,
                                V.emp_dir,
                                D.descripcion AS dir_des,

                                V.vac_fch_sol,
                                V.vac_fch_ini,
                                V.vac_fch_fin,
                                V.vac_sol,
                                V.vac_fch_pre,
                                A.anio_des,
                                EV.est_id,
                                EV.est_des,
        CASE WHEN DATEDIFF(day, GETDATE(), V.vac_fch_pre) >=1 THEN
		datediff(dd, GETDATE(), V.vac_fch_pre) - (datediff(wk, GETDATE(), V.vac_fch_pre) * 2) -
		case when datepart(dw, GETDATE()) = 1 then 1 else 0 end +
		case when datepart(dw, V.vac_fch_pre) = 1 then 1 else 0 end
		ELSE 0 END AS diares
        FROM [app_vacaciones].[dbo].[VACACIONES] V
        INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        --INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
		INNER JOIN rrhh_persona F ON v.emp_id = F.id_persona
		INNER JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON V.emp_dir=D.id_direccion
		INNER JOIN [SAAS_APP].[dbo].[tbl_catalogo_detalle] CD ON V.emp_pue=CD.id_item

		LEFT JOIN (SELECT T.fecha_inicio,T.id_persona, T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion, T.direccion, T.direccion_n, T.cargo, T.puesto_f, T.rnk, T.id_drec
                        FROM
                        (
                          SELECT ISNULL(a_c.direccion_f,a_c.subsecretaria_f) AS id_drec, a_c.fecha_inicio, k_c.id_persona, c_c.id_plaza, a_c.reng_num, c_c.id_empleado, c_c.id_asignacion,
            						  c_c.fecha_efectiva_resicion,ISNULL(d_c.descripcion,ISNULL(e_c.descripcion,'N')) AS direccion, f_c.descripcion AS puesto_f,
									  ISNULL(h_c.descripcion,ISNULL(i_c.descripcion,'N')) AS direccion_n, j_c.descripcion AS cargo,
            						  ROW_NUMBER() OVER (PARTITION BY k_c.id_empleado ORDER BY c_c.id_asignacion DESC) AS rnk
            						  FROM rrhh_asignacion_puesto_historial_detalle a_c

                                      INNER JOIN rrhh_empleado_plaza c_c ON a_c.id_asignacion = c_c.id_asignacion
            						  LEFT JOIN rrhh_direcciones d_c ON a_c.direccion_f = d_c.id_direccion
									  LEFT JOIN rrhh_direcciones e_c ON a_c.subsecretaria_f = e_c.id_direccion
									  LEFT JOIN tbl_catalogo_detalle f_c ON a_c.puesto_f = f_c.id_item
									  LEFT JOIN rrhh_plaza_detalle_ubicacion g_c ON c_c.id_plaza = g_c.id_plaza
									  LEFT JOIN rrhh_direcciones h_c ON g_c.id_direccion_n = h_c.id_direccion
									  LEFT JOIN rrhh_direcciones i_c ON g_c.id_subsecretaria_n = i_c.id_direccion
									  INNER JOIN rrhh_plaza b_c ON c_c.id_plaza = b_c.id_plaza
									  INNER JOIN rrhh_empleado k_c ON c_c.id_empleado = k_c.id_empleado
									  LEFT JOIN rrhh_plazas_puestos j_c ON b_c.id_puesto = j_c.id_puesto
									  --WHERE id_persona = 410
									  --WHERE YEAR(c.fecha_toma_posesion) = 2023
									  --WHERE YEAR(c_c.fecha_toma_posesion) = 2020
									  --AND c.id_empleado = 2743
            						  --WHERE   c_c.id_status = 891

                        ) T
                        WHERE T.rnk = 1
                      ) AS k ON F.id_persona = k.id_persona --AND YEAR(k.fecha_inicio) <= YEAR(V.vac_fch_ini)

                      ) AS VV

";

        //         $sql = "SELECT DISTINCT V.vac_id, F.id_persona, F.nombre, F.dir_funcional, V.vac_fch_sol, V.vac_fch_ini, V.vac_fch_fin, V.vac_sol, V.vac_fch_pre, A.anio_des, EV.est_id, EV.est_des
        // ,CASE WHEN DATEDIFF(day, GETDATE(), V.vac_fch_pre) >=1 THEN DATEDIFF(day, GETDATE(), V.vac_fch_fin)+1 ELSE 0 END AS diares
        // FROM [app_vacaciones].[dbo].[VACACIONES] V
        // INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        // INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        // INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        // INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
        // ";
        if ($tipo != 3) {
            $sql .= "WHERE ";
        }
        if ($tipo == 1) {
            $sql .= "(VV.est_id = 1 OR VV.est_id = 2)";
        } else if ($tipo == 2) {
            $current_year = date('Y-m-d');
            $last_year = strtotime('-1 year', strtotime(date('Y-m-d')));
            $last_year = date('Y-m-d', $last_year);
            $sql .= "CONVERT(DATE, VV.vac_fch_sol) BETWEEN CAST('" . $last_year . "' AS DATE) AND CAST('" . $current_year . "' AS DATE)";
        } else if ($tipo == 4) {
            $current_year = date('Y-m-d');
            // $sql .= " CAST( '" . $current_year . "' AS DATE) BETWEEN CONVERT(DATE, V.vac_fch_ini) AND CONVERT(DATE, V.vac_fch_fin) AND EV.est_id = 5";
            $sql .= "VV.est_id = 5 AND (CONVERT(DATE, VV.vac_fch_ini) >=  CAST('" . $current_year . "' AS DATE) OR  CONVERT(DATE, VV.vac_fch_fin) >=  CAST('" . $current_year . "' AS DATE))";
        } else if ($tipo == 5) {
            $sql .= " CAST(VV.vac_fch_ini AS DATE) BETWEEN CONVERT(DATE, VV.vac_fch_ini) AND CONVERT(DATE, VV.vac_fch_fin) AND VV.est_id = 5";
        }
        if ($dir != 0) {
          if ($dir == 663 || $dir == 667) { //despacho del secretario
            $direcciones = '(7,2,655,657)';
            $sql .= " AND VV.id_direc IN " . $direcciones;
          }else
           if($dir == 207){
             $sql .= " AND VV.subdireccion_f IN (34,37) ";
           }else{

            $sql .= " AND VV.id_direc =" . $dir;
          }

        } else if ($subsec != 0) {
            $sql .= "AND VV.id_direc = 0 AND VV.id_subsecre =" . $subsec;
        } else if ($sec != 1) {
            $sql .= "AND VV.id_direc = 0 AND VV.id_subsecre = 0 AND VV.id_subsecre = 4";
        } else {
            $sql .= "";
        }
        $sql .= "ORDER BY VV.vac_id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $vacaciones = $stmt->fetchAll();
        Database::disconnect_sqlsrv();
        return $vacaciones;
    }
    function save_vacaciones()
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO VALUES()";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array())) {
            $response =  array(
                "status" => 200,
                "msg" => ""
            );
        } else {
            $response =  array(
                "status" => 400,
                "msg" => "error"
            );
        }
        Database::disconnect_sqlsrv();
        return $response;
    }

    function get_empleados($dirf)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT([id_persona])
                                ,[nombre]
                                ,[p_funcional]
                                ,[dir_general]
                                ,[p_contrato]
                            FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F
                            LEFT JOIN [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A ON A.emp_id=F.id_persona
                            JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
                            WHERE estado=1 AND dia_est = 1 AND F.id_direc = ?  AND F.id_tipo IN (1,2)
                            ORDER BY id_persona";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($dirf))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_empleados_1_0($tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        /*$sql = "SELECT DISTINCT([id_persona])
                                ,[nombre]
                                ,[p_funcional]
                                ,[dir_general]
                                ,[p_contrato]
                            FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F
                            LEFT JOIN [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A ON A.emp_id=F.id_persona
                            JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
                            WHERE estado=? AND dia_est = 1 AND F.id_tipo IN (1,2)
                            ORDER BY id_persona";*/
        $sql = "SELECT * from (
              SELECT f.id_persona, f.nombre, STRING_AGG(q.csv, CHAR(13)) AS vacaciones,
              k.puesto_f AS p_funcional, k.direccion AS dir_general, CASE WHEN k.puesto_f <> NULL THEN k.puesto_f ELSE NULL END AS  p_contrato
              FROM (select distinct id_persona, nombre, estado, id_tipo from saas_app.dbo.xxx_rrhh_ficha ) f
              LEFT JOIN (
                  SELECT A.emp_id, 'año: ' + CONVERT(VARCHAR, y.anio_des) + ' - ' + CONVERT(varchar, convert(numeric(5,2),a.dia_asi-a.dia_goz)) + ' <br> ' AS csv, y.anio_des, a.dia_asi, a.dia_goz
                  FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
                  JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id = A.anio_id
                  WHERE dia_est IN (1, 2) AND dia_asi != dia_goz
                  GROUP BY a.emp_id, y.anio_des, a.dia_asi, a.dia_goz
              ) q ON f.id_persona = q.emp_id
			  LEFT JOIN (SELECT T.fecha_inicio,T.id_persona, T.id_empleado, T.id_asignacion, T.fecha_efectiva_resicion, T.direccion, T.direccion_n, T.cargo, T.puesto_f, T.rnk
                        FROM
                        (
                          SELECT a_c.fecha_inicio, k_c.id_persona, c_c.id_plaza, a_c.reng_num, c_c.id_empleado, c_c.id_asignacion,
            						  c_c.fecha_efectiva_resicion,ISNULL(d_c.descripcion,ISNULL(e_c.descripcion,'N')) AS direccion, f_c.descripcion AS puesto_f,
									  ISNULL(h_c.descripcion,ISNULL(i_c.descripcion,'N')) AS direccion_n, j_c.descripcion AS cargo,
            						  ROW_NUMBER() OVER (PARTITION BY k_c.id_empleado ORDER BY c_c.id_asignacion DESC) AS rnk
            						  FROM rrhh_asignacion_puesto_historial_detalle a_c

                                      INNER JOIN rrhh_empleado_plaza c_c ON a_c.id_asignacion = c_c.id_asignacion
            						  LEFT JOIN rrhh_direcciones d_c ON a_c.direccion_f = d_c.id_direccion
									  LEFT JOIN rrhh_direcciones e_c ON a_c.subsecretaria_f = e_c.id_direccion
									  LEFT JOIN tbl_catalogo_detalle f_c ON a_c.puesto_f = f_c.id_item
									  LEFT JOIN rrhh_plaza_detalle_ubicacion g_c ON c_c.id_plaza = g_c.id_plaza
									  LEFT JOIN rrhh_direcciones h_c ON g_c.id_direccion_n = h_c.id_direccion
									  LEFT JOIN rrhh_direcciones i_c ON g_c.id_subsecretaria_n = i_c.id_direccion
									  INNER JOIN rrhh_plaza b_c ON c_c.id_plaza = b_c.id_plaza
									  INNER JOIN rrhh_empleado k_c ON c_c.id_empleado = k_c.id_empleado
									  LEFT JOIN rrhh_plazas_puestos j_c ON b_c.id_puesto = j_c.id_puesto
									  --WHERE id_persona = 410
									  --WHERE YEAR(c.fecha_toma_posesion) = 2023
									  --WHERE YEAR(c_c.fecha_toma_posesion) = 2020
									  --AND c.id_empleado = 2743
            						  --WHERE   c_c.id_status = 891

                        ) T
                        WHERE T.rnk = 1
                      ) AS k ON F.id_persona = k.id_persona --AND k.fecha_inicio <= V.vac_fch_ini


              WHERE f.estado = ?  and not f.id_tipo=4
              GROUP BY f.id_persona, f.nombre, k.puesto_f, k.direccion ) x where not vacaciones is null;-- algunos PNC aparecen como renglon 011 por error
              ";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($tipo))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_empleados_all()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT([id_persona])
                                ,[nombre]
                                ,[p_funcional]
                                ,[dir_general]
                                ,[p_contrato]
                            FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F
                            LEFT JOIN [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A ON A.emp_id=F.id_persona
                            JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
                            WHERE estado=1 AND dia_est = 1
                            ORDER BY id_persona";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_vacaciones_utilizadas($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT  SUM(vac_sol) AS dutz
        FROM [APP_VACACIONES].[dbo].[VACACIONES]
        WHERE emp_id=? AND est_id=5 AND YEAR(vac_fch_ini)=YEAR(GETDATE())";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $empleados = $stmt->fetch();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_vacaciones_pendientes($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT A.dia_id, A.emp_id, A.emp_dir, A.emp_pue, A.anio_id, A.dia_asi, A.dia_goz, A.dia_est, A.dia_obs, ISNULL(dia_liq, 0) AS dia_liq,
                Y.anio_des, d.dias_apartados
                FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
                JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
        				LEFT JOIN (SELECT anio_id, emp_id, SUM(vac_sol) AS dias_apartados FROM APP_VACACIONES.dbo.VACACIONES
        				   WHERE est_id IN (1,2)
        				   GROUP BY anio_id, emp_id) AS d ON a.emp_id = d.emp_id AND d.anio_id = A.anio_id
                WHERE dia_est IN (1,2) AND A.emp_id = ? AND dia_asi!=dia_goz AND ISNULL(dia_liq,0) = ?
                ORDER BY anio_des ASC";

                // ya valida las vacaciones liquidadas
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona,0))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_vacaciones_apartadas($dia_id)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT  TOP(1) *
                FROM [APP_VACACIONES].[dbo].[VACACIONES]
                WHERE dia_id=? AND (est_id=1 OR est_id=2)
                ORDER BY vac_pen ASC";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($dia_id))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_dias_persona($id_persona, $dia_id)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
                JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON A.anio_id=Y.anio_id
                WHERE emp_id = ? AND dia_id = ?
                ORDER BY anio_des ASC";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona, $dia_id))) {
            $empleados = $stmt->fetch();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }

    function get_dias_asignados($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
                LEFT JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON A.anio_id=Y.anio_id
                LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON A.emp_id=F.id_persona
                WHERE emp_id = ?
                ORDER BY anio_des DESC";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }

    function get_dias_asignados_cert($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
                LEFT JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON A.anio_id=Y.anio_id
                LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON A.emp_id=F.id_persona
                WHERE emp_id = ? AND dia_est = 1
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

    function get_fechas_ingreso_baja($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT e.*, '||' xx, p.* FROM rrhh_empleado e
                LEFT OUTER JOIN rrhh_empleado_plaza p ON e.id_empleado = p.id_empleado
                WHERE e.id_persona=?";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_vacaciones_periodos($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT	 V.vac_id
                        ,V.emp_id
                        ,anio_des
                        ,CONVERT (varchar(10), vac_fch_tra, 103) AS vac_fch_tra
                        ,CONVERT (varchar(10), vac_fch_sol, 103) AS vac_fch_sol
                        ,CONVERT (varchar(10), vac_fch_ini, 103) AS vac_fch_ini
                        ,CONVERT (varchar(10), vac_fch_fin, 103) AS vac_fch_fin
                        ,CONVERT (varchar(10), vac_fch_pre, 103) AS vac_fch_pre
                        ,vac_dia
                        ,vac_dia_goz
                        ,vac_sub
                        ,vac_sol
                        ,vac_pen
                        ,vac_obs
                        ,est_des
                        ,E.est_id
                        ,vac_obs_anula
                FROM [APP_VACACIONES].[dbo].[ANIO] Y
                JOIN [APP_VACACIONES].[dbo].[VACACIONES] V ON Y.anio_id=V.anio_id
                JOIN [APP_VACACIONES].[dbo].[ESTADO_VACACIONES] E ON E.est_id=V.est_id
                WHERE V.emp_id=?
                ORDER BY anio_des DESC, vac_fch_tra DESC";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_boletas_empleado($id_persona, $tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT cop.id_control, cap.nombre AS motivo, fecha_inicio, fecha_fin, observaciones, nro_boleta, autoriza, est_id, est_des, cap.tipo
                FROM [SAAS_APP].[dbo].[tbl_control_permisos] cop
                LEFT JOIN [SAAS_APP].[dbo].[tbl_catalogo_permisos] cap ON cap.id_catalogo = cop.id_catalogo
                LEFT JOIN [APP_VACACIONES].[dbo].[ESTADO_VACACIONES] eva ON eva.est_id = cop.estado
                WHERE cap.tipo=" . $tipo . " AND id_persona =" . $id_persona;
        // echo $sql;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_nivel_empleado($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $response =  $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }

    static function calular_dias_proporcional($date1, $date2){
      $f1 = new DateTime($date1);
      $f2 = new DateTime($date2);

      $intervalo = $f1->diff($f2);
      $dias = $intervalo->days;

      $year = self::esBisiesto(date('Y',strtotime($date1)));
      $calculo = ($year == true) ? ($dias * 20) / 366 :  ($dias * 20) / 365;;
      return number_format($calculo,6,'.',',');
    }

    function esBisiesto($anio) {
      return !($anio % 4) && ($anio % 100 || !($anio % 400));
    }

    function validarRenglonEmpleado($id_persona,$fecha){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT TOP 1 tb.* FROM (
        SELECT a.id_persona, a.id_empleado, CONVERT(VARCHAR,b.fecha_toma_posesion,23) AS f_ini, b.id_status, '011' AS renglon
        FROM rrhh_empleado a
        INNER JOIN rrhh_empleado_plaza b ON b.id_empleado = a.id_empleado
        UNION
        SELECT a.id_persona, a.id_empleado, CONVERT(VARCHAR,b.fecha_inicio,23) AS f_ini, b.id_status, '031' AS renglon
        FROM rrhh_empleado a
        INNER JOIN rrhh_empleado_contratos b ON b.id_empleado = a.id_empleado
        WHERE b.tipo_contrato = 8) AS tb WHERE tb.id_persona = ? AND CONVERT(VARCHAR,tb.f_ini,23) < ?
        ORDER BY tb.f_ini DESC";
      $stmt = $pdo->prepare($sql);
      if ($stmt->execute(array($id_persona,$fecha))) {
          $response =  $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
          $response = [];
      }
      Database::disconnect_sqlsrv();
      return $response;


    }
}
class Horario
{
    static function get_horario_general($month, $year, $dir, $month1, $year1)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";
        if ($dir != 'TODOS') {
            $sql = "SELECT I.id_persona, nombre
            FROM (SELECT DISTINCT(id_persona), MAX(fecha) AS fecha
                        FROM tbl_control_ingreso
                        WHERE id_punto in (2,3,4,5,6) AND CONVERT(DATE,fecha) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE)
                        GROUP BY id_persona) as I
                        INNER JOIN (SELECT DISTINCT(id_persona), nombre, dir_funcional, dir_nominal, p_funcional, p_nominal, p_contrato
                        FROM xxx_rrhh_Ficha
                        WHERE dir_funcional = '" . $dir . "')  AS RH  ON I.id_persona = RH.id_persona
                        ORDER BY I.fecha DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Database::disconnect_sqlsrv();
            return $horarios;
        } else {
            $sql = "SELECT I.id_persona, nombre
            FROM (SELECT DISTINCT(id_persona), MAX(fecha) AS fecha
                        FROM tbl_control_ingreso
                        WHERE id_punto in (2,3,4,5,6) AND CONVERT(DATE,fecha) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE)
                        GROUP BY id_persona) as I
                        INNER JOIN (SELECT DISTINCT(id_persona), nombre, dir_funcional, dir_nominal, p_funcional, p_nominal, p_contrato
                        FROM xxx_rrhh_Ficha)  AS RH  ON I.id_persona = RH.id_persona
                        ORDER BY I.fecha DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Database::disconnect_sqlsrv();
            return $horarios;
        }
    }
    function get_empleados_all()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT I.id_persona, RH.nombre, RH.dir_nominal, RH.dir_funcional,
        CASE WHEN RH.p_contrato IS NOT NULL THEN RH.p_contrato ELSE p_funcional END p_funcional,
        CASE WHEN RH.p_contrato IS NOT NULL THEN RH.p_contrato ELSE p_nominal END p_nominal
        FROM (SELECT DISTINCT(id_persona)
                    FROM tbl_control_ingreso) as I
                    INNER JOIN (SELECT DISTINCT(id_persona), nombre, dir_funcional, dir_nominal, p_funcional, p_nominal, p_contrato, estado
                    FROM xxx_rrhh_Ficha WHERE estado=1)  AS RH  ON I.id_persona = RH.id_persona ORDER BY I.id_persona ASC";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_empleados($year, $month)
    {
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year . "-" . $month . "-" . $saladdays . "'";
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT I.id_persona, RH.nombre, RH.dir_nominal, RH.dir_funcional,
        CASE WHEN RH.p_contrato IS NOT NULL THEN RH.p_contrato ELSE p_funcional END p_funcional,
        CASE WHEN RH.p_contrato IS NOT NULL THEN RH.p_contrato ELSE p_nominal END p_nominal
        FROM (SELECT DISTINCT(id_persona), MAX(fecha) AS fecha
                    FROM tbl_control_ingreso
                    WHERE id_punto in (2,3,4,5,6) AND CONVERT(DATE,fecha) BETWEEN CAST(" . $date1 . "AS DATE) AND CAST(" . $date2 . " AS DATE)
                    GROUP BY id_persona) as I
                    INNER JOIN (SELECT DISTINCT(id_persona), nombre, dir_funcional, dir_nominal, p_funcional, p_nominal, p_contrato
                    FROM xxx_rrhh_Ficha)  AS RH  ON I.id_persona = RH.id_persona
                    ORDER BY I.fecha DESC";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $empleados = $stmt->fetchAll();
        } else {
            $empleados = [];
        }
        Database::disconnect_sqlsrv();
        return $empleados;
    }
    function get_reporte_horario($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT rh.nombre, rh.dir_funcional, rh.p_funcional, ph.*, ch.*
        FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] AS rh
        LEFT JOIN [SAAS_APP].[dbo].[tbl_persona_horario] AS ph ON ph.id_persona = rh.id_persona
        LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
        WHERE rh.id_persona=?;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_name($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT rh.nombre, rh.dir_funcional, rh.p_funcional, ph.*, ch.*, rh.id_direc, rh.id_subsecre, rh.id_secre
        FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] AS rh
        LEFT JOIN [SAAS_APP].[dbo].[tbl_persona_horario] AS ph ON ph.id_persona = rh.id_persona
        LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
        WHERE rh.id_persona=? ORDER BY fecha_ini DESC, ph.estado DESC";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_tarde_by_empleado($id_persona, $entrada, $fecha)
    {
        $entrada = "'" . $entrada . "'";
        $fecha = "'" . date('Y-m-d', strtotime($fecha)) . "'";
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM ( SELECT TOP(1)
        (SELECT (CASE
                   WHEN (((DATEPART(DW,  CONVERT(DATE," . $fecha . ")) - 1 ) + @@DATEFIRST ) % 7) IN (0,6)
                   THEN 1
                   ELSE 0
               END) AS is_weekend_day) AS wkend,
                CASE WHEN CONVERT(TIME," . $entrada . ") >= (CASE WHEN hora_ini IS NULL THEN entrada ELSE hora_ini END) THEN 1 ELSE 0 END tarde,
                CASE WHEN hora_ini IS NULL THEN (CAST(entrada as varchar(5)) +' - '+CAST(salida as varchar(5))) ELSE (CAST(hora_ini as varchar(5)) +' - '+CAST(hora_fin as varchar(5))) END horas
                FROM [SAAS_APP].[dbo].[tbl_persona_horario] AS ph
                LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
                WHERE estado = 1 AND id_persona=" . $id_persona . " AND CONVERT(DATE," . $fecha . ") BETWEEN fecha_ini AND (CASE WHEN ph.fecha_fin IS NULL THEN GETDATE() ELSE fecha_fin END)
                ORDER BY
                (CASE
                    WHEN ph.id_horario = 0 THEN fecha_ini
                END) DESC) AS trd
                WHERE wkend=0";
        // echo $sql;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $listado = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $listado = [];
        }
        Database::disconnect_sqlsrv();
        return $listado;
    }
    function get_tarde_by_empleado_1($id_persona, $month, $year)
    {

        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year . "-" . $month . "-" . $saladdays . "'";

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $sql = "SELECT H.id_persona, MIN(entrada) entrada, fecha, (SELECT TOP(1)
        // CASE WHEN CONVERT(TIME, H.entrada) >= (CASE WHEN hora_ini IS NULL THEN entrada ELSE hora_ini END) AND CONVERT(TIME, H.entrada) <= CONVERT(TIME,'11:00') THEN 1 ELSE 0 END tarde
        // FROM [SAAS_APP].[dbo].[tbl_persona_horario] AS ph
        // LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
        // WHERE estado = 1 AND id_persona=" . $id_persona . " AND CONVERT(DATE,H.fecha) BETWEEN fecha_ini AND (CASE WHEN ph.fecha_fin IS NULL THEN GETDATE() ELSE fecha_fin END)
        // ORDER BY
        // (CASE
        //     WHEN ph.id_horario = 0 THEN fecha_ini
        // END) DESC) AS tarde
        // FROM (SELECT t.id_persona,
        //         CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END entrada,
        //         convert(date,t.fecha) fecha
        //         FROM tbl_control_ingreso t
        //         WHERE t.id_punto = 2 AND t.id_persona = " . $id_persona . " AND (t.fecha BETWEEN CONVERT(DATE," . $date1 . ") AND CONVERT(DATE," . $date2 . ") )) AS H

        // 		WHERE entrada<'11:00'
        //         GROUP BY H.fecha, H.id_persona, H.entrada
        //         ORDER BY H.fecha";


        $sql = "SELECT * FROM (SELECT fecha
        ,h.id_persona
        ,h.entrada
		,(SELECT (CASE
				   WHEN (((DATEPART(DW, fecha) - 1 ) + @@DATEFIRST ) % 7) IN (0,6)
				   THEN 1
				   ELSE 0
				   END)AS is_weekend_day) AS wkend
        ,(SELECT TOP(1)
                     CASE
                            WHEN convert(time,h.entrada) >= (CASE WHEN hora_ini IS NULL THEN entrada
                                   ELSE hora_ini END)  THEN 1
                                   ELSE 0
                            END tarde
              FROM tbl_persona_horario AS ph
                            left JOIN
                            tbl_control_horario AS ch
                            ON ph.id_horario=ch.id_horario
              WHERE estado = 1
                     AND id_persona=h.id_persona
                     AND convert(date,h.fecha) BETWEEN fecha_ini AND (
                            CASE
                                   WHEN ph.fecha_fin IS NULL THEN getdate()
                                   ELSE fecha_fin
                            END)
              ORDER BY (CASE WHEN ph.id_horario = 0 THEN fecha_ini END) DESC
              ) AS tarde
        FROM (SELECT
                     convert(date,t.fecha) as fecha
                     ,t.id_persona
                     ,min(convert(time,t.fecha)) as entrada
                     FROM tbl_control_ingreso as t
                     WHERE t.id_punto IN (2,3,4,5,6)
                                   AND t.id_persona = " . $id_persona . "
                                   AND (t.fecha BETWEEN convert(date," . $date1 . ") AND convert(date," . $date2 . ") )
                     GROUP BY convert(date,t.fecha),t.id_persona
              ) AS h

			  ) AS fff

		WHERE wkend=0
        ORDER BY fff.fecha";
        // echo $sql;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listado = [];
        }
        Database::disconnect_sqlsrv();
        return $listado;
    }
    function get_control_horario($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT rh.nombre, rh.dir_funcional, rh.p_funcional, ph.*, ch.*
                FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] AS rh
                LEFT JOIN [SAAS_APP].[dbo].[tbl_persona_horario] AS ph ON ph.id_persona = rh.id_persona
                LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
                WHERE rh.id_persona=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_persona));
        $foto = $stmt->fetchAll();
        Database::disconnect_sqlsrv();
        return $foto;
    }
    function get_empleado_fotografia($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT fotografia
                FROM rrhh_persona_fotografia
                WHERE id_persona = ? AND fotografia_principal = 1;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_persona));
        $foto = $stmt->fetch();
        Database::disconnect_sqlsrv();
        return $foto;
    }
    function get_horario_empleado($id_punto, $id_persona, $month, $year, $month1, $year1)
    {
        $month1 = $month1 + 1;
        $t1 = "'" . $year . "-" . $month . "-01'";
        // $t2 = "'" . $year1 . "-" . $month1 . "-" . cal_days_in_month(CAL_GREGORIAN, $month1, $year1) . "'";
        $t2 = "'" . $year1 . "-" . $month1 . "-01'";
        if ($month1 == 13) {
            $year1 = $year1 + 1;
            $month1 = 1;
            $t2 = "'" . $year1 . "-" . $month1 . "-01'";
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $sql = "SELECT MIN(entrada) entrada, MIN(almuerzo) entra_alm, MAX(almuerzo) sale_alm, MAX(entrada) salida, fecha
        //         FROM (SELECT
        //                 CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END entrada,
        //                 CASE WHEN convert(time,t.fecha) between '11:00:00' AND '14:59:59' THEN convert(time,t.fecha) END almuerzo,
        //                 convert(date,t.fecha) fecha
        //                 FROM tbl_control_ingreso t
        //                 WHERE t.id_punto = ? AND t.id_persona = ? AND YEAR(t.fecha) = ? AND MONTH(t.fecha) = ?) as H
        //         GROUP BY H.fecha;";
        $sql = "SELECT MIN(entrada) entrada, MIN(almuerzo) entra_alm, MAX(almuerzo) sale_alm, MAX(entrada) salida, fecha
        FROM (SELECT
                CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END entrada,
                CASE WHEN convert(time,t.fecha) between '11:00:00' AND '13:59:59' THEN convert(time,t.fecha) END almuerzo,
                convert(date,t.fecha) fecha
                FROM tbl_control_ingreso t
                WHERE t.id_punto in (2,3,4,5,6) AND t.id_persona = ? AND (t.fecha BETWEEN CONVERT(DATE," . $t1 . ") AND CONVERT(DATE," . $t2 . ") )) as H
                GROUP BY H.fecha
                ORDER BY H.fecha";
        // $sql ="SELECT hr.*, id_control, ph.id_horario, flag_fijo, fecha_ini, fecha_fin, hora_ini, hora_fin, dia, CASE WHEN hr.fecha BETWEEN fecha_ini AND (CASE WHEN ph.fecha_fin IS NULL THEN GETDATE() ELSE fecha_fin END) THEN ph.id_horario ELSE 0 END htipo
        //         , ch.*
        //         FROM (	SELECT t.id_persona,
        //         CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END entrada,
        //         CASE WHEN convert(time,t.fecha) between '11:00:00' AND '14:59:59' THEN convert(time,t.fecha) END almuerzo,
        //         convert(date,t.fecha) fecha
        //         FROM [SAAS_APP].[dbo].[tbl_control_ingreso] t
        //         WHERE t.id_punto = ? AND t.id_persona = ? AND (t.fecha BETWEEN CONVERT(DATE,".$t1.") AND CONVERT(DATE, ".$t2.") )) hr
        //         LEFT JOIN (SELECT * FROM [SAAS_APP].[dbo].[tbl_persona_horario] WHERE id_persona = ".$id_persona.") AS ph ON ph.id_persona = hr.id_persona
        //         LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario"
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_persona, $year, $month));
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();
        return $horarios;
    }
    function get_horarios_by_empleado($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT tch.id_control, tch.id_persona, tch.flag_fijo, CONVERT(VARCHAR,tch.fecha_ini,23) AS fecha_ini, CONVERT(VARCHAR,tch.fecha_fin,23) AS fecha_fin, tch.hora_ini,
tch.hora_fin, tch.dia, tch.estado, tch.entrada, tch.salida
        FROM (SELECT ph.id_control, ph.id_persona, ph.flag_fijo, CONVERT(VARCHAR,ph.fecha_ini,23) AS fecha_ini, CONVERT(VARCHAR,ph.fecha_fin,23) AS fecha_fin,
		ph.hora_ini, ph.hora_fin, ph.dia, ph.estado, ch.entrada, ch.salida
              FROM [SAAS_APP].[dbo].[tbl_persona_horario] AS ph
              LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
              WHERE id_persona=? AND estado=1) tch
        UNION
        SELECT id_control, id_persona, id_catalogo AS flag_fijo, CONVERT(VARCHAR,fecha_inicio,23) AS fecha_ini, CONVERT(VARCHAR,fecha_fin,23) AS fecha_fin, NULL AS hora_ini,
		NULL AS hora_fin, NULL AS dia, estado, NULL AS entrada, NULL AS salida
        FROM [SAAS_APP].[dbo].[tbl_control_permisos]
        WHERE id_persona=? AND (id_catalogo=63 OR id_catalogo=64) AND estado=5";

        // echo $sql;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona, $id_persona))) {
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listado = [];
        }
        Database::disconnect_sqlsrv();
        return $listado;
    }
    function get_horarios_by_empleado_by_fecha($id_persona, $fecha)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT tch.id_control, tch.id_persona, tch.flag_fijo, CONVERT(VARCHAR,tch.fecha_ini,23) AS fecha_ini, CONVERT(VARCHAR,tch.fecha_fin,23) AS fecha_fin, tch.hora_ini, tch.hora_fin, tch.dia, tch.estado, tch.entrada, tch.salida
        FROM (SELECT ph.id_control, ph.id_persona, ph.flag_fijo, ph.fecha_ini, ph.fecha_fin, ph.hora_ini, ph.hora_fin, ph.dia, ph.estado, ch.entrada, ch.salida
              FROM [SAAS_APP].[dbo].[tbl_persona_horario] AS ph
              LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
              WHERE id_persona=? AND estado=1) tch
        UNION
        SELECT id_control, id_persona, id_catalogo AS flag_fijo, CONVERT(VARCHAR,fecha_inicio,23) AS fecha_ini, CONVERT(VARCHAR,fecha_fin,23), NULL AS hora_ini, NULL AS hora_fin, NULL AS dia, estado, NULL AS entrada, NULL AS salida
        FROM [SAAS_APP].[dbo].[tbl_control_permisos]
        WHERE id_persona=? AND (id_catalogo=63 OR id_catalogo=64) AND estado=5 ";

        // echo $sql;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona, $id_persona, $fecha))) {
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listado = [];
        }
        Database::disconnect_sqlsrv();
        return $listado;
    }
    function get_listado_descansos()
    {
        $year = date("Y");
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM tbl_listado_descansos
                WHERE id_descanso NOT IN (SELECT id_descanso
                                            FROM tbl_fechas_descansos
                                            WHERE YEAR(fecha_inicio) = ?);";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($year))) {
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $listado = [];
        }
        Database::disconnect_sqlsrv();
        return $listado;
    }
    function get_total_descansos($year, $opcion, $mes, $year1, $month1)
    {
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        $date1 = "'" . $year . "-" . $mes . "-" . "01'";
        $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";

        if ($opcion == 1) {
            $str_select = "FD.fecha_inicio as inicio, FD.fecha_fin as fin";
            $str_where = "YEAR(FD.fecha_inicio) = ? ";
        } else if ($opcion == 2) {
            $str_select = " convert(varchar, FD.fecha_inicio, 105) as inicio, convert(varchar, FD.fecha_fin, 105) as fin";
            $str_where = " CONVERT(DATE, FD.fecha_inicio) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE) ";
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT TP.id_tipo, TP.nombre as ausencia,
                CASE WHEN LD.id_tipo_ausencia = 3 THEN FD.motivo ELSE LD.nombre END motivo, LD.tipo, {$str_select}
                FROM tbl_tipos_ausencia TP
                INNER JOIN tbl_listado_descansos LD ON TP.id_tipo = LD.id_tipo_ausencia
                INNER JOIN tbl_fechas_descansos FD ON FD.id_descanso = LD.id_descanso
                WHERE {$str_where}
                ORDER BY inicio;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($year))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_direccion_empleado($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT dir_nominal, id_dirp, dir_funcional, id_dirf
                FROM xxx_rrhh_Ficha F
                WHERE F.id_persona = ?;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_permisos_empleado($id_persona, $year, $month, $year1, $month1)
    {
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT convert(varchar, P.fecha_inicio, 105) as inicio, convert(varchar, P.fecha_fin, 105) as fin, C.nombre as motivo, observaciones, P.autoriza, P.goce, rh.nombre
                FROM tbl_control_permisos P
                LEFT JOIN tbl_catalogo_permisos C ON P.id_catalogo = C.id_catalogo
                LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] AS rh ON P.autoriza = rh.id_persona
                WHERE P.id_persona = ? AND P.estado=5 AND ( CONVERT(DATE, P.fecha_inicio) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE) OR CONVERT(DATE, P.fecha_fin) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE)) AND P.id_catalogo!=63 AND P.id_catalogo!=64";
        $stmt = $pdo->prepare($sql);
        // echo $sql;
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_turno_empleado($id_persona, $month, $year, $month1, $year1)
    {
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT convert(varchar, P.fecha_inicio, 105) as inicio, convert(varchar, P.fecha_fin, 105) as fin, C.nombre as motivo, observaciones, P.autoriza, P.goce, rh.nombre, P.id_catalogo
                FROM tbl_control_permisos P
                LEFT JOIN tbl_catalogo_permisos C ON P.id_catalogo = C.id_catalogo
                LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] AS rh ON P.autoriza = rh.id_persona
                WHERE P.id_persona = ? AND P.estado=5 AND ( CONVERT(DATE, P.fecha_inicio) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE) OR CONVERT(DATE, P.fecha_fin) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE)) AND C.tipo=0";
        $stmt = $pdo->prepare($sql);
        // echo $sql;
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_vacaciones_empleado($id_persona, $month, $year, $month1, $year1)
    {
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
        FROM [APP_VACACIONES].[dbo].[VACACIONES]
        WHERE emp_id=? AND est_id=5 AND ( CONVERT(DATE, vac_fch_ini) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE) OR CONVERT(DATE, vac_fch_fin) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE))";
        $stmt = $pdo->prepare($sql);
        // echo $sql;
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_vacaciones($dir)
    {
        // $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        // $date1 = "'" . $year . "-" . $month . "-" . "01'";
        // $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";
        if ($dir != 'TODOS') {
            $where = "AND F.dir_general ='" . $dir . "'";
        } else {
            $where = "";
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT V.*, F.nombre, F.dir_general
        FROM [APP_VACACIONES].[dbo].[VACACIONES] V
		LEFT OUTER JOIN[SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id=F.id_persona
        WHERE est_id=5 AND F.estado=1" . $where;
        $stmt = $pdo->prepare($sql);
        // echo $sql;
        if ($stmt->execute(array())) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_vacaciones_dir($dir)
    {
        // $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        // $date1 = "'" . $year . "-" . $month . "-" . "01'";
        // $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT V.*, F.nombre, F.dir_general
        FROM [APP_VACACIONES].[dbo].[VACACIONES] V
		LEFT OUTER JOIN[SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id=F.id_persona
        WHERE est_id=5 AND F.estado=1 AND F.id_direc=?";
        $stmt = $pdo->prepare($sql);
        // echo $sql;
        if ($stmt->execute(array($dir))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_comision_empleado($id_persona, $month, $year, $month1, $year1)
    {
        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year1 . "-" . $month1 . "-" . $saladdays . "'";

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_status, id_empleado, vtnd.fecha_salida, vtnd.fecha_regreso, nro_frm_vt_liq
          FROM [SAAS_APP].[dbo].[vt_nombramiento] vtn
          INNER JOIN [SAAS_APP].[dbo].[vt_nombramiento_detalle] vtnd ON vtn.vt_nombramiento = vtnd.vt_nombramiento
          WHERE id_status=940 AND nro_frm_vt_liq !=0 AND id_empleado = ? AND ( CONVERT(DATE, vtnd.fecha_salida) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE) OR CONVERT(DATE, vtnd.fecha_regreso) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE))";
        $stmt = $pdo->prepare($sql);
        // echo $sql;
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_empleados_for_permiso()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT F.id_persona, F.nombre, F.p_funcional, F.p_nominal, --P.id_status,
                CASE WHEN F.id_tipo = 2 THEN '031' WHEN F.id_tipo = 3 THEN '029' ELSE '011' END AS renglon, F.estado, F.id_tipo, F.dir_nominal, F.dir_funcional,
                CASE WHEN F.id_tipo = 2 THEN F.dir_funcional WHEN F.id_tipo = 4 THEN 'APOYO' ELSE F.dir_nominal END AS direccion
                FROM xxx_rrhh_Ficha F
                --LEFT JOIN rrhh_empleado E ON F.id_persona = E.id_persona
                --LEFT JOIN rrhh_empleado_plaza P ON P.id_empleado = E.id_empleado
                WHERE F.estado = 1 AND F.id_tipo IN (1,2)--AND P.id_status = 891 ;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_empleados_by_dir($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT F.id_persona, F.nombre, F.p_funcional, F.p_nominal, P.id_status,
                CASE WHEN F.id_tipo = 2 THEN '031' WHEN F.id_tipo = 3 THEN '029' ELSE '011' END AS renglon, F.estado, F.id_tipo, F.dir_nominal, F.dir_funcional,
                CASE WHEN F.id_tipo = 2 THEN F.dir_funcional WHEN F.id_tipo = 4 THEN 'APOYO' ELSE F.dir_nominal END AS direccion
                FROM xxx_rrhh_Ficha F
                LEFT JOIN rrhh_empleado E ON F.id_persona = E.id_persona
                LEFT JOIN rrhh_empleado_plaza P ON P.id_empleado = E.id_empleado
                WHERE F.estado = 1 AND P.id_status = 891  AND F.id_tipo IN (1,2) AND id_direc = (SELECT id_direc FROM xxx_rrhh_Ficha WHERE id_persona = ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_persona))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    static function get_reporte_solicitudes($tipo, $dir, $subsec, $sec, $fff1, $fff2)
    {

        if (!is_numeric($tipo) == 1) {
            $tipo = 3;
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT V.vac_id,
                                F.id_persona,
                                V.emp_pue,
                                CD.descripcion AS pue_des,
                                F.nombre,
                                F.dir_funcional,
                                V.emp_dir,
                                D.descripcion AS dir_des,
                                V.vac_fch_sol,
                                V.vac_fch_ini,
                                V.vac_fch_fin,
                                V.vac_sol,
                                V.vac_fch_pre,
                                A.anio_des,
                                EV.est_id,
                                EV.est_des,
        CASE WHEN DATEDIFF(day, GETDATE(), V.vac_fch_pre) >=1 THEN
		datediff(dd, GETDATE(), V.vac_fch_pre) - (datediff(wk, GETDATE(), V.vac_fch_pre) * 2) -
		case when datepart(dw, GETDATE()) = 1 then 1 else 0 end +
		case when datepart(dw, V.vac_fch_pre) = 1 then 1 else 0 end
		ELSE 0 END AS diares
        FROM [app_vacaciones].[dbo].[VACACIONES] V
        INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
		INNER JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON V.emp_dir=D.id_direccion
		INNER JOIN [SAAS_APP].[dbo].[tbl_catalogo_detalle] CD ON V.emp_pue=CD.id_item
        ";

        //         $sql = "SELECT DISTINCT V.vac_id, F.id_persona, F.nombre, F.dir_funcional, V.vac_fch_sol, V.vac_fch_ini, V.vac_fch_fin, V.vac_sol, V.vac_fch_pre, A.anio_des, EV.est_id, EV.est_des
        // ,CASE WHEN DATEDIFF(day, GETDATE(), V.vac_fch_pre) >=1 THEN DATEDIFF(day, GETDATE(), V.vac_fch_fin)+1 ELSE 0 END AS diares
        // FROM [app_vacaciones].[dbo].[VACACIONES] V
        // INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        // INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        // INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        // INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
        // ";

        $sql .= "WHERE CONVERT(VARCHAR, V.vac_fch_ini, 23) BETWEEN $fff1 AND $fff2";

        if ($dir != 0) {
            $sql .= " AND F.id_direc =" . $dir;
        } else if ($subsec != 0) {
            $sql .= "AND F.id_direc = 0 AND F.id_subsecre =" . $subsec;
        } else if ($sec != 1) {
            $sql .= "AND F.id_direc = 0 AND F.id_subsecre = 0 AND F.id_subsecre = 4";
        } else {
            $sql .= "";
        }
        $sql .= "ORDER BY V.vac_id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $vacaciones = $stmt->fetchAll();
        Database::disconnect_sqlsrv();
        return $vacaciones;
    }
    function get_autoriza_for_permiso()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT(F.id_persona)
        ,F.nombre
        ,F.p_funcional
        ,F.p_nominal

        ,CASE WHEN F.id_tipo = 2 THEN '031'
            WHEN F.id_tipo = 3 THEN '029'  ELSE '011' END           AS renglon
        ,F.estado
        ,F.id_tipo
        ,F.dir_nominal
        ,F.dir_funcional
        ,CASE WHEN F.id_tipo = 2 THEN F.dir_funcional
            WHEN F.id_tipo = 4 THEN 'APOYO'  ELSE F.dir_nominal END AS direccion
            FROM xxx_rrhh_Ficha F
            LEFT JOIN rrhh_empleado E
            ON F.id_persona = E.id_persona
            LEFT JOIN rrhh_empleado_plaza P
            ON P.id_empleado = E.id_empleado
            WHERE F.id_persona IN (30,32,52 ,88 ,102 ,108 ,117 ,135 ,149 ,166 ,177 ,279 ,456 ,523 ,614 ,6640 ,7399 ,8341 ,8347 ,8349 ,8351 ,8352 ,8354 ,8357 ,8358 ,8362 ,8366
              ,8370 ,8423 ,8511, 7370, 8433, 8423, 8449, 501, 8566, 8344,8594,8835,4,876,5170,1177,8892,452, 524,355,8789);";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_empleados_by_permiso($id_direccion, $id_catalogo)
    {
        $year = date("Y");
        if ($id_catalogo == 11 || $id_catalogo == 12 || $id_catalogo == 13) {
            $str_where = "AND F.id_genero = 819 AND F.id_persona NOT IN (SELECT id_persona
                                                    FROM tbl_control_permisos
                                                    WHERE  YEAR(fecha_inicio) = {$year} AND id_catalogo = {$id_catalogo})";
        } else {
            $str_where = "";
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT F.id_persona, F.nombre, F.p_funcional, F.p_nominal, P.id_status,
                CASE WHEN F.id_tipo = 2 THEN '031' WHEN F.id_tipo = 3 THEN '029' ELSE '011' END AS renglon, F.estado, F.id_tipo, F.dir_nominal, F.dir_funcional,
                CASE WHEN F.id_tipo = 2 THEN F.dir_funcional WHEN F.id_tipo = 4 THEN 'APOYO' ELSE F.dir_nominal END AS direccion
                FROM xxx_rrhh_Ficha F
                LEFT JOIN rrhh_empleado E ON F.id_persona = E.id_persona
                LEFT JOIN rrhh_empleado_plaza P ON P.id_empleado = E.id_empleado
                WHERE F.estado = 1 AND P.id_status = 891 AND F.id_dirf = ? {$str_where};";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_direccion))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_horario_diario($fecha, $direccion)
    {
        if ($direccion == null) {
            $str_where = "";
        } else {
            $str_where = "WHERE F.id_dirf = {$direccion}";
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT(F.id_persona), F.nombre, F.dir_funcional AS dfuncional,
                CASE WHEN F.p_contrato IS NOT NULL THEN F.p_contrato ELSE p_funcional END pfuncional, entrada, S.entra_alm, S.sale_alm, T.salida, C.cuenta,
              R.id_punto AS punto_entrada, T.id_punto AS punto_salida, F.p_nominal
                FROM xxx_rrhh_Ficha F
                INNER JOIN(
                  SELECT id_persona, COUNT(id_persona) as cuenta
                  FROM tbl_control_ingreso t WHERE t.id_punto IN (2,3,4,5,6) AND convert(varchar, t.fecha, 23) = ?
                  GROUP BY id_persona
                ) AS C ON C.id_persona = F.id_persona
                LEFT JOIN (SELECT T.entrada, T.id_persona, T.id_punto
                  FROM
                   (
                     SELECT CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END entrada, id_persona, fecha, id_punto,
                     ROW_NUMBER() OVER (PARTITION BY id_persona ORDER BY fecha ASC) AS rnk FROM tbl_control_ingreso t
                     WHERE t.id_punto IN (2,3,4,6) AND convert(varchar, t.fecha, 23) = ?
                   ) T
                   WHERE T.rnk = 1
                 ) AS R ON R.id_persona = F.id_persona

                LEFT JOIN ( SELECT MIN(almuerzo) entra_alm, MAX(almuerzo) sale_alm,  id_persona
                    FROM (
                      SELECT
                      CASE WHEN convert(time,t.fecha) between '11:00:00' AND '14:59:59' THEN convert(time,t.fecha) END almuerzo, id_persona, fecha
                      FROM tbl_control_ingreso t WHERE t.id_punto IN (2,6) AND convert(varchar, t.fecha, 23) = ?) as H GROUP BY H.id_persona
                    ) as S ON S.id_persona = F.id_persona

                    LEFT JOIN (SELECT T.id_punto, T.salida, T.id_persona
                      FROM
                       (
                         SELECT CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END salida, id_persona, fecha, id_punto,
                         ROW_NUMBER() OVER (PARTITION BY id_persona ORDER BY fecha DESC) AS rnk FROM tbl_control_ingreso t
                         WHERE t.id_punto IN (2,3,5,6)  AND convert(varchar, t.fecha, 23) = ?
                       ) T
                       WHERE T.rnk = 1
                     ) AS T ON T.id_persona = F.id_persona
                     ORDER BY R.entrada DESC;";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute(array($fecha, $fecha, $fecha, $fecha))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function save_descanso($data, $opcion)
    {

        // echo $data[2];
        $data[2] = (date("Y-d-m H:i:s", strtotime($data[2])));
        $data[3] = (date("Y-d-m H:i:s", strtotime($data[3])));
        // echo $data[2];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($opcion == "1") {
            $sql = "INSERT INTO tbl_fechas_descansos VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(),1)";
        } else if ($opcion == "2") {
            if (sizeof($data) == 5) {
                $sql = "INSERT INTO tbl_control_permisos VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(), '" . $data[4] . "', NULL)";
            } else {
                $sql = "INSERT INTO tbl_control_permisos VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(), NULL, NULL )";
            }
        }
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($data[0], $data[1], $data[2], $data[3], $_SESSION["id_persona"], $_SESSION["id_persona"]))) {
            $response = array(
                "status" => 200,
                "msg" => ""
            );
        } else {
            $response = array(
                "status" => 400,
                "msg" => "error"
            );
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_permisos($tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_catalogo_permisos WHERE estado = 1 AND tipo=" . $tipo;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_id_permisos($id_tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT nombre FROM tbl_catalogo_permisos WHERE estado = 1 AND id_catalogo = ?;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_tipo))) {
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_empleado_permiso($direccion, $id_tipo, $month, $year)
    {
        // echo $id_tipo;
        if ($month != 0) {
            $saladdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $date1 = "'" . $year . "-" . $month . "-" . "01'";
            $date2 = "'" . $year . "-" . $month . "-" . $saladdays . "'";
            $where = " WHERE CONVERT(DATE,fecha_inicio) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE) AND tipo=" . $id_tipo;
        } else {
            $where = "WHERE tipo=" . $id_tipo;
        }

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM [SAAS_APP].[dbo].[tbl_control_permisos] cop
                JOIN [SAAS_APP].[dbo].[tbl_catalogo_permisos] cap ON cop.id_catalogo=cap.id_catalogo
                JOIN [APP_VACACIONES].[dbo].[ESTADO_VACACIONES] EV ON cop.estado=EV.est_id
                " . $where . "
                ORDER BY id_control DESC";

        // echo $sql;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_horarios_fijos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM [SAAS_APP].[dbo].[tbl_control_horario]";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }




}
$tipo_control = array(1 => '5a. Peatonal', 2 => 'Principal', 3 => 'Academia', 4 => 'Ingreso', 5 => 'Egreso', 6 => 'RRHH');

function getPuestoEmpleado($id_persona,$fecha_comision){
 //inicio
 $response = '';
 $pdo = Database::connect_sqlsrv();
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $sql = "SELECT TOP 1  c.fecha_toma_posesion AS fecha_inicio, g.descripcion AS pueston,
         substring(f.partida_presupuestaria,27,5)+substring(f.partida_presupuestaria,57,5) as partida, sd.sueldo,
         e.descripcion AS direccion, e.id_direccion, h.descripcion AS puesto_funcional
         FROM rrhh_empleado b
         LEFT JOIN rrhh_empleado_plaza c ON c.id_empleado = b.id_empleado
         LEFT JOIN SAAS_APP.dbo.rrhh_asignacion_puesto_historial_detalle d ON d.id_asignacion = c.id_asignacion
         LEFT JOIN SAAS_APP.dbo.rrhh_direcciones e ON d.direccion_f = e.id_direccion
         INNER JOIN SAAS_APP.dbo.rrhh_plaza f ON c.id_plaza = f.id_plaza
         INNER JOIN SAAS_APP.dbo.rrhh_plazas_puestos g ON g.id_puesto = f.id_puesto
         LEFT JOIN (SELECT c.monto_p AS sueldo, b.id_plaza
           FROM rrhh_plazas_sueldo b
           INNER JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo
           INNER JOIN rrhh_plazas_sueldo_conceptos d ON c.id_concepto = d.id_concepto
           WHERE  b.actual =1 AND d.aplica_plaza = 1 AND c.id_concepto = 1

         ) AS sd ON sd.id_plaza = c.id_plaza
     LEFT JOIN tbl_catalogo_detalle h ON d.puesto_f = h.id_item
         WHERE b.id_persona = ? AND CONVERT(VARCHAR,d.fecha_inicio,23) <= ? AND d.status <> 3
         ORDER BY c.id_asignacion DESC, d.reng_num DESC;

         --ORDER BY d.fecha_inicio DESC";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($id_persona,$fecha_comision));
 $response =  $stmt->fetch(PDO::FETCH_ASSOC);
 Database::disconnect_sqlsrv();
 return $response;
 //fin
}
function getContratoEmpleado($id_persona,$fecha_comision){
 //inicio
 $response = '';
 $pdo = Database::connect_sqlsrv();
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $sql = "SELECT TOP 1 cnt.tipo_contrato,
        CASE WHEN tipo_contrato=8 THEN '031' ELSE
        CASE WHEN tipo_contrato=9 THEN '029' ELSE ' ' END END Renglon,
        e.id_persona, cnt.id_empleado, e.nombre_completo, cnt.reng_num, cnt.nro_contrato, cnt.nro_acuerdo_aprobacion, cnt.fecha_acuerdo_aprobacion,
        cnt.fecha_contrato, e.fecha_ingreso, cnt.fecha_inicio, cnt.fecha_finalizacion, cnt.monto_contrato, cnt.monto_mensual, cnt.fecha_acuerdo_resicion, cnt.fecha_efectiva_resicion,
          dir.descripcion AS direccion, cd.descripcion AS pueston,cnt.id_direccion_servicio, '031' AS partida, 2425.80 AS sueldo,
          dir.id_direccion

        FROM dbo.xxx_rrhh_empleado_persona e LEFT JOIN
        dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = e.id_empleado LEFT JOIN

        dbo.rrhh_direcciones dir ON cnt.id_direccion_servicio = dir.id_direccion
          LEFT JOIN
            dbo.tbl_catalogo_detalle cd ON cnt.id_puesto_servicio = cd.id_item
        WHERE
        e.id_persona=? AND CONVERT(VARCHAR,fecha_inicio,23) <= ?
        ORDER BY cnt.reng_num DESC, cnt.tipo_contrato DESC;

         --ORDER BY d.fecha_inicio DESC";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($id_persona,$fecha_comision));
 $response =  $stmt->fetch(PDO::FETCH_ASSOC);
 Database::disconnect_sqlsrv();
 return $response;
 //fin
}

function getApoyoEmpleado($id_persona){
 //inicio
 $response = '';
 $pdo = Database::connect_sqlsrv();
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $sql = "SELECT a.id_persona, a.id_cargo, b.descripcion AS puesto, CASE WHEN LEN(a.partida_presupuestaria) > 10 THEN substring(a.partida_presupuestaria,27,5)+substring(a.partida_presupuestaria,57,5)
         ELSE a.partida_presupuestaria END AS
         partida, a.salario_base AS sueldo
         FROM rrhh_persona_apoyo a
         INNER JOIN tbl_catalogo_detalle b ON a.id_cargo = b.id_item
         WHERE id_persona = ?;

         --ORDER BY d.fecha_inicio DESC";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($id_persona));
 $response =  $stmt->fetch(PDO::FETCH_ASSOC);
 Database::disconnect_sqlsrv();
 return $response;
 //fin
}
