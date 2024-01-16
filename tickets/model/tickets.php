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
        'soli' => ($array[0]['flag_es_menu'] == 1) ? true : false,

        'dirSoli' => ($array[1]['flag_es_menu'] == 1) ? true : false,

        'tecnico' => ($array[2]['flag_es_menu'] == 1) ? true : false,
        'tecnicoActua' => ($array[2]['flag_actualizar'] == 1) ? true : false,

        'jefe' => ($array[3]['flag_es_menu'] == 1) ? true : false,
        'jefeActua' => ($array[3]['flag_actualizar'] == 1) ? true : false,
        'jefeAcce' => ($array[3]['flag_acceso'] == 1) ? true : false,

        'dir' => ($array[4]['flag_es_menu'] == 1) ? true : false,
        'dirElim' => ($array[4]['flag_eliminar'] == 1) ? true : false,
        'dirActua' => ($array[4]['flag_actualizar'] == 1) ? true : false,
        'dirAcce' => ($array[4]['flag_acceso'] == 1) ? true : false,
        'dirAuto' => ($array[4]['flag_autoriza'] == 1) ? true : false,

        'desarrollo' => ($array[5]['flag_es_menu'] == 1) ? true : false,
        'soporte' => ($array[6]['flag_es_menu'] == 1) ? true : false,
        'radios' => ($array[7]['flag_es_menu'] == 1) ? true : false,
    );
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

