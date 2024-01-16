<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$idPersona = $_SESSION['id_persona'];
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

if (function_exists('verificar_session') && verificar_session() != true) {
    echo "<script type='text/javascript'> window.location='principal'; </script>";
}

class Ingreso
{
    //Opcion
    public static function validarLote()
    {
        $lote1 = $_GET["lote1"];
        $lote2 = $_GET["lote2"];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(id_cupon) as cupon
        FROM SAAS_APP.dbo.dayf_cupones
        where nro_cupon BETWEEN ? AND ?";
        $p = $pdo->prepare($sql);
        $p->execute(array($lote1, $lote2));
        $yes = $p->fetch();

        echo json_encode($yes);
        Database::disconnect_sqlsrv();
    }

    public static function ingresarLote()
    {
        $lote = $_GET["lote"];
        $rango = $_GET["rango"];
        $monto = $_GET["monto"];
        $observaciones = $_GET["observaciones"];
        $fecha = date("Y-m-d h:i:s");
        $documento = $_GET["documento"];
        $total = ($rango + 1) * $monto;
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO SAAS_APP.dbo.dayf_cupones_documento (id_tipo_documento, id_estado_documento, fecha, descripcion, nro_documento, id_persona_opero,total)
                    VALUES (?,?,?,?,?,?,?);";
            $p = $pdo->prepare($sql);
            $p->execute(array(4351, 4348, $fecha, $observaciones, $documento, $GLOBALS["idPersona"], $total));

            $sql = "SELECT TOP (1) id_documento
            FROM SAAS_APP.dbo.dayf_cupones_documento
            order by id_documento desc";
            $p = $pdo->prepare($sql);
            $p->execute();
            $idIngreso = $p->fetch();

            for ($i = 0; $i <= $rango; $i++) {
                $sql = "INSERT INTO SAAS_APP.dbo.dayf_cupones (nro_cupon, id_ingreso_cupon, monto, id_estado_cupon)
                VALUES (?,?,?,?);";
                $p = $pdo->prepare($sql);
                $p->execute(array($lote, $idIngreso["id_documento"], $monto, 1913));
                $lote++;
            }
            $lote = $_GET["lote"];
            $sql = "SELECT TOP (1) [id_cupon]
            FROM [SAAS_APP].[dbo].[dayf_cupones]
            WHERE nro_cupon = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($lote));
            $idCupon = $p->fetch();

            for ($i = 0; $i <= $rango; $i++) {
                $sql = "INSERT INTO SAAS_APP.dbo.dayf_cupones_documento_detalle (id_documento, id_cupon)
                VALUES (?,?);";
                $p = $pdo->prepare($sql);
                $p->execute(array($idIngreso["id_documento"], $idCupon["id_cupon"]));
                $idCupon++;
            }

            $yes = array('msg' => 'OK', 'id' => '', 'message' => 'Lote ingresado');
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
            //Para el Datatable
        case 1:
            Ingreso::validarLote();
            break;

        case 2:
            Ingreso::ingresarLote();
            break;
    }
}
