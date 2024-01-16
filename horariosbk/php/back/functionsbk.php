<?php
class Boleta
{
    function set_estado_badge($est_id, $est_des)
    {
        switch ($est_id) {
            case 1:
                $estado = '<span class="badge badge-soft-info">' . $est_des . '</span>';
                break;

            case 2:
                $estado = '<span class="badge badge-soft-secondary">' . $est_des . '</span>';
                break;

            case 3:
                $estado = '<span class="badge badge-soft-warning">' . $est_des . '</span>';
                break;

            case 4:
                $estado = '<span class="badge badge-soft-danger">' . $est_des . '</span>';
                break;

            case 5:
                $estado = '<span class="badge badge-soft-success">' . $est_des . '</span>';
                break;

            case 6:
                $estado = '<span class="badge badge-soft-warning">' . $est_des . '</span>';
                break;

            case 7:
                $estado = '<span class="badge badge-soft-danger">' . $est_des . '</span>';
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
    function get_boleta_by_id($vac_id)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT  D.*
		,V.dia_id, F.id_persona, V.vac_id, V.vac_fch_tra, V.vac_fch_sol, V.vac_fch_ini, V.vac_fch_fin, V.vac_fch_pre, A.anio_des, EV.est_id, EV.est_des, vac_dia, vac_dia_goz, vac_sub, vac_sol, vac_pen, vac_obs, dia_est, nombre, dir_funcional, F.p_funcional, F.dir_general, F.id_dirg, F.id_dirfn, F.id_secre, F.id_subsecre
        FROM [app_vacaciones].[dbo].[VACACIONES] V
        INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
        INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
        INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
        INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
        LEFT JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON F.id_direc=D.id_direccion
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

    function get_all_solicitudes($tipo, $dir, $subsec, $sec)
    {
        if (!is_numeric($tipo) == 1) {
            $tipo = 3;
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT V.vac_id, F.id_persona, F.nombre, F.dir_funcional, V.vac_fch_sol, V.vac_fch_ini, V.vac_fch_fin, V.vac_sol, V.vac_fch_pre, A.anio_des, EV.est_id, EV.est_des,
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
            $sql .= "(V.est_id = 1 OR V.est_id = 2)";
        } else if ($tipo == 2) {
            $current_year = date('Y-m-d');
            $last_year = strtotime('-1 year', strtotime(date('Y-m-d')));
            $last_year = date('Y-m-d', $last_year);
            $sql .= "CONVERT(DATE, V.vac_fch_sol) BETWEEN CAST('" . $last_year . "' AS DATE) AND CAST('" . $current_year . "' AS DATE)";
        } else if ($tipo == 4) {
            $current_year = date('Y-m-d');
            $sql .= " CAST('" . $current_year . "' AS DATE) BETWEEN CONVERT(DATE, V.vac_fch_ini) AND CONVERT(DATE, V.vac_fch_fin) AND EV.est_id = 5";
        }
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
        // echo $sql;
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
                                ,[dir_funcional]
                            FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F
                            LEFT JOIN [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A ON A.emp_id=F.id_persona
                            JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
                            WHERE estado=1 AND dia_est = 1 AND F.id_direc = ?
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
    function get_empleados_all()
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT([id_persona])
                                ,[nombre]
                                ,[p_funcional]
                                ,[dir_funcional]
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

    function get_vacaciones_pendientes($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT *
                FROM [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] A
                JOIN [APP_VACACIONES].[dbo].[ANIO] Y ON Y.anio_id= A.anio_id
                WHERE dia_est = 1 AND emp_id = ?
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
                        WHERE id_punto = 2 AND CONVERT(DATE,fecha) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE)
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
                        WHERE id_punto = 2 AND CONVERT(DATE,fecha) BETWEEN CAST(" . $date1 . " AS DATE) AND CAST(" . $date2 . " AS DATE)
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
                    WHERE id_punto = 2 AND CONVERT(DATE,fecha) BETWEEN CAST(" . $date1 . "AS DATE) AND CAST(" . $date2 . " AS DATE)
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
            $response =  $stmt->fetch(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetch(PDO::FETCH_ASSOC);
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
                     WHERE t.id_punto = 2 OR t.id_punto = 3
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
                WHERE t.id_punto = ? AND t.id_persona = ? AND (t.fecha BETWEEN CONVERT(DATE," . $t1 . ") AND CONVERT(DATE," . $t2 . ") )) as H
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
        $stmt->execute(array($id_punto, $id_persona, $year, $month));
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();
        return $horarios;
    }
    function get_horarios_by_empleado($id_persona)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT tch.id_control, tch.id_persona, tch.flag_fijo, tch.fecha_ini, tch.fecha_fin, tch.hora_ini, tch.hora_fin, tch.dia, tch.estado, tch.entrada, tch.salida
        FROM (SELECT ph.id_control, ph.id_persona, ph.flag_fijo, ph.fecha_ini, ph.fecha_fin, ph.hora_ini, ph.hora_fin, ph.dia, ph.estado, ch.entrada, ch.salida
              FROM [SAAS_APP].[dbo].[tbl_persona_horario] AS ph
              LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
              WHERE id_persona=? AND estado=1) tch
        UNION
        SELECT id_control, id_persona, id_catalogo AS flag_fijo, fecha_inicio AS fecha_ini, fecha_fin, NULL AS hora_ini, NULL AS hora_fin, NULL AS dia, estado, NULL AS entrada, NULL AS salida 
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
        $sql = "SELECT tch.id_control, tch.id_persona, tch.flag_fijo, tch.fecha_ini, tch.fecha_fin, tch.hora_ini, tch.hora_fin, tch.dia, tch.estado, tch.entrada, tch.salida
        FROM (SELECT ph.id_control, ph.id_persona, ph.flag_fijo, ph.fecha_ini, ph.fecha_fin, ph.hora_ini, ph.hora_fin, ph.dia, ph.estado, ch.entrada, ch.salida
              FROM [SAAS_APP].[dbo].[tbl_persona_horario] AS ph
              LEFT JOIN  [SAAS_APP].[dbo].[tbl_control_horario] AS ch ON ph.id_horario=ch.id_horario
              WHERE id_persona=? AND estado=1) tch
        UNION
        SELECT id_control, id_persona, id_catalogo AS flag_fijo, fecha_inicio AS fecha_ini, fecha_fin, NULL AS hora_ini, NULL AS hora_fin, NULL AS dia, estado, NULL AS entrada, NULL AS salida 
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    function get_empleados_for_permiso()
    {
        $HORARIO = new Horario();
        // $id_persona = $HORARIO->get_name($_SESSION['id_persona']);
        // if(){

        // }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT F.id_persona, F.nombre, F.p_funcional, F.p_nominal, P.id_status,
                CASE WHEN F.id_tipo = 2 THEN '031' WHEN F.id_tipo = 3 THEN '029' ELSE '011' END AS renglon, F.estado, F.id_tipo, F.dir_nominal, F.dir_funcional,
                CASE WHEN F.id_tipo = 2 THEN F.dir_funcional WHEN F.id_tipo = 4 THEN 'APOYO' ELSE F.dir_nominal END AS direccion
                FROM xxx_rrhh_Ficha F
                LEFT JOIN rrhh_empleado E ON F.id_persona = E.id_persona
                LEFT JOIN rrhh_empleado_plaza P ON P.id_empleado = E.id_empleado
                WHERE F.estado = 1 AND P.id_status = 891 ;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
    //autorizar permiso
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
            WHERE F.id_persona IN (32,52 ,79 ,88 ,102 ,108 ,117 ,135 ,149 ,166 ,177 ,279 ,355 ,456 ,523 ,537 ,614 ,6640 ,7399 ,8341 ,8347 ,8349 ,8351 ,8352 ,8354 ,8356 ,8357 ,8358 ,8362 ,8366 ,8370 ,8371 ,8423 ,8428 ,8461 ,8500 ,8511, 7370, 8433, 8423, 8449, 501, 8566, 8344, 5170, 8594);";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        CASE WHEN F.p_contrato IS NOT NULL THEN F.p_contrato ELSE p_funcional END pfuncional, entrada, R.entra_alm, R.sale_alm, R.salida, C.cuenta
        FROM xxx_rrhh_Ficha F
        INNER JOIN( SELECT id_persona, COUNT(id_persona) as cuenta
                    FROM tbl_control_ingreso t WHERE t.id_punto = 2 AND convert(date, t.fecha) = ?
                    GROUP BY id_persona ) AS C ON C.id_persona = F.id_persona

        INNER JOIN ( SELECT MIN(entrada) entrada, MIN(almuerzo) entra_alm, MAX(almuerzo) sale_alm, MAX(entrada) salida, id_persona
                    FROM (
                            SELECT CASE WHEN convert(time,t.fecha) between '00:00:00' AND '23:59:59' THEN convert(time,t.fecha) END entrada,
                                   CASE WHEN convert(time,t.fecha) between '11:00:00' AND '14:59:59' THEN convert(time,t.fecha) END almuerzo, id_persona, fecha
                                   FROM tbl_control_ingreso t WHERE t.id_punto = 2 AND convert(date, t.fecha) = ?) as H GROUP BY H.id_persona) as R ON F.id_persona = R.id_persona
                                   {$str_where}
                                   ORDER BY R.entrada DESC;";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute(array($fecha, $fecha))) {
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    function get_permisos($tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_catalogo_permisos WHERE estado = 1 AND tipo=" . $tipo;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetch(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
}