class Ticket
{
    public $ticket;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->ticket = array();
    }

    //Funcion para mandar a llamar todos los Tickets
    static function getAllTickets()
    {
        $permisos = $GLOBALS["permisos"];
        $tck = "#TCK";
        $filtro = $_POST['filtro'];
        $id_persona = $GLOBALS['emp'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Permiso Solicitante
        if ($permisos["soli"] == true) {
            $sql = "SELECT DISTINCT [id_ticket]
            ,f.nombre as persona_solicita
            ,[detalle]
            ,[fecha]
            ,e.estado
            ,t.id_estado
            FROM [Tickets].[dbo].[Ticket] as t
            inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
            ";

            if ($filtro == 0) {
                $sql .= "WHERE t.id_estado IN (0,1,2,6) and t.id_persona_solicita = ?";
            } else if ($filtro == 3) {
                $sql .= "WHERE t.id_estado in (3,5) and t.id_persona_solicita = ?";
            } else if ($filtro == 4) {
                $sql .= "WHERE t.id_estado = 4 and t.id_persona_solicita = ?";
            } else if ($filtro == 400) {
                $sql .= "WHERE t.id_estado IN (0,1,2,3,4,5,6) and t.id_persona_solicita = ?";
            }

            $sql .= " ORDER BY id_ticket DESC";
            $p = $pdo->prepare($sql);
            $p->execute(array($id_persona[1]));
        } else if ($permisos["dirSoli"] == true) {
            $sql0 = 'SELECT id_dirf from xxx_rrhh_Ficha
            where id_persona = ?';
            $p = $pdo->prepare($sql0);
            $p->execute(array($id_persona[1]));
            $dirF = $p->fetch();

            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT DISTINCT t.[id_ticket]
            ,f.nombre as persona_solicita
            ,[detalle]
            ,[fecha]
            ,e.estado
            ,t.id_estado
            FROM [Tickets].[dbo].[Ticket] as t
            inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
            ";

            if ($filtro == 0) {
                $sql .= "WHERE t.id_estado IN (0,1,2,6) and t.id_direccion_persona_solicita = ?";
            } else if ($filtro == 3) {
                $sql .= "WHERE t.id_estado in (3,5) and t.id_direccion_persona_solicita = ?";
            } else if ($filtro == 4) {
                $sql .= "WHERE t.id_estado = 4 and t.id_direccion_persona_solicita = ?";
            } else if ($filtro == 400) {
                $sql .= "WHERE t.id_estado IN (0,1,2,3,4,5,6) and t.id_direccion_persona_solicita = ?";
            }

            $sql .= " ORDER BY id_ticket DESC";
            $p = $pdo->prepare($sql);
            $p->execute(array($dirF["id_dirf"]));
        } else if ($permisos["tecnico"] == true) {
            $id_departamento = "";
            if ($permisos["desarrollo"] == true) {
                $id_departamento = 60;
            } else if ($permisos["soporte"] == true) {
                $id_departamento = 65;
            } else if ($permisos["radios"] == true) {
                $id_departamento = 90;
            } else {
                $id_departamento = "";
            }

            $sql = "";

            if ($filtro == 0) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                WHERE t.id_estado in (0,1,2,6) and t.id_persona_solicita = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where  t.id_estado = 1 and r.id_departamento = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado = 2 and r.id_tecnico = ?
                    
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado = 6 and r.id_tecnico = ?";
                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $id_departamento, $_SESSION['id_persona'], $_SESSION['id_persona']));
            } else if ($filtro == 3) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (3,5) and t.id_persona_solicita = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado in (3,5) and r.id_tecnico = ?";
                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $_SESSION['id_persona']));
            } else if ($filtro == 4) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                WHERE t.id_estado = 4 and t.id_persona_solicita = ?";
                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona']));
            } else if ($filtro == 400) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                WHERE t.id_estado in (0,1,2,6) and t.id_persona_solicita = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where  t.id_estado = 1 and r.id_departamento = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado = 2 and r.id_tecnico = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado = 6 and r.id_tecnico = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (3,5) and t.id_persona_solicita = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado in (3,5) and r.id_tecnico = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado = 4 and t.id_persona_solicita = ?
                ";

                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $id_departamento, $_SESSION['id_persona'], $_SESSION['id_persona'], $_SESSION['id_persona'], $_SESSION['id_persona'], $_SESSION['id_persona']));
            }
        } else if ($permisos["jefe"] == true) {
            $id_departamento = "";
            $departamento = "";
            if ($permisos["desarrollo"] == true) {
                $id_departamento = 60;
                $departamento = "DEPARTAMENTO DE DESARROLLO DE APLICACIONES";
            } else if ($permisos["soporte"] == true) {
                $id_departamento = 65;
                $departamento = "DEPARTAMENTO DE INFORMATICA";
            } else if ($permisos["radios"] == true) {
                $id_departamento = 90;
                $departamento = "DEPARTAMENTO DE RADIOCOMUNICACIONES";
            } else {
                $id_departamento = "";
                $departamento = "";
            }
            $sql = "SELECT DISTINCT t.[id_ticket]
            ,f.nombre as persona_solicita
            ,t.[detalle]
            ,t.[fecha]
            ,e.estado
            ,t.id_estado
            FROM [Tickets].[dbo].[Ticket] as t
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
            inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado ";

            if ($filtro == 0) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (0,1,2,6) and t.id_persona_solicita = ?
                /**La funcion de arriba es para traer sus Tickets */

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where  t.id_estado in (0,1,2,6) and r.id_departamento = ?
                /**La funcion de arriba es para traer sus Tickets */

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (0,1,2,6) and t.departamento_persona_solicita = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                WHERE t.id_estado in (0,1,2,6) and r.id_tecnico = ?";

                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $id_departamento, $departamento, $_SESSION['id_persona']));
            } else if ($filtro == 3) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (3,5) and t.id_persona_solicita = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where  t.id_estado IN(3,5) and r.id_departamento = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (3,5) and t.departamento_persona_solicita = ?
                
                union
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado in (3,5) and r.id_tecnico = ?";

                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $id_departamento, $departamento, $_SESSION['id_persona']));
            } else if ($filtro == 4) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado = 4 and t.id_persona_solicita = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where  t.id_estado = 4 and r.id_departamento = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado = 4 and t.departamento_persona_solicita = ?";
                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $id_departamento, $departamento));
            } else if ($filtro == 400) {
                $sql = "SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (0,1,2,3,5,6,7) and t.id_persona_solicita = ?
                
                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                where  t.id_estado in (0,1,2,3,5,6,7) and r.id_departamento = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado 
                WHERE t.id_estado in (0,1,2,3,5,6,7) and t.departamento_persona_solicita = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado in(3,5) and r.id_tecnico = ?

                UNION
                SELECT DISTINCT t.[id_ticket]
                ,f.nombre as persona_solicita
                ,t.[detalle]
                ,t.[fecha]
                ,e.estado
                ,t.id_estado
                FROM [Tickets].[dbo].[Ticket] as t
                inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
                inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
                inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
                inner join [Tickets].[dbo].[ticket_tecnico] r on td.id_correlativo = r.id_correlativo
                where  t.id_estado in(1,2,6) and r.id_tecnico = ?
                ";

                $sql .= " ORDER BY id_ticket DESC";
                $p = $pdo->prepare($sql);
                $p->execute(array($_SESSION['id_persona'], $id_departamento, $departamento, $_SESSION['id_persona'], $_SESSION['id_persona']));
            }
        } else if ($permisos["dir"] == true) {
            $sql = "SELECT DISTINCT t.[id_ticket]
            ,f.nombre as persona_solicita
            ,t.[detalle]
            ,t.[fecha]
            ,e.estado
            ,t.id_estado
            FROM [Tickets].[dbo].[Ticket] as t
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
            inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado ";

            if ($filtro == 0) {
                $sql .= "WHERE t.id_estado IN (0,1,2,6)";
            } else if ($filtro == 3) {
                $sql .= "WHERE t.id_estado in (3,5)";
            } else if ($filtro == 4) {
                $sql .= "WHERE t.id_estado = 4";
            } else if ($filtro == 400) {
                $sql .= "WHERE t.id_estado IN (0,1,2,3,4,5,6)";
            }

            $sql .= " ORDER BY id_ticket DESC";
            $p = $pdo->prepare($sql);
            $p->execute(array());
        }

        $ticket = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        $accion = '<a id="actions1Invoker1" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown">
        <i class="fa fa-sliders-h"></i></a>
        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker1"
        style="margin-right:20px">
        <div class="card overflow-hidden" style="margin-top:-20px;">
        <div class="card-header d-flex align-items-center py-3">
        <h2 class="h4 card-header-title">Opciones:
        </h2>
        </div>
        <div  class="card-body animacion_right_to_left" style="padding: 0rem;">
        <div>
        <ul class="list-unstyled mb-0">';

        $pendiente = '<div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 0%">
        </div>
        </div>';

        $abierto = '<div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 33%">
        </div>
        </div>';

        $asignado = '<div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 66%">
        </div>
        </div>';

        $finalizado = '<div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 100%">
        </div>
        </div>';

        $anulado = '<div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 100%">
        </div>
        </div>';

        $calificado = '<div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 100%"></div>
        </div>';


        foreach ($ticket as $t) {
            $tecnicos = Ticket::getTecnicosAsignados2($t["id_ticket"]);

            $detalle = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="showModal2(2,' . $t["id_ticket"] . ')">
            <i class="fa fa-print mr-2"></i> Detalle Ticket
            </a>
            </li>';

            $aprobarTicket = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="aprobar(4,' . $t["id_ticket"] . ')" >
            <i class="fa fa-check-square mr-2"></i> Aprobar Ticket
            </a>
            </li>';

            $rechazarTicket = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="rechazar(4,' . $t["id_ticket"] . ')" >
            <i class="fa fa-window-close mr-2"></i> Rechazar Ticket
            </a>
            </li>';

            $asignarTecnicos = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="showModal2(3,' . $t["id_ticket"] . ')">
            <i class="fa fa-print mr-2"></i> Asignar Técnicos
            </a>
            </li>';

            $asignarTecnicosD = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="showModal2(4,' . $t["id_ticket"] . ')">
            <i class="fa fa-print mr-2"></i> Asignar Técnicos
            </a>
            </li>';

            $reAsignarTecnicos = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="reAsginarTicket(4,' . $t["id_ticket"] . ')">
            <i class="fa fa-print mr-2"></i> Re-Asignar Técnicos
            </a>
            </li>';

            $asignarTicket = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="asignarTicket(2,' . $t["id_ticket"] . ')" >
            <i class="fa fa-check-square mr-2"></i> Asignar Ticket
            </a>
            </li>';

            $finalizarTicket = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="terminarTicket(3,' . $t["id_ticket"] . ')" >
            <i class="fa fa-check-square mr-2"></i> Finalizar Requerimiento
            </a>
            </li>';

            $calificarTicket = '<li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" onclick="calificarTicket(5,' . $t["id_ticket"] . ')">
            <i class="fa fa-star mr-2"></i> Calificar Ticket
            </a>
            </li>';

            switch ($permisos) {
                    //Permiso Solicitante
                case $permisos["soli"]:
                    if ($t["id_estado"] == 0) {
                        //Pendiente
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => "",
                            'estado' => $t["estado"] . $pendiente,
                            'accion' => $accion . $detalle
                        );
                    }
                    //Abierto
                    if ($t["id_estado"] == 1) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => "",
                            'estado' => $t["estado"] . $abierto,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Asignado
                    if ($t["id_estado"] == 2) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Finalizado
                    if ($t["id_estado"] == 3) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $finalizado,
                            'accion' => $accion . $detalle . $calificarTicket
                        );
                    }

                    //Anulado
                    if ($t["id_estado"] == 4) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $anulado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Calificado
                    if ($t["id_estado"] == 5) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $calificado,
                            'accion' => $accion . $detalle
                        );
                    }

                    if ($t["id_estado"] == 6) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle
                        );
                    }

                    if ($t["id_estado"] == 6) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle
                        );
                    }
                    break;

                    //Permiso Director Solicitante
                case $permisos["dirSoli"]:
                    if ($t["id_estado"] == 0) {
                        //Pendiente
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => "",
                            'estado' => $t["estado"] . $pendiente,
                            'accion' => $accion . $detalle
                        );
                    }
                    //Abierto
                    if ($t["id_estado"] == 1) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => "",
                            'estado' => $t["estado"] . $abierto,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Asignado
                    if ($t["id_estado"] == 2) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Finalizado
                    if ($t["id_estado"] == 3) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $finalizado,
                            'accion' => $accion . $detalle . $calificarTicket
                        );
                    }

                    //Anulado
                    if ($t["id_estado"] == 4) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $anulado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Calificado
                    if ($t["id_estado"] == 5) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $calificado,
                            'accion' => $accion . $detalle
                        );
                    }

                    if ($t["id_estado"] == 6) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle
                        );
                    }

                    if ($t["id_estado"] == 6) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle
                        );
                    }

                    break;

                    //Permiso Tecnico
                case $permisos["tecnico"]:
                    if ($t["id_estado"] == 0) {
                        //Pendiente
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => "",
                            'estado' => $t["estado"] . $pendiente,
                            'accion' => $accion . $detalle . $aprobarTicket
                        );
                    }
                    //Abierto
                    if ($t["id_estado"] == 1) {
                        if ($permisos["tecnicoActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle . $asignarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => "",
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle
                            );
                        }
                    }

                    //Asignado
                    if ($t["id_estado"] == 2) {
                        if ($permisos["tecnicoActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle . $finalizarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle
                            );
                        }
                    }

                    //Finalizado
                    if ($t["id_estado"] == 3) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $finalizado,
                            'accion' => $accion . $detalle . $calificarTicket
                        );
                    }

                    //Anulado
                    if ($t["id_estado"] == 4) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $anulado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Calificado
                    if ($t["id_estado"] == 5) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $calificado,
                            'accion' => $accion . $detalle
                        );
                    }

                    if ($t["id_estado"] == 6) {
                        if ($permisos["tecnicoActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle . $finalizarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle
                            );
                        }
                    }

                    break;

                case $permisos["jefe"]:
                    if ($t["id_estado"] == 0) {
                        //Pendiente
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => "",
                            'estado' => $t["estado"] . $pendiente,
                            'accion' => $accion . $detalle . $aprobarTicket
                        );
                    }

                    //Abierto
                    if ($t["id_estado"] == 1) {
                        if ($permisos["jefeAcce"] == true && $permisos["jefeActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle . $asignarTicket . $asignarTecnicos . $rechazarTicket
                            );
                        } else if ($permisos["jefeAcce"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle . $asignarTecnicos . $rechazarTicket
                            );
                        } else if ($permisos["jefeActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle . $asignarTicket . $rechazarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle
                            );
                        }
                    }

                    //Asignado
                    if ($t["id_estado"] == 2) {

                        if ($permisos["jefeActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle . $finalizarTicket . $asignarTecnicosD . $reAsignarTecnicos . $rechazarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle
                            );
                        }
                    }

                    //Finalizado
                    if ($t["id_estado"] == 3) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $finalizado,
                            'accion' => $accion . $detalle . $calificarTicket . $rechazarTicket
                        );
                    }

                    //Anulado
                    if ($t["id_estado"] == 4) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $anulado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Calificado
                    if ($t["id_estado"] == 5) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $calificado,
                            'accion' => $accion . $detalle . $rechazarTicket
                        );
                    }

                    if ($t["id_estado"] == 6) {

                        if ($permisos["jefeActua"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle . $finalizarTicket . $asignarTecnicosD . $reAsignarTecnicos . $rechazarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $asignado,
                                'accion' => $accion . $detalle
                            );
                        }
                    }
                    break;

                case $permisos["dir"]:
                    if ($t["id_estado"] == 0) {
                        //Pendiente
                        if ($permisos["dirElim"] == true && $permisos["dirAuto"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => "",
                                'estado' => $t["estado"] . $pendiente,
                                'accion' => $accion . $detalle . $aprobarTicket . $rechazarTicket
                            );
                        } else if ($permisos["dirAuto"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => "",
                                'estado' => $t["estado"] . $pendiente,
                                'accion' => $accion . $detalle . $aprobarTicket
                            );
                        } else if ($permisos["dirElim"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => "",
                                'estado' => $t["estado"] . $pendiente,
                                'accion' => $accion . $detalle . $rechazarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => "",
                                'estado' => $t["estado"] . $pendiente,
                                'accion' => $accion . $detalle
                            );
                        }
                    }
                    //Abierto
                    if ($t["id_estado"] == 1) {
                        if ($permisos["dirAcce"] == true) {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => $tecnicos,
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle . $asignarTecnicos . $rechazarTicket
                            );
                        } else {
                            $sub_array = array(
                                'id' => $tck . $t["id_ticket"],
                                'solicitante' => $t["persona_solicita"],
                                'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                                'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                                'responsable' => "",
                                'estado' => $t["estado"] . $abierto,
                                'accion' => $accion . $detalle
                            );
                        }
                    }

                    //Asignado
                    if ($t["id_estado"] == 2) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle . $reAsignarTecnicos . $asignarTecnicos . $rechazarTicket
                        );
                    }

                    //Finalizado
                    if ($t["id_estado"] == 3) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $finalizado,
                            'accion' => $accion . $detalle . $calificarTicket . $rechazarTicket
                        );
                    }

                    //Anulado
                    if ($t["id_estado"] == 4) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $anulado,
                            'accion' => $accion . $detalle
                        );
                    }

                    //Calificado
                    if ($t["id_estado"] == 5) {
                        $sub_array = array(
                            'id' => $t["id_ticket"], 'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $calificado,
                            'accion' => $accion . $detalle . $rechazarTicket
                        );
                    }

                    if ($t["id_estado"] == 6) {
                        $sub_array = array(
                            'id' => $tck . $t["id_ticket"],
                            'solicitante' => $t["persona_solicita"],
                            'detalle' => '<div class="scrollmenu">' . $t["detalle"] . '<div>',
                            'fecha' => date('d-m-Y H:i', strtotime($t["fecha"])),
                            'responsable' => $tecnicos,
                            'estado' => $t["estado"] . $asignado,
                            'accion' => $accion . $detalle . $reAsignarTecnicos . $asignarTecnicos . $rechazarTicket
                        );
                    }
                    break;
            }
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

    // Opcion 2
    public static function getDireccion()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($_GET["idDir"] == 000) {
            $sql = "SELECT descripcion, id_direccion
            FROM rrhh_direcciones d
            WHERE (id_nivel = 4)
			union 
            SELECT descripcion, id_direccion dd
            FROM rrhh_direcciones
            WHERE (id_nivel = 3 and id_direccion in (14,15))";
            $p = $pdo->prepare($sql);
            $p->execute(array($_GET["idDir"]));
            $direcciones = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();
        } else {
            $sql = "SELECT descripcion, id_direccion
            FROM rrhh_direcciones
            WHERE (id_nivel = 4 AND id_direccion = ? )";
            $p = $pdo->prepare($sql);
            $p->execute(array($_GET["idDir"]));
            $direcciones = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();
        }

        foreach ($direcciones as $d) {

            $sub_array = array(
                "id_direccion" => $d["id_direccion"],
                "nombre" => $d["descripcion"],
            );

            $data[] = $sub_array;
        }

        echo json_encode($data);
    }

    // Devuelve los Departamentos dentro las direcciones
    public static function getDepartamento()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($_GET["tipo"] == 1) {
            $id_persona = $GLOBALS['emp'];
            $sql = "SELECT distinct rde.id_departamento
            ,rdi.[descripcion] as nombre_direccion
            ,rde.[descripcion]
            FROM [SAAS_APP].[dbo].[rrhh_direcciones] rdi
            inner join [SAAS_APP].[dbo].[rrhh_subdirecciones] rsu on rdi.id_direccion = rsu.id_direccion
            inner join [SAAS_APP].[dbo].[rrhh_departamentos] rde on rsu.id_subdireccion = rde.id_subdireccion
            where id_nivel = 4 and rdi.descripcion=?";
            $p = $pdo->prepare($sql);
            $p->execute(array($id_persona[10]));
        } else if ($_GET["tipo"] == 2) {
            $sql = "SELECT distinct rde.id_departamento
                ,rdi.[descripcion] as nombre_direccion
                ,rde.[descripcion]
                FROM [SAAS_APP].[dbo].[rrhh_direcciones] rdi
                inner join [SAAS_APP].[dbo].[rrhh_subdirecciones] rsu on rdi.id_direccion = rsu.id_direccion
                inner join [SAAS_APP].[dbo].[rrhh_departamentos] rde on rsu.id_subdireccion = rde.id_subdireccion
                where id_nivel = 4 and rdi.id_direccion =?";
            $p = $pdo->prepare($sql);
            $p->execute(array($_GET["id"]));
        }

        $departamento = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($departamento as $d) {

            $sub_array = array(
                "id_departamento" => $d["id_departamento"],
                "nombre" => $d["descripcion"]
            );

            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    // Devuelve los Requerimiento dentro los departamentos
    // opcion 4
    public static function getRequerimientos()
    {

        $campo = 'id_direccion';
        $valor = $_GET['requerimientos'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT [id_requerimiento]
        ,[nombre]
        ,[descripcion]
        ,[id_departamento]
        ,[id_direccion]
        ,[id_subdireccion]
        FROM [Tickets].[dbo].[Requerimiento]
        WHERE $campo = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($valor));
        $requerimiento = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($requerimiento as $d) {

            $sub_array = array(
                "id_requerimiento" => $d["id_requerimiento"],
                "nombre" => $d["nombre"],
                "descripcion" => $d["descripcion"]
            );

            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    //opcion 5
    public static function setTicketNuevo()
    {
        $direcciones = $_GET['direcciones'];
        $departamentos = $_GET['departamentos'];
        $requerimientos = $_GET['requerimientos'];
        $descripcion = $_GET['descripcion'];
        $fecha = date('Y-m-d H:i:s');
        $id_persona = $GLOBALS['emp'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $yes = '';

        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $estado = 1;
            if ($_GET["tipo"] == 1) {
                $sql0 = 'SELECT id_dirf from xxx_rrhh_Ficha
                where id_persona = ?';
                $p = $pdo->prepare($sql0);
                $p->execute(array($id_persona[1]));
                $dirF = $p->fetch();

                $sql = "INSERT INTO [Tickets].[dbo].[Ticket] (id_persona_solicita, id_estado, detalle, fecha, id_direccion_solicitada,id_departamento_persona_solicita,id_direccion_persona_solicita)
                VALUES (?,?,?,?,?,?,?)";
                $p = $pdo->prepare($sql);
                $p->execute(array($id_persona[1], $estado,  $descripcion, $fecha, $direcciones, $departamentos, $dirF["id_dirf"]));
                foreach ($requerimientos as $r) {
                    $sql2 = "DECLARE @MAXRECORD INT = (SELECT TOP 1 id_ticket FROM [Tickets].[dbo].[Ticket] ORDER BY id_ticket DESC) 
                    INSERT INTO [Tickets].[dbo].[ticket_detalle] (id_ticket, id_requerimiento, id_estado, fecha)
                    VALUES (@MAXRECORD,?,?,?)";
                    $p = $pdo->prepare($sql2);
                    $p->execute(array($r, $estado, $fecha));
                }
            } else if ($_GET["tipo"] == 2) {
                $estado = 6;
                $idSolicitante = $_GET["idSolicitante"];
                $dirSolicita = $_GET["dirSolicita"];
                $fechaF = date('Y-m-d H:i:s', strtotime($_GET["fechaF"]));
                $fech = date('Y-m-d H:i:s', strtotime($_GET["fecha"]));
                $sql = "INSERT INTO [Tickets].[dbo].[Ticket] (id_persona_solicita, id_direccion_persona_solicita, id_direccion_solicitada, id_estado, detalle, fecha, id_departamento_persona_solicita, fecha_terminado)
                VALUES (?,?,?,?,?,?,?,?)";
                $p = $pdo->prepare($sql);
                $p->execute(array($idSolicitante, $dirSolicita, $direcciones, $estado, $descripcion, $fech, $departamentos, $fechaF));
                $estado = 1;
                foreach ($requerimientos as $r) {
                    $sql2 = "DECLARE @MAXRECORD INT = (SELECT TOP 1 id_ticket FROM [Tickets].[dbo].[Ticket] ORDER BY id_ticket DESC) 
                    INSERT INTO [Tickets].[dbo].[ticket_detalle] (id_ticket, id_requerimiento, id_estado, fecha)
                    VALUES (@MAXRECORD,?,?,?)";
                    $p = $pdo->prepare($sql2);
                    $p->execute(array($r, $estado, $fech));
                }
            }

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Creado');
            $pdo->commit();

            if ($_GET["tipo"] == 2) {
                $pdo = Database::connect_sqlsrv();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $id = "SELECT TOP (1) [id_ticket]
                FROM [Tickets].[dbo].[Ticket]
                order by id_ticket desc";

                $p = $pdo->prepare($id);
                $p->execute();
                $i = $p->fetch(PDO::FETCH_ASSOC);
                $ids = (int)$i["id_ticket"];

                $tecnico = $GLOBALS['emp'];
                $yes = '';
                $arr_length2 = Ticket::getLongCorrelativos($ids);
                $masUno = 0;
                $pdo = Database::connect_sqlsrv();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $pdo->beginTransaction();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                for ($j = 0; $j < $arr_length2[0]; $j++) {
                    $sql = "DECLARE @MAXRECORD INT = (SELECT TOP 1 id_correlativo FROM [Tickets].[dbo].[ticket_detalle]
                            WHERE id_ticket = $ids)+$masUno
        
                            INSERT INTO [Tickets].[dbo].[ticket_tecnico] (id_correlativo, id_tecnico, id_persona_asigna, estado, fecha)
                            VALUES (@MAXRECORD,?,?,?,?)";
                    $p = $pdo->prepare($sql);
                    $p->execute(array($tecnico[1], $tecnico[1], $estado, $fech));
                    $masUno++;
                }
                $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Creado');
                $pdo->commit();
            }
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }

        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    //Opcion 6
    public static function getDetalle()
    {
        $id = $_GET['id'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT [id_ticket]
        ,[id_persona_solicita]
        ,[detalle]
        ,[fecha]
        ,[fecha_terminado]
        ,[id_direccion_persona_solicita]
        ,[id_departamento_persona_solicita]
        ,[id_direccion_solicitada]
        FROM [Tickets].[dbo].[Ticket]
        where id_ticket =  ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $pd = $p->fetch(PDO::FETCH_ASSOC);

        if ($pd["id_direccion_persona_solicita"] == "1000000") {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql1 = "SELECT f.nombre as persona_solicita
            ,[detalle]
            ,[fecha]
            ,[fecha_terminado]
            FROM [Tickets].[dbo].[Ticket] as t
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona

            where id_ticket =  ?";

            $p = $pdo->prepare($sql1);
            $p->execute(array($id));
            $d = $p->fetch(PDO::FETCH_ASSOC);

            $sql2 = "DECLARE @MAXRECORD INT = (SELECT [id_persona_solicita]
            FROM [Tickets].[dbo].[Ticket] t
            where id_ticket =  ?)

            SELECT b.persona_user
            FROM rrhh_persona_usuario b
            where id_persona =  @MAXRECORD";

            $p = $pdo->prepare($sql2);
            $p->execute(array($id));
            $c = $p->fetch(PDO::FETCH_ASSOC);

            $data = array();
            $fechaTer = $d["fecha_terminado"];
            //foreach ($detalle as $d) {

            $data = array(
                "fecha" => date('d-m-Y H:i', strtotime($d["fecha"])),
                "fecha_terminado" => (!empty($fechaTer)) ? date('d-m-Y H:i', strtotime($d["fecha_terminado"])) : $d["fecha_terminado"],
                "persona_solicita" => $d["persona_solicita"],
                "detalle" => $d["detalle"],
                "direccion_persona_solicita" => 'APOYO',
                "departamento_persona_solicita" => 'APOYO',
                "persona_user" => (empty($c["persona_user"])) ? 'Sin Correo' : $c["persona_user"]
            );
        } else  if ($pd["id_departamento_persona_solicita"] == 0) {
            $sql1 = "SELECT distinct f.nombre as persona_solicita
            ,[detalle]
            ,[fecha]
            ,[fecha_terminado]
            ,dir.descripcion as direccion_persona_solicita
            ,[id_direccion_solicitada]
            FROM [Tickets].[dbo].[Ticket] as t
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
            inner join rrhh_direcciones dir on t.id_direccion_persona_solicita = dir.id_direccion
            where id_ticket =  ?";

            $p = $pdo->prepare($sql1);
            $p->execute(array($id));
            $d = $p->fetch(PDO::FETCH_ASSOC);

            $sql2 = "DECLARE @MAXRECORD INT = (SELECT [id_persona_solicita]
            FROM [Tickets].[dbo].[Ticket] t
            where id_ticket =  ?)
    
            SELECT b.persona_user
            FROM rrhh_persona_usuario b
            where id_persona =  @MAXRECORD";

            $p = $pdo->prepare($sql2);
            $p->execute(array($id));
            $c = $p->fetch(PDO::FETCH_ASSOC);


            $sql3 = "DECLARE @MAXRECORD INT = (SELECT id_persona_solicita
            FROM [Tickets].[dbo].[Ticket] t
            where id_ticket =  ?)
    
            select nombre, dir_funcional, departamento
            from xxx_rrhh_Ficha
            where id_persona = @MAXRECORD";

            $p3 = $pdo->prepare($sql3);
            $p3->execute(array($id));
            $u = $p3->fetch(PDO::FETCH_ASSOC);


            $data = array();
            $fechaTer = $d["fecha_terminado"];
            //foreach ($detalle as $d) {

            $data = array(
                "fecha" => date('d-m-Y H:i', strtotime($d["fecha"])),
                "fecha_terminado" => (!empty($fechaTer)) ? date('d-m-Y H:i', strtotime($d["fecha_terminado"])) : $d["fecha_terminado"],
                "persona_solicita" => (!empty($d["persona_solicita"])) ? $d["persona_solicita"] : $u["nombre"],
                "detalle" => (!empty($d["detalle"])) ? $d["detalle"] : "Pendiente",
                "direccion_persona_solicita" => (!empty($d["direccion_persona_solicita"])) ? $d["direccion_persona_solicita"] : $u["dir_funcional"],
                "departamento_persona_solicita" => $d["direccion_persona_solicita"],
                "persona_user" => (empty($c["persona_user"])) ? 'Sin Correo' : $c["persona_user"]
            );
        } else {
            $sql1 = "SELECT f.nombre as persona_solicita
            ,[detalle]
            ,[fecha]
            ,[fecha_terminado]
            ,dir.descripcion as direccion_persona_solicita
            ,dep.descripcion as departamento_persona_solicita
            ,[id_direccion_solicitada]
            FROM [Tickets].[dbo].[Ticket] as t
            inner join xxx_rrhh_Ficha f on t.id_persona_solicita = f.id_persona
            inner join rrhh_direcciones dir on t.id_direccion_persona_solicita = dir.id_direccion
            inner join rrhh_departamentos dep on t.id_departamento_persona_solicita = dep.id_departamento
            where id_ticket =  ?";

            $p = $pdo->prepare($sql1);
            $p->execute(array($id));
            $d = $p->fetch(PDO::FETCH_ASSOC);

            $sql2 = "DECLARE @MAXRECORD INT = (SELECT [id_persona_solicita]
            FROM [Tickets].[dbo].[Ticket] t
            where id_ticket =  ?)
    
            SELECT b.persona_user
            FROM rrhh_persona_usuario b
            where id_persona =  @MAXRECORD";

            $p = $pdo->prepare($sql2);
            $p->execute(array($id));
            $c = $p->fetch(PDO::FETCH_ASSOC);


            $sql3 = "DECLARE @MAXRECORD INT = (SELECT id_persona_solicita
            FROM [Tickets].[dbo].[Ticket] t
            where id_ticket =  ?)
    
            select nombre, dir_funcional, departamento
            from xxx_rrhh_Ficha
            where id_persona = @MAXRECORD";

            $p3 = $pdo->prepare($sql3);
            $p3->execute(array($id));
            $u = $p3->fetch(PDO::FETCH_ASSOC);


            $data = array();
            $fechaTer = $d["fecha_terminado"];
            //foreach ($detalle as $d) {

            $data = array(
                "fecha" => date('d-m-Y H:i', strtotime($d["fecha"])),
                "fecha_terminado" => (!empty($fechaTer)) ? date('d-m-Y H:i', strtotime($d["fecha_terminado"])) : $d["fecha_terminado"],
                "persona_solicita" => (!empty($d["persona_solicita"])) ? $d["persona_solicita"] : $u["nombre"],
                "detalle" => (!empty($d["detalle"])) ? $d["detalle"] : "Pendiente",
                "direccion_persona_solicita" => (!empty($d["direccion_persona_solicita"])) ? $d["direccion_persona_solicita"] : $u["dir_funcional"],
                "departamento_persona_solicita" => (!empty($d["departamento_persona_solicita"])) ? $d["departamento_persona_solicita"] : $u["departamento"],
                "persona_user" => (empty($c["persona_user"])) ? 'Sin Correo' : $c["persona_user"]
            );
        }


        echo json_encode($data);
    }

    //Opcion 7
    public static function asignarTicket()
    {
        $estado = 1;
        $id = $_GET['idtick'];
        $fecha = date('Y-m-d H:i:s');
        $tecnico = $GLOBALS['emp'];
        $yes = '';
        $arr_length2 = Ticket::getLongCorrelativos($id);
        $masUno = 0;
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            for ($j = 0; $j < $arr_length2[0]; $j++) {
                $sql = "DECLARE @MAXRECORD INT = (SELECT TOP 1 id_correlativo FROM [Tickets].[dbo].[ticket_detalle]
                    WHERE id_ticket = $id)+$masUno

                    INSERT INTO [Tickets].[dbo].[ticket_tecnico] (id_correlativo, id_tecnico, id_persona_asigna, estado, fecha)
                    VALUES (@MAXRECORD,?,?,?,?)";
                $p = $pdo->prepare($sql);
                $p->execute(array($tecnico[1], $tecnico[1], $estado, $fecha));
                $masUno++;
            }

            $sql = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = 6
            WHERE [id_ticket] = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($id));

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Asignado');
            $pdo->commit();
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }

        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    public static function contarTickets()
    {
        if ($_GET["masUno"] == 1) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT count (id_ticket)+1 as cantidad
        FROM [Tickets].[dbo].[Ticket]";

            $p = $pdo->prepare($sql);
            $p->execute();
            $detalle = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            foreach ($detalle as $d) {

                $sub_array = array(
                    "conteo" => $d["cantidad"]
                );

                $data[] = $sub_array;
            }
            echo json_encode($data);
        } else {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT count (id_ticket) as cantidad
        FROM [Tickets].[dbo].[Ticket]";

            $p = $pdo->prepare($sql);
            $p->execute();
            $detalle = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            foreach ($detalle as $d) {

                $sub_array = array(
                    "conteo" => $d["cantidad"]
                );

                $data[] = $sub_array;
            }
            echo json_encode($data);
        }
    }

    public static function aprobarTickets()
    {
        $idTicket = $_GET["idtick"];
        $aprobado = $_GET["aprobado"];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $yes = '';
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = ?
            WHERE [id_ticket] = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($aprobado, $idTicket));

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Aprobado');

            $pdo->commit();
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }
        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    //Opcion 10
    public static function getTecnicos()
    {
        $permisos = $GLOBALS["permisos"];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //insert
        if ($permisos["dir"] == true) {
            $sql = "SELECT * FROM xxx_rrhh_Ficha WHERE id_dirf = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array(8));

            $personas = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();
        } else if ($permisos["desarrollo"] == true) {
            $sql = "SELECT distinct f.id_persona
            ,primer_nombre
            ,segundo_nombre
            ,tercer_nombre
            ,primer_apellido
            ,segundo_apellido
            ,tercer_apellido
            FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
            inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
            inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
            INNER JOIN xxx_rrhh_Ficha f ON tau.id_persona = f.id_persona
            where flag_es_menu = 1 and taud.id_pantalla = 352";
            $p = $pdo->prepare($sql);
            $p->execute(array());

            $personas = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();
        } else if ($permisos["soporte"] == true) {
            $sql = "SELECT distinct f.id_persona
            ,primer_nombre
            ,segundo_nombre
            ,tercer_nombre
            ,primer_apellido
            ,segundo_apellido
            ,tercer_apellido
            FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
            inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
            inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
            INNER JOIN xxx_rrhh_Ficha f ON tau.id_persona = f.id_persona
            where flag_es_menu = 1 and taud.id_pantalla = 353";
            $p = $pdo->prepare($sql);
            $p->execute(array());

            $personas = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();
        } else if ($permisos["radios"] == true) {
            $sql = "SELECT distinct f.id_persona
            ,primer_nombre
            ,segundo_nombre
            ,tercer_nombre
            ,primer_apellido
            ,segundo_apellido
            ,tercer_apellido
            FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
            inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
            inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
            INNER JOIN xxx_rrhh_Ficha f ON tau.id_persona = f.id_persona
            where flag_es_menu = 1 and taud.id_pantalla = 354";
            $p = $pdo->prepare($sql);
            $p->execute(array());

            $personas = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();
        }
        foreach ($personas as $p) {
            $sub_array = array(
                'id_persona' => $p['id_persona'],
                'primer_nombre' => $p['primer_nombre'],
                'segundo_nombre' => $p['segundo_nombre'],
                'tercer_nombre' => $p['tercer_nombre'],
                'primer_apellido' => $p['primer_apellido'],
                'segundo_apellido' => $p['segundo_apellido'],
                'tercer_apellido' => $p['tercer_apellido'],
            );
            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    //Opcion 11
    public static function finalizarTicket()
    {
        $persona_asignada = $GLOBALS['emp'];
        $idTicket = $_GET["idtick"];
        $aprobado = $_GET["aprobado"];
        $fecha = date('Y-m-d H:i:s');
        $nombreReq = $_GET["nombreReq"];
        $descripTec = $_GET["descripTec"];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $yes = '';
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Obtener id requerimiento
            $sql1 = "SELECT td.id_requerimiento
            FROM [Tickets].[dbo].[ticket_detalle] td
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where id_ticket = ? and r.nombre = ?";
            $q1 = $pdo->prepare($sql1);
            $q1->execute(array($idTicket, $nombreReq));
            $idReq = $q1->fetch();

            $sql00 = "SELECT fecha_terminado
            FROM [Tickets].[dbo].[Ticket]
            where id_ticket = ?";
            $q11 = $pdo->prepare($sql00);
            $q11->execute(array($idTicket));
            $fechaVacia = $q11->fetch();

            if (empty($fechaVacia["fecha_terminado"])) {
                $sql = "UPDATE [Tickets].[dbo].[ticket_detalle] SET id_estado = ?, fecha_terminado = ?, descripcion_tecnico = ?, id_tecnico = ?
                WHERE id_ticket = ? and id_requerimiento = ?";
                $p = $pdo->prepare($sql);
                $p->execute(array($aprobado, $fecha, $descripTec, $persona_asignada[1], $idTicket, $idReq[0]));
            } else {
                $sql = "UPDATE [Tickets].[dbo].[ticket_detalle] SET id_estado = ?, fecha_terminado = ?, descripcion_tecnico = ?, id_tecnico = ?
                WHERE id_ticket = ? and id_requerimiento = ?";
                $p = $pdo->prepare($sql);
                $p->execute(array($aprobado, $fechaVacia["fecha_terminado"], $descripTec, $persona_asignada[1], $idTicket, $idReq[0]));
            }

            //Obtener cantidad de Requerimientos del Ticket
            $sql0 = "SELECT count ([id_requerimiento])
            FROM [Tickets].[dbo].[ticket_detalle]
            where id_ticket = ?";
            $q0 = $pdo->prepare($sql0);
            $q0->execute(array($idTicket));
            $cantRequerimientos = $q0->fetch();

            //Obtener cantidad de Requerimientos Finalizados
            $sql2 = "SELECT count ([id_requerimiento])
            FROM [Tickets].[dbo].[ticket_detalle]
            where id_ticket = ? and id_estado=?";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(array($idTicket, $aprobado));
            $cantRequerimientosF = $q2->fetch();

            if ($cantRequerimientos == $cantRequerimientosF) {

                if (empty($fechaVacia["fecha_terminado"])) {
                    $sql3 = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = ?, [fecha_terminado] = ?
                    WHERE [id_ticket] = ?";
                    $p = $pdo->prepare($sql3);
                    $p->execute(array(3, $fecha, $idTicket));
                } else {
                    $sql3 = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = ?
                    WHERE [id_ticket] = ?";
                    $p = $pdo->prepare($sql3);
                    $p->execute(array(3, $idTicket));
                }
            }

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Requerimiento Finalizado');

            $pdo->commit();
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }
        echo json_encode($yes);
        Database::disconnect_sqlsrv();

        //insert
    }

    //Opcion 12
    static function getRequerimientosSolicitados()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $id = $_GET['id'];

        $sql = "SELECT td.[id_requerimiento],
            r.nombre
            ,id_estado
            ,fecha_terminado
            ,RANK() OVER(ORDER BY td.[id_requerimiento]) correlativo
            FROM [Tickets].[dbo].[ticket_detalle] td
            left join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where id_ticket = ?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $requerimientos = $p->fetchAll(PDO::FETCH_ASSOC);


        $data = array();

        foreach ($requerimientos as $r) {
            $nombre = $r["nombre"];
            $estado = $r["id_estado"];
            $correlativo = $r["correlativo"];
            $fecha_terminado =  $r["fecha_terminado"];
            $sub_array = array(
                "nombre" => (!empty($nombre)) ? $r["nombre"] : "pendiente",
                "estado" => (!empty($estado)) ? $r["id_estado"] : "pendiente",
                "correlativo" => (!empty($correlativo)) ? $r["correlativo"] : "pendiente",
                "fecha_terminado" => (!empty($fecha_terminado)) ? date('d-m-Y H:i', strtotime($r["fecha_terminado"])) : "pendiente"
            );
            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    //Opcion 13
    public static function asignarTecnicos()
    {
        $estado = $_GET['estado'];
        $id = $_GET['id'];
        $tecnicosAsignados = $_GET["tecnicosAsignados"];
        $fecha = date('Y-m-d H:i:s');
        $persona_asigna = $GLOBALS['emp'];
        $yes = '';
        $arr_length2 = Ticket::getLongCorrelativos($id);
        $masUno = 0;
        $masTecnico = 0;
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($estado == 1) {
                $arr_length = count($tecnicosAsignados);
                for ($i = 0; $i < $arr_length; $i++) {
                    for ($j = 0; $j < $arr_length2[0]; $j++) {
                        $sql = "DECLARE @MAXRECORD INT = (SELECT TOP 1 id_correlativo FROM [Tickets].[dbo].[ticket_detalle]
                        WHERE id_ticket = ?)+$masUno

                        INSERT INTO [Tickets].[dbo].[ticket_tecnico] (id_correlativo, id_tecnico, id_persona_asigna, estado, fecha)
                        VALUES (@MAXRECORD,?,?,?,?)";
                        $p = $pdo->prepare($sql);
                        $p->execute(array($id, $tecnicosAsignados[$masTecnico], $persona_asigna[1], $estado, $fecha));
                        $masUno++;
                    }
                    $masUno = 0;
                    $masTecnico++;
                }

                $sql = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = 2
                WHERE [id_ticket] = ?";
                $p = $pdo->prepare($sql);
                $p->execute(array($id));
            } else if ($estado == 2) {
                $masUno = 1;
                $razon = $_GET["razon"];
                $original = $_GET["tecnicoOriginal"];
                $presql = "SELECT distinct td.id_correlativo
                FROM [Tickets].[dbo].[ticket_tecnico] tt
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.id_correlativo = td.id_correlativo
                where td.id_ticket = ? and tt.id_tecnico = ?";

                $p = $pdo->prepare($presql);
                $p->execute(array($id, $original));
                $correlativo = $p->fetchAll();
                foreach ($correlativo as $c) {
                    $sql = "UPDATE [Tickets].[dbo].[ticket_tecnico]
                    SET estado = ?, descripcion = ?
                    WHERE id_correlativo =? and id_tecnico = ?
                    INSERT INTO [Tickets].[dbo].[ticket_tecnico] (id_correlativo, id_tecnico, id_persona_asigna, estado, fecha)
                    VALUES (?,?,?,?,?)";
                    $p = $pdo->prepare($sql);
                    $p->execute(array(0, $razon, $c["id_correlativo"], $original, $c["id_correlativo"], $tecnicosAsignados, $persona_asigna[1], $estado, $fecha));
                }
            }

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Asignado');
            $pdo->commit();
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }

        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    static function getLongCorrelativos($idTick)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT count ([id_correlativo])
        FROM [Tickets].[dbo].[ticket_detalle]
        where id_ticket = $idTick";
        $p = $pdo->prepare($sql);
        $p->execute(array());
        $long = $p->fetch();
        Database::disconnect_sqlsrv();
        return $long;
    }

    //opcion 14
    static function getTecnicosAsignados()
    {
        $id = $_GET['id'];
        $tipo = $_GET["tipo"];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($tipo == 1) {
            $sql = "SELECT DISTINCT 
            tt.id_tecnico
            ,tt.estado
            ,tt.descripcion
            ,td.id_ticket
            ,rp.primer_nombre
            ,rp.segundo_nombre
            ,rp.tercer_nombre
            ,rp.primer_apellido
            ,rp.segundo_apellido														
            ,rp.tercer_apellido
            FROM [Tickets].[dbo].[ticket_tecnico] tt
            inner join [Tickets].[dbo].[ticket_detalle] td on tt.[id_correlativo] = td.[id_correlativo]
            inner join [SAAS_APP].[dbo].[rrhh_persona] rp on tt.[id_tecnico] = rp.id_persona
            where td.id_ticket = $id";
        } else if ($tipo == 2) {
            $sql = "SELECT DISTINCT 
            tt.id_tecnico
            ,tt.estado
            ,tt.descripcion
            ,td.id_ticket
            ,rp.primer_nombre
            ,rp.segundo_nombre
            ,rp.tercer_nombre
            ,rp.primer_apellido
            ,rp.segundo_apellido														
            ,rp.tercer_apellido
            FROM [Tickets].[dbo].[ticket_tecnico] tt
            inner join [Tickets].[dbo].[ticket_detalle] td on tt.[id_correlativo] = td.[id_correlativo]
            inner join [SAAS_APP].[dbo].[rrhh_persona] rp on tt.[id_tecnico] = rp.id_persona
            where td.id_ticket = $id and tt.estado!=0";
        }

        $p = $pdo->prepare($sql);
        $p->execute(array());
        $tecnicos = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($tecnicos as $t) {

            $sub_array = array(
                "id_tecnico" => $t["id_tecnico"],
                "estado" => $t["estado"],
                "id_ticket" => $t["id_ticket"],
                "primer_nombre" => $t["primer_nombre"],
                "segundo_nombre" => $t["segundo_nombre"],
                "tercer_nombre" => $t["tercer_nombre"],
                "primer_apellido" => $t["primer_apellido"],
                "segundo_apellido" => $t["segundo_apellido"],
                "tercer_apellido" => $t["tercer_apellido"],
                "descripcion" => (!empty($t["descripcion"])) ?  $t["descripcion"] : "Realizando Ticket",
            );

            $data[] = $sub_array;
        }

        echo json_encode($data);
    }

    //Opcion 15
    static function rechazarTickets()
    {
        $idTicket = $_GET["idtick"];
        $aprobado = $_GET["aprobado"];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $yes = '';
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = ?
            WHERE [id_ticket] = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($aprobado, $idTicket));

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Rechazado');

            $pdo->commit();
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }
        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    //Opcion 16
    static function calificarTicket()
    {
        $idTicket = $_GET["idtick"];
        $aprobado = $_GET["aprobado"];
        $idCalificacion = $_GET["idCalificacion"];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $yes = '';
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE [Tickets].[dbo].[Ticket] SET [id_estado] = ?
            WHERE [id_ticket] = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($aprobado, $idTicket));

            $sql = "UPDATE [Tickets].[dbo].[Ticket] SET id_calificacion = ?
            WHERE [id_ticket] = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($idCalificacion, $idTicket));

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Ticket Calificado');

            $pdo->commit();
        } catch (PDOException $e) {

            $yes = array('msg' => 'ERROR', 'id' => $e);
            try {
                $pdo->rollBack();
            } catch (Exception $e2) {
                $yes = array('msg' => 'ERROR', 'id' => $e2);
            }
        }
        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    static function departamentoPersonaSolicita($idpersona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT departamento from xxx_rrhh_Ficha
        where id_persona = $idpersona";
        $p = $pdo->prepare($sql);
        $p->execute(array());
        $departamento = $p->fetch();
        Database::disconnect_sqlsrv();
        return $departamento;
    }

    //Opcion 17
    static function calificacionTicket()
    {
        $id = $_GET['id'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_calificacion from [Tickets].[dbo].[Ticket]
        where id_ticket = $id";

        $p = $pdo->prepare($sql);
        $p->execute(array());
        $calificacion = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($calificacion as $c) {

            $sub_array = array(
                "id_calificacion" => $c["id_calificacion"]
            );

            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    static function getTecnicosAsignados2($idTick)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT 
        tt.id_tecnico
        ,td.id_ticket
        ,rp.primer_nombre
        ,rp.segundo_nombre
        ,rp.tercer_nombre
        ,rp.primer_apellido
        ,rp.segundo_apellido														
        ,rp.tercer_apellido
        FROM [Tickets].[dbo].[ticket_tecnico] tt
        inner join [Tickets].[dbo].[ticket_detalle] td on tt.[id_correlativo] = td.[id_correlativo]
        inner join [SAAS_APP].[dbo].[rrhh_persona] rp on tt.[id_tecnico] = rp.id_persona
        where td.id_ticket = $idTick and tt.estado != 0";
        $p = $pdo->prepare($sql);
        $p->execute(array());
        $tecnicos = $p->fetchAll();
        $todos = "";
        foreach ($tecnicos as $t) {
            $todos .=  $t["primer_nombre"] . ' ' . $t["segundo_nombre"] . ' ' . $t["tercer_nombre"] . ' ' .
                $t["primer_apellido"] . ' ' . $t["segundo_apellido"] . ' ' . $t["tercer_apellido"] . ' ' . "<br>";
        }

        Database::disconnect_sqlsrv();
        return $todos;
    }

    //Opcion 18
    static function sendMail()
    {
        $tipoEnviar = $_GET["tipoEnviar"];
        $idTick = $_GET["idTick"];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tipoEnviar == 1) {
            //Funcion para selecionar enviar correo tecnicos
            $sql55 = "SELECT DISTINCT 
                tt.id_tecnico
                ,td.id_ticket
                ,persona_user
                FROM [Tickets].[dbo].[ticket_tecnico] tt
                inner join [Tickets].[dbo].[ticket_detalle] td on tt.[id_correlativo] = td.[id_correlativo]
                inner join [SAAS_APP].[dbo].[rrhh_persona] rp on tt.[id_tecnico] = rp.id_persona
                INNER JOIN rrhh_persona_usuario b ON tt.id_tecnico = b.id_persona
                where td.id_ticket = ?";
            $q55 = $pdo->prepare($sql55);
            $q55->execute(array($idTick));
            $correo = $q55->fetchAll();
            $correos = array();

            foreach ($correo as $c) {
                //Funcion para la persona que envia el correo
                $sql77 = "SELECT b.persona_user
                FROM rrhh_persona_usuario b
                WHERE b.id_persona = ?";
                $q77 = $pdo->prepare($sql77);
                $q77->execute(array($_SESSION['id_persona']));
                $emisor = $q77->fetch();

                //Funcion para mandar a traer los requerimientos
                $sql1 = "SELECT r.nombre
                ,rd.descripcion
                FROM [Tickets].[dbo].[ticket_detalle] td
                inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
                inner join [SAAS_APP].[dbo].[rrhh_departamentos] rd on r.id_departamento = rd.id_departamento
                where id_ticket = ?";
                $sql1 = $pdo->prepare($sql1);
                $sql1->execute(array($idTick));
                $requerimientos = $sql1->fetchAll();
                $requerimientos2 = "";
                foreach ($requerimientos as $r) {
                    $requerimientos2 .= $r["nombre"] . " - " . $r["descripcion"] . "<br>";
                }

                //Funcion para mandar a traer la descripcion del Ticket
                $sql1 = "SELECT [detalle]
                FROM [Tickets].[dbo].[Ticket]
                where id_ticket = ?";
                $sql1 = $pdo->prepare($sql1);
                $sql1->execute(array($idTick));
                $descripcion = $sql1->fetch();
                $descripcion2 = $descripcion["detalle"];
                $titulo = 'asignado';
                $subject = 'TICKET ' . strtoupper($titulo);;
                $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el Ticket No. <strong>' . $idTick . '</strong> fue ' . $titulo;
                $body .= '<br>Siendo las: ' . date('H:i:s') . ' del ' . date('d-m-Y');
                $body .= '<br><br><strong>Requerimientos del ticket</strong>:<br>';
                $body .= "$requerimientos2";

                $body .= '<br><br><strong>Información del ticket</strong>:<br>';
                $body .= "$descripcion2";

                $body .= '<br><br><br>Correo enviado desde SAAS APP - Módulo control de Tickets';

                if ($c["persona_user"] == 'rocio.valenzuela@saas.gob.gt') {
                    $receptor = 'melannie.valenzuela@saas.gob.gt';
                } else {
                    $receptor = $c["persona_user"];
                }

                $mail = array(
                    'emisor' => $emisor['persona_user'],
                    'receptor' => $receptor,
                    'subject' => $subject,
                    'body' => $body,
                );
            }
            $correos[] = $mail;

            //Correo para Rechazar
        } else if ($tipoEnviar == 2) {
            $descript = $_GET["decrip"];

            //funcion para enviar correo persona solicita
            $sql55 = "SELECT persona_user
              FROM [Tickets].[dbo].[Ticket] t
              inner join rrhh_persona_usuario rpu on t.id_persona_solicita = rpu.id_persona
              where id_ticket = ?";
            $q55 = $pdo->prepare($sql55);
            $q55->execute(array($idTick));
            $correo = $q55->fetch();

            //Funcion para la persona que envia el correo
            $sql77 = "SELECT b.persona_user
                FROM rrhh_persona_usuario b
                WHERE b.id_persona = ?";
            $q77 = $pdo->prepare($sql77);
            $q77->execute(array($_SESSION['id_persona']));
            $emisor = $q77->fetch();

            $titulo = 'rechazado';
            $subject = 'TICKET ' . strtoupper($titulo);;
            $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el Ticket No. <strong>' . $idTick . '</strong> fue ' . $titulo;
            $body .= '<br>Siendo las: ' . date('H:i:s') . ' del ' . date('d-m-Y');
            $body .= '<br><br><strong>Motivo Rechazo</strong>:<br>';
            $body .= $descript;

            $body .= '<br><br><br>Correo enviado desde SAAS APP - Módulo control de Tickets';

            if ($correo["persona_user"] == 'rocio.valenzuela@saas.gob.gt') {
                $receptor = 'melannie.valenzuela@saas.gob.gt';
            } else {
                $receptor = $correo["persona_user"];
            }

            $mail = array(
                'emisor' => $emisor['persona_user'],
                'receptor' => $receptor,
                'subject' => $subject,
                'body' => $body,
            );
            $correos[] = $mail;
        } else if ($tipoEnviar == 3) {
            //funcion para enviar correo persona solicita
            $sql55 = "SELECT persona_user
              FROM [Tickets].[dbo].[Ticket] t
              inner join rrhh_persona_usuario rpu on t.id_persona_solicita = rpu.id_persona
              where id_ticket = ?";
            $q55 = $pdo->prepare($sql55);
            $q55->execute(array($idTick));
            $correo = $q55->fetch();

            //Funcion para la persona que envia el correo
            $sql77 = "SELECT b.persona_user
                FROM rrhh_persona_usuario b
                WHERE b.id_persona = ?";
            $q77 = $pdo->prepare($sql77);
            $q77->execute(array($_SESSION['id_persona']));
            $emisor = $q77->fetch();

            $titulo = 'aprobado';
            $subject = 'TICKET ' . strtoupper($titulo);;
            $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el Ticket No. <strong>' . $idTick . '</strong> fue ' . $titulo;
            $body .= '<br>Siendo las: ' . date('H:i:s') . ' del ' . date('d-m-Y');
            $body .= '<br><br><strong>En un momento se le asignara un Técnico</strong><br>';

            $body .= '<br><br><br>Correo enviado desde SAAS APP - Módulo control de Tickets';
            if ($correo["persona_user"] == 'rocio.valenzuela@saas.gob.gt') {
                $receptor = 'melannie.valenzuela@saas.gob.gt';
            } else {
                $receptor = $correo["persona_user"];
            }
            $mail = array(
                'emisor' => $emisor['persona_user'],
                'receptor' => $receptor,
                'subject' => $subject,
                'body' => $body,
            );
            $correos[] = $mail;
        } else if ($tipoEnviar == 4) {
            //Obtener idTicket
            $sql0 = "SELECT TOP (1) [id_ticket]
            FROM [Tickets].[dbo].[Ticket]
            ORDER BY [id_ticket] DESC";
            $sql0 = $pdo->prepare($sql0);
            $sql0->execute(array());
            $id = $sql0->fetch();
            $id2 = $id["id_ticket"];

            //Funcion para mandar a traer los requerimientos
            $sql1 = "SELECT r.nombre
            ,rd.descripcion
            FROM [Tickets].[dbo].[ticket_detalle] td
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            inner join [SAAS_APP].[dbo].[rrhh_departamentos] rd on r.id_departamento = rd.id_departamento
            where id_ticket = ?";
            $sql1 = $pdo->prepare($sql1);
            $sql1->execute(array($id2));
            $requerimientos = $sql1->fetchAll();
            $requerimientos2 = "";
            foreach ($requerimientos as $r) {
                $requerimientos2 .= $r["nombre"] . " - " . $r["descripcion"] . "<br>";
            }

            //Funcion para traer la descripción del Ticket
            $sql1 = "SELECT [detalle]
                FROM [Tickets].[dbo].[Ticket]
                where id_ticket = ?";
            $sql1 = $pdo->prepare($sql1);
            $sql1->execute(array($id2));
            $descripcion = $sql1->fetch();
            $descripcion2 = $descripcion["detalle"];

            //funcion para enviar correo persona recibe
            $sql77 = "SELECT persona_user
            FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
            inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
            inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
            INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
            where flag_es_menu = 1 and taud.id_pantalla = 351";
            $q77 = $pdo->prepare($sql77);
            $q77->execute(array());
            $correo = $q77->fetchAll();
            $correos = array();
            foreach ($correo as $c) {
                //Funcion para la persona que envia el correo
                $sql55 = "SELECT persona_user
                FROM [Tickets].[dbo].[Ticket] t
                inner join rrhh_persona_usuario rpu on t.id_persona_solicita = rpu.id_persona
                where id_ticket = ?";
                $q55 = $pdo->prepare($sql55);
                $q55->execute(array($id2));
                $emisor = $q55->fetch();

                $titulo = 'nuevo';
                $subject = 'TICKET ' . strtoupper($titulo);;
                $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el Ticket No. <strong>' . $id2 . '</strong> fue generado';
                $body .= '<br>Siendo las: ' . date('H:i:s') . ' del ' . date('d-m-Y');
                $body .= '<br><br><strong>Requerimientos del ticket</strong>:<br>';
                $body .= "$requerimientos2";

                $body .= '<br><br><strong>Descripción Ticket:</strong><br>';
                $body .= "$descripcion2";
                $body .= '<br><br><br>Correo enviado desde SAAS APP - Módulo control de Tickets';
                
                if ($c["persona_user"] == 'rocio.valenzuela@saas.gob.gt') {
                    $receptor = 'melannie.valenzuela@saas.gob.gt';
                } else {
                    $receptor = $c["persona_user"];
                }
                $mail = array(
                    'emisor' => $emisor['persona_user'],
                    'receptor' => $receptor,
                    'subject' => $subject,
                    'body' => $body,
                );
                $correos[] = $mail;

            }
        }
        $yes = array('msg' => 'OK', 'id' => $tipoEnviar, 'correos' => $correos);
        echo json_encode($yes);
    }

    //opcion 19
    static function sendMailchief()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tipoEnviar = $_GET["tipoEnviar"];
        $sql0 = "SELECT TOP (1) [id_ticket]
        FROM [Tickets].[dbo].[Ticket]
        ORDER BY [id_ticket] DESC";
        $sql0 = $pdo->prepare($sql0);
        $sql0->execute(array());
        $id = $sql0->fetch();
        $idTick = $id["id_ticket"];

        //Obtener ID departamento 
        $sql55 = "SELECT r.nombre
        ,r.id_departamento
        FROM [Tickets].[dbo].[Ticket] t
        inner join [Tickets].[dbo].[Estado] e on t.[id_estado] = e.id_estado
        inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
        inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
        where  t.id_estado = 1 and t.id_ticket = ?";
        $q55 = $pdo->prepare($sql55);
        $q55->execute(array($idTick));
        $id_departamento = $q55->fetchAll();
        $pantallaDepartamento = "";

        //Funcion para la persona que envia el correo
        $sql55 = "SELECT persona_user
        FROM [Tickets].[dbo].[Ticket] t
        inner join rrhh_persona_usuario rpu on t.id_persona_solicita = rpu.id_persona
        where id_ticket = ?";
        $q55 = $pdo->prepare($sql55);
        $q55->execute(array($idTick));
        $emisor = $q55->fetch();

        //funcion para enviar correo a los jefes
        $sql77 = "SELECT persona_user
        FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
        inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
        inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
        INNER JOIN rrhh_persona_usuario b ON tau.id_persona = b.id_persona
        where flag_es_menu = 1 and taud.id_pantalla = 350";
        $q77 = $pdo->prepare($sql77);
        $q77->execute(array());
        $jefe = $q77->fetchAll();


        //Funcion para mandar a traer la descripcion del Ticket
        $sql1 = "SELECT [detalle]
        FROM [Tickets].[dbo].[Ticket]
        where id_ticket = ?";
        $sql1 = $pdo->prepare($sql1);
        $sql1->execute(array($idTick));
        $descripcion = $sql1->fetch();
        $descripcion2 = $descripcion["detalle"];
        $correos = array();

        $receptor = '';

        $correos = array(); // Mueve esta declaración fuera del bucle

        foreach ($id_departamento as $d) {
            if ($d["id_departamento"] == 60) {
                $pantallaDepartamento = 352;
            } else if ($d["id_departamento"] == 65) {
                $pantallaDepartamento = 353;
            } else if ($d["id_departamento"] == 90) {
                $pantallaDepartamento = 354;
            }
        
            foreach ($jefe as $f) {

                //funcion para enviar correo persona solicita
                $sql2 = "SELECT persona_user
                FROM [SAAS_APP].[dbo].[tbl_accesos_usuarios_det] taud
                inner join [SAAS_APP].[dbo].[tbl_pantallas] tp on taud.id_pantalla = tp.id_pantalla
                inner join [SAAS_APP].[dbo].[tbl_accesos_usuarios] tau on taud.id_acceso = tau.id_acceso
                INNER JOIN [SAAS_APP].[dbo].rrhh_persona_usuario b ON tau.id_persona = b.id_persona
                where flag_es_menu = 1  and persona_user = ? and taud.id_pantalla  = ?";
                $q2 = $pdo->prepare($sql2);
                $q2->execute(array($f["persona_user"], $pantallaDepartamento));
                $jefeDep = $q2->fetchAll();

                foreach ($jefeDep as $fd) {

                    if ($fd["persona_user"] == 'rocio.valenzuela@saas.gob.gt') {
                        $receptor = 'melannie.valenzuela@saas.gob.gt';
                    } else {
                        $receptor = $f["persona_user"];
                    }

                    $titulo = 'nuevo';
                    $subject = 'TICKET ' . strtoupper($titulo);;
                    $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que el Ticket No. <strong>' . $idTick . '</strong> fue generado';
                    $body .= '<br>Siendo las: ' . date('H:i:s') . ' del ' . date('d-m-Y');
                    $body .= '<br><br><strong>Requerimientos del ticket</strong>:<br>';
                    $body .= $d["nombre"];

                    $body .= '<br><br><strong>Información del ticket</strong>:<br>';
                    $body .= "$descripcion2";

                    $body .= '<br><br><br>Correo enviado desde SAAS APP - Módulo control de Tickets';

                    $mail = array(
                        'emisor' => $emisor['persona_user'],
                        'receptor' => $receptor,
                        'subject' => $subject,
                        'body' => $body,
                    );
                    $correos[] = $mail; // Agrega el correo al arreglo de correos
                }
            }
        }
        $yes = array('msg' => 'OK', 'id' => $tipoEnviar, 'correos' => $correos);
        echo json_encode($yes);;
    }

    //Opcion 20
    static function getRequerimientosPendientes()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_GET['id'];
        $tipo = $_GET["tipo"];

        $sql = "SELECT td.[id_requerimiento],
            r.nombre
            ,id_estado
            FROM [Tickets].[dbo].[ticket_detalle] td
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where id_ticket = ? and id_estado = ?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id, $tipo));
        $requerimientos = $p->fetchAll(PDO::FETCH_ASSOC);


        $data = array();

        foreach ($requerimientos as $r) {

            $sub_array = array(
                "nombre" => $r["nombre"]
            );
            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    //Opcion 21
    static function informacionTecnicos()
    {
        $id = $_GET['id'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT [id_ticket]
        ,[nombre]
        ,td.[descripcion_tecnico]
        ,rp.primer_nombre
        ,rp.primer_apellido
        FROM [Tickets].[dbo].[ticket_detalle] td
        left join [SAAS_APP].[dbo].[rrhh_persona] rp on td.[id_tecnico] = rp.id_persona
        left join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
        where id_ticket = ?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $detalle = $p->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        //foreach ($detalle as $d) {
        foreach ($detalle as $d) {
            $nombreTec = $d["primer_nombre"];
            $apellido = $d["primer_apellido"];
            $descri =  $d["descripcion_tecnico"];
            $nombreReque = $d["nombre"];
            $sub_array = array(
                "primer_nombre" => (!empty($nombreTec)) ? $d["primer_nombre"] : "pendiente",
                "primer_apellido" => (!empty($apellido)) ? $d["primer_apellido"] : "pendiente",
                "descripcion_tecnico" => (!empty($descri)) ?  $d["descripcion_tecnico"] : "pendiente",
                "nombre" => (!empty($nombreReque)) ? $d["nombre"] : "pendiente",
            );
            $data[] = $sub_array;
        }

        echo json_encode($data);
    }

    //Opcion22
    static function ticketsDepartamentos()
    {
        if ($_GET["depa"] == 60) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "select count(d.desarrollo) desarrollo
            from (SELECT distinct t.id_ticket as desarrollo
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado =1 and id_departamento =60) as d";
            $p = $pdo->prepare($sql);
            $p->execute(array());
            $ad = $p->fetch(PDO::FETCH_ASSOC);

            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql5 = "select count(d.desarrollo) desarrollo
            from (SELECT distinct t.id_ticket as desarrollo
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado =2 and id_departamento =60) as d";
            $p5 = $pdo->prepare($sql5);
            $p5->execute(array());
            $pd = $p5->fetch(PDO::FETCH_ASSOC);

            $data = array();

            $sub_array = array(
                "estado" => "Abiertos",
                "tickets" => $ad["desarrollo"],
            );

            $sub_array2 = array(
                "estado" => "Proceso",
                "tickets" => $pd["desarrollo"],
            );

            array_push($data, $sub_array, $sub_array2);
        }

        if ($_GET["depa"] == 65) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql2 = "select count(s.soporte) soporte
            from (SELECT distinct t.id_ticket as soporte
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado =1 and id_departamento =65) as s";
            $p2 = $pdo->prepare($sql2);
            $p2->execute(array());
            $as = $p2->fetch(PDO::FETCH_ASSOC);

            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql6 = "select count(s.soporte) soporte
            from (SELECT distinct t.id_ticket as soporte
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado =2 and id_departamento =65) as s";
            $p6 = $pdo->prepare($sql6);
            $p6->execute(array());
            $ps = $p6->fetch(PDO::FETCH_ASSOC);

            $data = array();

            $sub_array = array(
                "estado" => "Abiertos",
                "tickets" => $as["soporte"],
            );

            $sub_array2 = array(
                "estado" => "Proceso",
                "tickets" => $ps["soporte"],
            );

            array_push($data, $sub_array, $sub_array2);
        }

        if ($_GET["depa"] == 90) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql3 = "select count(r.radios) radios
            from (SELECT distinct t.id_ticket as radios
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado =1 and id_departamento =90) as r";
            $p3 = $pdo->prepare($sql3);
            $p3->execute(array());
            $ar = $p3->fetch(PDO::FETCH_ASSOC);

            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql7 = "select count(r.radios) radios
            from (SELECT distinct t.id_ticket as radios
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado =2 and id_departamento =90) as r";
            $p7 = $pdo->prepare($sql7);
            $p7->execute(array());
            $pr = $p7->fetch(PDO::FETCH_ASSOC);

            $data = array();

            $sub_array = array(
                "estado" => "Abiertos",
                "tickets" => $ar["radios"],
            );

            $sub_array2 = array(
                "estado" => "Proceso",
                "tickets" => $pr["radios"],
            );

            array_push($data, $sub_array, $sub_array2);
        }

        if ($_GET["depa"] == 1) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql10 = "select count(d.desarrollo) desarrollo
            from (SELECT distinct t.id_ticket as desarrollo
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado in (1,2) and id_departamento =60) as d";
            $p10 = $pdo->prepare($sql10);
            $p10->execute(array());
            $td = $p10->fetch(PDO::FETCH_ASSOC);

            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql8 = "select count(s.soporte) soporte
            from (SELECT distinct t.id_ticket as soporte
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado in (1,2) and id_departamento = 65) as s";
            $p8 = $pdo->prepare($sql8);
            $p8->execute(array());
            $ts = $p8->fetch(PDO::FETCH_ASSOC);

            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql7 = "select count(r.radios) radios
            from (SELECT distinct t.id_ticket as radios
            FROM [Tickets].[dbo].[Ticket] t
            inner join [Tickets].[dbo].[ticket_detalle] td on t.id_ticket = td.id_ticket
            inner join [Tickets].[dbo].[Requerimiento] r on td.id_requerimiento = r.id_requerimiento
            where t.id_estado in (1,2) and id_departamento =90) as r";
            $p7 = $pdo->prepare($sql7);
            $p7->execute(array());
            $tr = $p7->fetch(PDO::FETCH_ASSOC);

            $hoy = date('Y-m-d');
            $sql = "SELECT count(id_ticket) as total
            FROM [Tickets].[dbo].[Ticket] t
            where fecha >= '$hoy 00:00:00'AND Fecha <= '$hoy 23:59:59'";
            $p = $pdo->prepare($sql);
            $p->execute(array());
            $t = $p->fetch(PDO::FETCH_ASSOC);

            $data = array();

            $sub_array = array(
                "desarrollo" => $td["desarrollo"],
                "soporte" => $ts["soporte"],
                "radios" => $tr["radios"],
                "dia" => $t["total"]
            );
            $data[] = $sub_array;
        }

        if ($_GET["depa"] == 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $hoy = date('Y-m-d');
            $sql = "SELECT count(id_ticket) as total
            FROM [Tickets].[dbo].[Ticket] t
            where fecha >= '$hoy 00:00:00'AND Fecha <= '$hoy 23:59:59'";
            $p = $pdo->prepare($sql);
            $p->execute(array());
            $t = $p->fetch(PDO::FETCH_ASSOC);

            $sql1 = "SELECT count(id_ticket) as pendientes
            FROM [Tickets].[dbo].[Ticket] t
            where id_estado in (1,2) and fecha >= '$hoy 00:00:00'AND Fecha <= '$hoy 23:59:59'";
            $p1 = $pdo->prepare($sql1);
            $p1->execute(array());
            $pe = $p1->fetch(PDO::FETCH_ASSOC);

            $sql2 = "SELECT count(id_ticket) as anulados
            FROM [Tickets].[dbo].[Ticket] t
            where id_estado = 4 and fecha >= '$hoy 00:00:00'AND Fecha <= '$hoy 23:59:59'";
            $p2 = $pdo->prepare($sql2);
            $p2->execute(array());
            $a = $p2->fetch(PDO::FETCH_ASSOC);

            $data = array();

            $sub_array = array(
                "estado" => "Total Dia",
                "total" => $t["total"]
            );

            $sub_array2 = array(
                "estado" => "Pendientes",
                "total" => $pe["pendientes"]
            );

            $sub_array3 = array(
                "estado" => "Anulados",
                "total" => $a["anulados"]
            );

            array_push($data, $sub_array, $sub_array2, $sub_array3);
        }

        echo json_encode($data);
    }
    static function getUsuarios()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($_GET["dir"] == '1000000') {
            $sql = "SELECT f.nombre
            ,f.id_persona
            ,id_dirf
            ,dir_funcional
            FROM [SAAS_APP].[dbo].[rrhh_persona_apoyo] pa
            inner join xxx_rrhh_Ficha f on pa.id_persona = f.id_persona
            where estado = 1";
            $p = $pdo->prepare($sql);
            $p->execute();
            $usuario = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            foreach ($usuario as $u) {
                $sub_array = array(
                    "nombre" => $u["nombre"],
                    "idNombre" => $u["id_persona"],
                    "idDir" => $u["id_dirf"],
                    "dir" => $u["dir_funcional"],
                );
                $data[] = $sub_array;
            }
        } else if ($_GET["dir"] == '2000000') {

            $sql = "SELECT id_salon
            ,nombre
            FROM [SAAS_APP].[dbo].[sal_salones]";

            $p = $pdo->prepare($sql);
            $p->execute();
            $usuario = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            foreach ($usuario as $u) {
                $sub_array = array(
                    "nombre" => $u["nombre"],
                    "idNombre" => $u["id_salon"],
                    "idDir" => $u["id_salon"],
                    "dir" => $u["nombre"],
                );
                $data[] = $sub_array;
            }
        } else {
            $sql = "SELECT nombre, id_persona,id_dirf,dir_funcional
            from xxx_rrhh_Ficha
            where id_dirf = ? and estado = 1";
            $p = $pdo->prepare($sql);
            $p->execute(array($_GET["dir"]));
            $usuario = $p->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            foreach ($usuario as $u) {

                $sub_array = array(
                    "nombre" => $u["nombre"],
                    "idNombre" => $u["id_persona"],
                    "idDir" => $u["id_dirf"],
                    "dir" => $u["dir_funcional"],
                );
                $data[] = $sub_array;
            }
        }

        echo json_encode($data);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
            //Para el Datatable
        case 1:
            Ticket::getAllTickets();
            break;

        case 2:
            Ticket::getDireccion();
            break;

        case 3:
            Ticket::getDepartamento();
            break;

        case 4:
            Ticket::getRequerimientos();
            break;

        case 5:
            Ticket::setTicketNuevo();
            break;
            //Para el DetalleTicket
        case 6:
            Ticket::getDetalle();
            break;

        case 7:
            Ticket::asignarTicket();
            break;
        case 8:
            Ticket::contarTickets();
            break;

        case 9:
            Ticket::aprobarTickets();
            break;

        case 10:
            Ticket::getTecnicos();
            break;

        case 11:
            Ticket::finalizarTicket();
            break;

        case 12:
            Ticket::getRequerimientosSolicitados();
            break;

        case 13:
            Ticket::asignarTecnicos();
            break;

        case 14:
            Ticket::getTecnicosAsignados();
            break;

        case 15:
            Ticket::rechazarTickets();
            break;

        case 16:
            Ticket::calificarTicket();
            break;

        case 17:
            Ticket::calificacionTicket();
            break;

        case 18:
            Ticket::sendMail();
            break;

        case 19:
            Ticket::sendMailchief();
            break;

        case 20:
            Ticket::getRequerimientosPendientes();
            break;

        case 21:
            Ticket::informacionTecnicos();
            break;

        case 22:
            Ticket::ticketsDepartamentos();
            break;

        case 23:
            Ticket::getUsuarios();
            break;
    }
}
