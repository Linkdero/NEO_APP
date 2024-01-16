<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class Detalle
{
    public $servicios;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->servicios = array();
    }

    //Opcion 1
    static function getInformacion()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_servicio
        ,id_vehiculo
        FROM SAAS_APP.dbo.dayf_vehiculo_servicios
        where id_servicio = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["noServi"]));
        $d = $p->fetch(PDO::FETCH_ASSOC);
        $tipV = "";
        if ($d["id_vehiculo"] != null) {
            $tipoV = 'id_vehiculo';
        } else {
            $tipoV = 'id_vehiculo_externo';
        }

        $sql = "SELECT id_servicio
        ,$tipoV
        ,id_mecanico_asignado
        ,nro_orden
        ,fecha_recepcion
        ,fecha_asignacion_mecanico
        , [dbo].[RTF2Text](descripcion_solicitado) AS descripcion_solicitado
        , [dbo].[RTF2Text](descripcion_realizado) AS descripcion_realizado 
        FROM SAAS_APP.dbo.dayf_vehiculo_servicios
        where id_servicio = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["noServi"]));
        $d = $p->fetch(PDO::FETCH_ASSOC);
        $data = array();

        $sub_array = array(
            "id_servicio" => $d["id_servicio"],
            "id_vehiculo" => $d[$tipoV],
            "id_mecanico_asignado" => $d["id_mecanico_asignado"],
            "nro_orden" => $d["nro_orden"],
            "fecha_recepcion" => $d["fecha_recepcion"],
            "fecha_asignacion_mecanico" => $d["fecha_asignacion_mecanico"],
            "descripcion_solicitado" => $d["descripcion_solicitado"],
            "descripcion_realizado" => empty($d["descripcion_realizado"]) ? 'Todavia no se ha terminado el servicio' : $d["descripcion_realizado"],
        );
        $data[] = $sub_array;

        echo json_encode($sub_array);
    }

    //Opcion 2
    static function getDetalleVehiculo()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT id_servicio
        ,id_vehiculo
        FROM SAAS_APP.dbo.dayf_vehiculo_servicios
        where id_servicio = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["noServi"]));
        $d = $p->fetch(PDO::FETCH_ASSOC);
        if ($d["id_vehiculo"] != null) {
            $sql = "SELECT propietario
            ,nro_placa
            ,nombre_tipo
            ,nombre_marca
            ,id_marca
            ,nombre_linea
            ,modelo
            ,observaciones
            ,nombre_color
            ,detalle_franjas
            ,chasis
            ,motor
            ,nombre_tipo_combustible
            ,nombre_estado
            FROM xxx_dayf_vehiculos
            where id_vehiculo = ?";
        } else {
            $sql = "SELECT id_vehiculo_externo
            ,nro_placa
            ,cd.descripcion as nombre_marca
            ,id_modelo as modelo
            ,cdd.descripcion as nombre_color
            ,p.descripcion as propietario
            ,cddd.descripcion nombre_tipo_combustible
            FROM dayf_vehiculos_externos as ve
            left join tbl_catalogo_detalle as cd on ve.id_marca = cd.id_item
            left join tbl_catalogo_detalle as cdd on ve.id_color = cdd.id_item
            left join dayf_proveedores as p on ve.id_arrendadora = p.id_proveedor
            left join tbl_catalogo_detalle as cddd on ve.id_tipo_combustible = cddd.id_item
            where id_vehiculo_externo = ?";
        }

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["idVehiculo"]));
        $d = $p->fetch(PDO::FETCH_ASSOC);

        $data = array();
        $null = 'Sin Informacion';
        $sub_array = array(
            "propietario" => $d["propietario"],
            "nro_placa" => $d["nro_placa"],
            "chasis" => isset($d["chasis"]) ? $d["chasis"] : $null,
            "motor" => isset($d["motor"]) ? $d["motor"] : $null,
            "modelo" => $d["modelo"],
            "detalle_franjas" => isset($d["detalle_franjas"]) ? $d["detalle_franjas"] : $null,
            "observaciones" => empty($d["observaciones"]) ? $null : $d["observaciones"],
            "nombre_tipo_combustible" => isset($d["nombre_tipo_combustible"]) ? $d["nombre_tipo_combustible"] : $null,
            "nombre_linea" => isset($d["nombre_linea"]) ? $d["nombre_linea"] : $null,
            "nombre_color" => isset($d["nombre_color"]) ? $d["nombre_color"] : $null,
            "id_marca" => isset($d["id_marca"]) ? $d["id_marca"] : $null,
            "nombre_marca" => isset($d["nombre_marca"]) ? $d["nombre_marca"] : $null,
            "nombre_tipo" => isset($d["nombre_tipo"]) ? $d["nombre_tipo"] : $null,
            "nombre_estado" => isset($d["nombre_estado"]) ? $d["nombre_estado"] : $null,
        );
        $data[] = $sub_array;

        echo json_encode($sub_array);
    }

    //Opcion 3
    static function getMecanicos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT nombre
        ,p_funcional
        FROM xxx_rrhh_Ficha
        where id_persona = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["idMecanico"]));
        $d = $p->fetch(PDO::FETCH_ASSOC);
        $data = array();

        $sub_array = array(
            "nombre" => empty($d["nombre"]) ? 'Mecanico no Asignado' : $d["nombre"],
            "p_funcional" => empty($d["p_funcional"]) ? 'Mecanico' : $d["p_funcional"]
        );
        $data[] = $sub_array;

        echo json_encode($sub_array);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Detalle::getInformacion();
            break;

        case 2:
            Detalle::getDetalleVehiculo();
            break;

        case 3:
            Detalle::getMecanicos();
            break;
    }
}