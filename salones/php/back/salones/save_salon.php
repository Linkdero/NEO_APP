<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../functions.php';


    if ($_POST["opcion"] == 0) { //CREATE
        $nombre = $_POST['nombre'];
        $ubicacion = $_POST['ubicacion'];
        $capacidad = $_POST['capacidad'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO sal_salones (nombre, capacidad, ubicacion, id_usuario_mod, fecha_mod, id_usuario_ing, fecha_ing, estado)
                 VALUES (?,?,?,?,GETDATE(),?,GETDATE(),1)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($nombre, $capacidad, $ubicacion, $_SESSION["id_persona"], $_SESSION["id_persona"]))) {
            echo json_encode(array(
                "status" => true,
                "msg" => "Ok"
            ));
        } else {
            echo json_encode(array(
                "status" => false,
                "msg" => "Error"
            ));
        }
        Database::disconnect_sqlsrv();
    } else if ($_POST["opcion"] == 1) { //UPDATE
        $id_salon = $_POST['id'];
        $nombre = $_POST['nombre'];
        $ubicacion = $_POST['ubicacion'];
        $capacidad = $_POST['capacidad'];
        $estado = $_POST['estado'];

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE sal_salones SET nombre = ?, ubicacion = ?, capacidad = ?, estado = ?, fecha_mod = GETDATE(), id_usuario_mod = ?
                WHERE id_salon = ?;";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($nombre, $ubicacion, $capacidad, $estado, $_SESSION["id_persona"], $id_salon))) {
            if ($estado == 0) {
                $motivo = $_POST['motivo'];
                $sqlDetalle = "INSERT INTO sal_salones_detalle(id_salon, motivo, id_usuario_mod, fecha_mod, id_usuario_ing, fecha_ing, estado) VALUES(?,?,?,GETDATE(),?,GETDATE(),1);";
                $stmtDetalle = $pdo->prepare($sqlDetalle);
                if ($stmtDetalle->execute(array($id_salon, $motivo, $_SESSION["id_persona"], $_SESSION["id_persona"], 1))) {
                    $response = array(
                        "status" => true,
                        "msg" => "Ok"
                    );
                } else {
                    $response = array(
                        "status" => false,
                        "msg" => "Error"
                    );
                }
            } else {
                $response = array(
                    "status" => true,
                    "msg" => "Ok"
                );
            }
        } else {
            $response = array(
                "status" => false,
                "msg" => "Error"
            );
        }
        echo json_encode($response);
        Database::disconnect_sqlsrv();
    }
} else {
    echo "<script type='text/javascript'> window.location='principal'; </script>";
}
