<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class Servicios
{
    public $servicios;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->servicios = array();
    }

    //Opcion 1
    static function getMecanicos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT nombre
        ,id_persona
        from xxx_rrhh_Ficha
        where id_depto_funcional = 160 and estado = 1";

        $p = $pdo->prepare($sql);
        $p->execute();
        $usuario = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($usuario as $u) {
            $sub_array = array(
                "nombre" => $u["nombre"],
                "id_persona" => $u["id_persona"]
            );
            $data[] = $sub_array;
        }
        echo json_encode($data);
    }

    //Opcion 2
    public static function setMecanicos()
    {
        $fecha_actual = date("Y-m-d H:i:s"); // Formato correcto para SQL Server
        $campos = [$_POST['mecanico'], $fecha_actual, 5488, $_POST['noServi']];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $yes = '';

        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'UPDATE dayf_vehiculo_servicios
            SET id_mecanico_asignado = ?,  fecha_asignacion_mecanico = ?, id_status = ?
            WHERE id_servicio = ?';
            $p = $pdo->prepare($sql);
            $p->execute(array($campos[0], $campos[1], $campos[2], $campos[3]));

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Mecanico Asignado');
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
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Servicios::getMecanicos();
            break;

        case 2:
            Servicios::setMecanicos();
            break;
    }
}