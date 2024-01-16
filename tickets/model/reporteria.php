<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

if (function_exists('verificar_session') && verificar_session() == true) :
    $permisos = array();
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8931);

    $pos = $array[3]['id_persona'];
    $permisos = array(
        'tecnico' => ($array[2]['flag_es_menu'] == 1) ? true : false,

        'jefe' => ($array[3]['flag_es_menu'] == 1) ? true : false,

        'dir' => ($array[4]['flag_es_menu'] == 1) ? true : false,

        'desarrollo' => ($array[5]['flag_es_menu'] == 1) ? true : false,
        'soporte' => ($array[6]['flag_es_menu'] == 1) ? true : false,
        'radios' => ($array[7]['flag_es_menu'] == 1) ? true : false,
    );
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

class Reporte
{
    public $ticket;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->ticket = array();
    }

    //Funcion para mandar a llamar todos los Tickets
    static function getAllReportes()
    {
        $fecha1 =  $_POST['fecha1'];
        $fecha2 = $_POST["fecha2"];
        $newDate1 = date("m/d/Y", strtotime($fecha1));
        $newDate2 = date("m/d/Y", strtotime($fecha2));

        $permisos = $GLOBALS["permisos"];
        $filtro = $_POST['filtro'];
        $id_persona = $GLOBALS['emp'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($filtro == 0) {
            if ($permisos["tecnico"] == true) {
                $sql = "SELECT count (tickets) as tickets, nombre,departamento
                from (SELECT count (distinct t.id_ticket) as tickets
                ,(f.primer_nombre+' '+ f.primer_apellido) as nombre
                , tp.descrip_corta as departamento
                FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where flag_es_menu = 1 and taud.id_pantalla IN (352,353,354) AND t.id_estado !=4
                and f.id_persona = ? and tt.estado !=0
                and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                GROUP BY (f.primer_nombre+' '+ f.primer_apellido), tp.descrip_corta,t.id_ticket) as d
                GROUP BY nombre,departamento";
                $p = $pdo->prepare($sql);
                $p->execute(array($id_persona[1]));
            } else if ($permisos["jefe"] == true) {
                if ($permisos["desarrollo"] == true) {
                    $sql = "SELECT count (distinct t.id_ticket) as tickets
                    ,(f.primer_nombre+' '+ f.primer_apellido) as nombre
                    , tp.descrip_corta as departamento
                        FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                        inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                        inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                        INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                        inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                        inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                        inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                        inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                        inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                        where flag_es_menu = 1 and taud.id_pantalla = 352 AND t.id_estado !=4 and tt.estado !=0
                        and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                        GROUP BY (f.primer_nombre+' '+ f.primer_apellido), tp.descrip_corta";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                } else if ($permisos["soporte"] == true) {
                    $sql = "SELECT count (distinct t.id_ticket) as tickets
                    ,(f.primer_nombre+' '+ f.primer_apellido) as nombre
                    , tp.descrip_corta as departamento
                        FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                        inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                        inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                        INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                        inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                        inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                        inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                        inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                        inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                        where flag_es_menu = 1 and taud.id_pantalla = 353 AND t.id_estado !=4 and tt.estado !=0
                        and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                        GROUP BY (f.primer_nombre+' '+ f.primer_apellido), tp.descrip_corta";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                } else if ($permisos["radios"] == true) {
                    $sql = "SELECT count (distinct t.id_ticket) as tickets
                    ,(f.primer_nombre+' '+ f.primer_apellido) as nombre
                    , tp.descrip_corta as departamento
                        FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                        inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                        inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                        INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                        inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                        inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                        inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                        inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                        inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                        where flag_es_menu = 1 and taud.id_pantalla = 354 AND t.id_estado !=4 and tt.estado !=0
                        and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                        GROUP BY (f.primer_nombre+' '+ f.primer_apellido), tp.descrip_corta";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                }
            } else if ($permisos["dir"] == true) {
                $sql = "SELECT count (distinct t.id_ticket) as tickets
                ,(f.primer_nombre+' '+ f.primer_apellido) as nombre
                , tp.descrip_corta as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla in (352,353,354) AND t.id_estado !=4 and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    GROUP BY (f.primer_nombre+' '+ f.primer_apellido), tp.descrip_corta";
                $p = $pdo->prepare($sql);
                $p->execute(array());
            }
        } else if ($filtro == 1) {
            if ($permisos["tecnico"] == true) {
                $sql = "SELECT count (distinct t.id_ticket) as tickets
                ,(t.direccion_persona_solicita) as nombre
				, t.departamento_persona_solicita as departamento
                FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where flag_es_menu = 1 and taud.id_pantalla IN (352,353,354) AND t.id_estado !=4
                and f.id_persona = ? and tt.estado !=0
				and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                group by direccion_persona_solicita, departamento_persona_solicita";
                $p = $pdo->prepare($sql);
                $p->execute(array($id_persona[1]));
            } else if ($permisos["jefe"] == true) {
                if ($permisos["desarrollo"] == true) {
                    $sql = "SELECT count (distinct t.id_ticket) as tickets
                    ,(t.direccion_persona_solicita) as nombre
                    , t.departamento_persona_solicita as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla = 352 AND t.id_estado !=4
                    and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    group by direccion_persona_solicita, departamento_persona_solicita";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                } else if ($permisos["soporte"] == true) {
                    $sql = "SELECT count (distinct t.id_ticket) as tickets
                    ,(t.direccion_persona_solicita) as nombre
                    , t.departamento_persona_solicita as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla = 353 AND t.id_estado !=4
                    and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    group by direccion_persona_solicita, departamento_persona_solicita";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                } else if ($permisos["radios"] == true) {
                    $sql = "SELECT count (distinct t.id_ticket) as tickets
                    ,(t.direccion_persona_solicita) as nombre
                    , t.departamento_persona_solicita as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla = 354 AND t.id_estado !=4
                    and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    group by direccion_persona_solicita, departamento_persona_solicita";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                }
            } else if ($permisos["dir"] == true) {
                $sql = "SELECT count (distinct t.id_ticket) as tickets
                ,(t.direccion_persona_solicita) as nombre
                , t.departamento_persona_solicita as departamento
                FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where flag_es_menu = 1 and taud.id_pantalla in (352,353,354) AND t.id_estado !=4
                and tt.estado !=0
                and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                group by direccion_persona_solicita, departamento_persona_solicita";
                $p = $pdo->prepare($sql);
                $p->execute(array());
            }
        } else if ($filtro == 2) {
            if ($permisos["tecnico"] == true) {
                $sql = "SELECT count (td.id_requerimiento) as tickets
                ,(r.nombre) as nombre
				, t.departamento_persona_solicita as departamento
                FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where flag_es_menu = 1 and taud.id_pantalla IN (352,353,354) AND t.id_estado !=4
                and f.id_persona = ? and tt.estado !=0
                and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                group by r.nombre,t.departamento_persona_solicita";
                $p = $pdo->prepare($sql);
                $p->execute(array($id_persona[1]));
            } else if ($permisos["jefe"] == true) {
                if ($permisos["desarrollo"] == true) {
                    $sql = "SELECT count (td.id_requerimiento) as tickets
                    ,(r.nombre) as nombre
                    , t.departamento_persona_solicita as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla = 352 AND t.id_estado !=4 and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    group by r.nombre,t.departamento_persona_solicita";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                } else if ($permisos["soporte"] == true) {
                    $sql = "SELECT count (td.id_requerimiento) as tickets
                    ,(r.nombre) as nombre
                    , t.departamento_persona_solicita as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla = 353 AND t.id_estado !=4 and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    group by r.nombre,t.departamento_persona_solicita";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                } else if ($permisos["radios"] == true) {
                    $sql = "SELECT count (td.id_requerimiento) as tickets
                    ,(r.nombre) as nombre
                    , t.departamento_persona_solicita as departamento
                    FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                    inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                    inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                    INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                    inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                    inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                    inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                    inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                    inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                    where flag_es_menu = 1 and taud.id_pantalla = 354 AND t.id_estado !=4 and tt.estado !=0
                    and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                    group by r.nombre,t.departamento_persona_solicita";
                    $p = $pdo->prepare($sql);
                    $p->execute(array());
                }
            } else if ($permisos["dir"] == true) {
                $sql = "SELECT count (td.id_requerimiento) as tickets
                ,(r.nombre) as nombre
				, t.departamento_persona_solicita as departamento
                FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                inner join [Tickets].[dbo].[ticket_tecnico] tt on tau.id_persona = tt.id_tecnico
                inner join xxx_rrhh_Ficha f on tt.id_tecnico = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                inner join [Tickets].[dbo].[ticket] t on td.id_ticket = t.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where flag_es_menu = 1 and taud.id_pantalla IN (352,353,354) and tt.estado !=0
                and t.fecha >= '$newDate1 00:00:00'AND t.fecha <= '$newDate2 23:59:59'
                group by r.nombre,t.departamento_persona_solicita";
                $p = $pdo->prepare($sql);
                $p->execute(array());
            }
        }

        $reporte = $p->fetchAll();
        $data = array();

        foreach ($reporte as $r) {
            $sub_array = array(
                "nombre" => $r["nombre"],
                "tickets" => $r["tickets"],
                "departamento" => $r["departamento"]
            );
            $data[] = $sub_array;
        }

        $result = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($result);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
            //Para el Datatable
        case 1:
            Reporte::getAllReportes();
            break;
    }
}
