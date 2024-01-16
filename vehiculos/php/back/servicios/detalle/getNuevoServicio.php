<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class Nuevo
{
    public $nuevos;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->nuevos = array();
    }

    //Opcion 1
    static function getCargaTipoServicios()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_item ,descripcion
        FROM tbl_catalogo_detalle
        where id_catalogo = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array(54));
        $tipo = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($tipo as $t) {
            $sub_array = array(
                "id_item" => $t["id_item"],
                "descripcion" => $t["descripcion"],
            );
            $data[] = $sub_array;
        }

        echo json_encode($data);
    }

    //Opcion 2
    static function getFichas()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT nombre ,id_persona
        FROM xxx_rrhh_Ficha
        where estado = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array(1));
        $ficha = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($ficha as $f) {
            $sub_array = array(
                "nombre" => $f["nombre"],
                "id_persona" => $f["id_persona"],
            );
            $data[] = $sub_array;
        }

        echo json_encode($data);
    }

    //Opcion 3
    static function getPlacas()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_GET["tipo"] == 1) {
            $sql = "SELECT distinct id_vehiculo
            ,nro_placa
            FROM dayf_vehiculo_placas";
        } else {
            $sql = "SELECT id_vehiculo_externo as id_vehiculo
            ,nro_placa
            FROM dayf_vehiculos_externos";
        }

        $p = $pdo->prepare($sql);
        $p->execute();
        $ficha = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($ficha as $f) {
            $sub_array = array(
                "id_vehiculo" => $f["id_vehiculo"],
                "nro_placa" => $f["nro_placa"],
            );
            $data[] = $sub_array;
        }

        echo json_encode($data);
    }

    //Opcion 4
    static function getVehiculo()
    {
        $tipo = $_GET["tipo"] ?? null;
        $placa = $_GET["placa"] ?? null;

        if ($tipo === null || $placa === null) {
            echo json_encode(["error" => "Parámetros tipo y placa requeridos"]);
            return;
        }

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tipo == 1) {
            $sql = "SELECT id_vehiculo, nro_placa, chasis, motor, modelo, observaciones, nombre_tipo_combustible, nombre_marca, nombre_tipo, nombre_estado, km_actual, nombre_persona_asignado
                    FROM xxx_dayf_vehiculos
                    WHERE id_vehiculo = ?";
        } else if ($tipo == 2) {
            $sql = "SELECT id_vehiculo_externo, nro_placa
                    FROM dayf_vehiculos_externos
                    WHERE id_vehiculo_externo = ?";
        } else {
            echo json_encode(["error" => "Tipo de vehículo inválido"]);
            return;
        }

        $p = $pdo->prepare($sql);
        $p->execute([$placa]);
        $vehiculos = $p->fetchAll(PDO::FETCH_ASSOC);
        $null = 'Sin Datos Vehiculo Arrendado';
        $data = [];
        foreach ($vehiculos as $v) {
            $sub_array = [
                "id_vehiculo" => $v["id_vehiculo"] ?? $null,
                "nro_placa" => $v["nro_placa"] ?? $null,
                "chasis" => $v["chasis"] ?? $null,
                "motor" => $v["motor"] ?? $null,
                "modelo" => $v["modelo"] ?? $null,
                "observaciones" => $v["observaciones"] ?? $null,
                "nombre_tipo_combustible" => $v["nombre_tipo_combustible"] ?? $null,
                "nombre_marca" => $v["nombre_marca"] ?? $null,
                "nombre_tipo" => $v["nombre_tipo"] ?? $null,
                "nombre_estado" => $v["nombre_estado"] ?? $null,
                "km_actual" => $v["km_actual"] ?? $null,
                "nombre_persona_asignado" => $v["nombre_persona_asignado"] ?? $null
            ];
            $data[] = $sub_array;
        }
        echo json_encode($data);
    }



    //Opcion 5
    public static function setnuevoServicio()
    {
        $campos = $_POST["formulario"];
        $fecha_actual = date("Y-m-d H:i:s"); // Formato correcto para SQL Server
        $tipo = $_POST["tipo"];
        $tipoV = '';
        if ($tipo == 1) {
            $tipoV = 'id_vehiculo';
        } else {
            $tipoV = 'id_vehiculo_externo';
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT count(nro_orden) as nro_orden
        from dayf_vehiculo_servicios
        where nro_orden = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($campos[0]));
        $nro = $p->fetch(PDO::FETCH_ASSOC);

        if ($nro['nro_orden'] > 0) {
            $yes = array('msg' => 'OK', 'id' => '1', 'message' => 'Nro de Serivicio ya Existente');
            echo json_encode($yes);
            Database::disconnect_sqlsrv();
            return;
        }

        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO dayf_vehiculo_servicios (nro_orden, id_tipo_servicio, recepcion_id_persona_entrega, $tipoV, km_actual,descripcion_solicitado, recepcion_id_persona_recibe, id_status, fecha_recepcion, id_taller)
            VALUES (?,?,?,?,?,?,?,?,?,?)";
            $p = $pdo->prepare($sql);
            $p->execute(array($campos[0], $campos[1], $campos[2], $campos[3], $campos[4], $campos[5], $_SESSION['id_persona'], 5487, $fecha_actual, 5));

            if ($tipo == 1) {
                $sql = "UPDATE dayf_vehiculos
                SET km_actual = ?, id_servicio = 1";
                $p = $pdo->prepare($sql);
                $p->execute(array($campos[3]));
            }

            $yes = array('msg' => 'OK', 'id' => '2', 'message' => 'Servicio Generado');
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

    //Opcion 6
    public static function finalizarServicio()
    {
        $campos = $_POST["formulario"];
        $fecha_actual = date("Y-m-d H:i:s"); // Formato correcto para SQL Server
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'UPDATE dayf_vehiculo_servicios
            SET entrega_id_persona_recibe = ?,  descripcion_realizado = ?, entrega_id_persona_entrega = ? , fecha_entregado = ? , id_status = ?
            WHERE id_servicio = ?';
            $p = $pdo->prepare($sql);
            $p->execute(array($campos[0], $campos[1], $_SESSION['id_persona'], $fecha_actual, 5489, $_POST["noServi"]));

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Servicio Generado');
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

    //Opcion 7
    static function getDescripciones()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_servicio
        ,[dbo].[RTF2Text](descripcion_solicitado) AS descripcion_solicitado
        FROM dayf_vehiculo_servicios
        where id_servicio = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["servicio"]));
        $descripcion = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($descripcion as $d) {
            $sub_array = array(
                "descripcion" => $d["descripcion_solicitado"],
            );
            $data[] = $sub_array;
        }
        echo json_encode($data);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Nuevo::getCargaTipoServicios();
            break;

        case 2:
            Nuevo::getFichas();
            break;

        case 3:
            Nuevo::getplacas();
            break;

        case 4:
            Nuevo::getVehiculo();
            break;

        case 5:
            Nuevo::setnuevoServicio();
            break;

        case 6:
            Nuevo::finalizarServicio();
            break;

        case 7:
            Nuevo::getDescripciones();
            break;
    }
}