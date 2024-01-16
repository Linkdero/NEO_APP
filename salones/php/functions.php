<?php

class Salon
{

    function get_salones()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT S.id_salon, S.nombre, S.capacidad, S.ubicacion, P.primer_nombre, P.primer_apellido, convert(varchar, S.fecha_mod, 103) as fecha, estado
                    FROM sal_salones S
                    LEFT JOIN rrhh_persona P ON S.id_usuario_mod = P.id_persona;";

        $stmt = $pdo->prepare($query);
        if ($stmt->execute()) {
            $salones = $stmt->fetchAll();
        } else {
            $salones = [];
        }
        Database::disconnect_sqlsrv();
        return $salones;
    }

    function get_salon_by_id($id_salon)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id_salon, nombre, capacidad, ubicacion, estado
                    FROM sal_salones
                    WHERE id_salon = :id;";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id_salon);
        if ($stmt->execute()) {
            $salon = $stmt->fetch();
        } else {
            $salon = [];
        }
        Database::disconnect_sqlsrv();
        return $salon;
    }

    function get_solicitudes()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT S.id_solicitud, SL.nombre, P.primer_nombre, P.primer_apellido, S.motivo, CONVERT(varchar, S.fecha_inicio, 22) AS fecha_inicio, CONVERT(varchar, S.fecha_fin,22) AS fecha_fin, fecha_inicio as date_start, fecha_fin as date_end, S.estado, D.audiovisuales, D.mobiliario
                    FROM sal_salones SL
                    INNER JOIN sal_solicitudes S ON SL.id_salon = S.id_salon
                    LEFT JOIN sal_detalle_solicitud D ON S.id_solicitud = D.id_solicitud
                    LEFT JOIN rrhh_persona P ON S.id_usuario_solicitud = P.id_persona
                    WHERE S.estado!=0";

        $stmt = $pdo->prepare($query);
        //$stmt->bindParam(':usuario', $_SESSION["id_persona"]);
        if ($stmt->execute()) {
            $reservaciones = $stmt->fetchAll();
        } else {
            $reservaciones = [];
        }
        Database::disconnect_sqlsrv();
        return $reservaciones;
    }
    function get_all_solicitudes()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT S.id_solicitud, SL.nombre, P.primer_nombre, P.primer_apellido, S.motivo, CONVERT(varchar, S.fecha_inicio, 22) AS fecha_inicio, CONVERT(varchar, S.fecha_fin,22) AS fecha_fin, fecha_inicio as date_start, fecha_fin as date_end, S.estado, D.audiovisuales, D.mobiliario
                    FROM sal_salones SL
                    INNER JOIN sal_solicitudes S ON SL.id_salon = S.id_salon
                    LEFT JOIN sal_detalle_solicitud D ON S.id_solicitud = D.id_solicitud
                    LEFT JOIN rrhh_persona P ON S.id_usuario_solicitud = P.id_persona";

        $stmt = $pdo->prepare($query);
        //$stmt->bindParam(':usuario', $_SESSION["id_persona"]);
        if ($stmt->execute()) {
            $reservaciones = $stmt->fetchAll();
        } else {
            $reservaciones = [];
        }
        Database::disconnect_sqlsrv();
        return $reservaciones;
    }

    function verify_date($fecha, $id_salon)
    {

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = " SELECT *
                    FROM sal_solicitudes
                    WHERE id_salon =:salon AND estado = 2 AND '" . $fecha . "' BETWEEN convert(varchar, fecha_inicio, 20) AND convert(varchar, fecha_fin, 20)
                    OR '" . $fecha . "' = convert(varchar, fecha_inicio, 20) OR '" . $fecha . "' = convert(varchar, fecha_fin, 20);";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":salon", $id_salon);
        if ($stmt->execute()) {
            $solicitudes = count($stmt->fetchAll());
            if ($solicitudes > 0) {
                $response = array(
                    "status" => "bussy",
                    "msg" => "Salón ocupado a esa hora"
                );
            } else {
                $response = array(
                    "status" => "free",
                    "msg" => ""
                );
            }
        } else {
            $response = array(
                "status" => "error",
                "msg" => ""
            );
        }
        Database::disconnect_sqlsrv();
        return $response;
    }
}
