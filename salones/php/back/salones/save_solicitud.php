<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../functions.php';
    date_default_timezone_set('America/Guatemala');

    $id_salon = $_POST['id_salon'];
    $str_fecha_inicio = $_POST['fecha_inicio'];
    $str_fecha_fin = $_POST['fecha_fin'];
    $motivo = $_POST['motivo'];
    $audiovisuales = $_POST['audiovisuales'];
    $mobiliario = $_POST['mobiliario'];

    $fecha_inicio = str_replace("T", " ", $str_fecha_inicio);
    $fecha_inicio .= ":00";
    $fecha_fin = str_replace("T", " ", $str_fecha_fin);
    $fecha_fin .= ":00";
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO sal_solicitudes (id_salon, fecha_inicio, fecha_fin, motivo, id_usuario_solicitud, id_usuario_mod, fecha_mod, id_usuario_ing, fecha_ing, estado)
             VALUES (?,?,?,?,?,?,GETDATE(),?,GETDATE(),1)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($id_salon, $fecha_inicio, $fecha_fin, $motivo, $_SESSION["id_persona"], $_SESSION["id_persona"], $_SESSION["id_persona"]))){
        if($mobiliario != null || $audiovisuales != null){
            $id_solicitud = $pdo->lastInsertId();
            $sqlDetalle = "INSERT INTO sal_detalle_solicitud (id_solicitud, audiovisuales, mobiliario, id_usuario_mod, fecha_mod, id_usuario_ing, fecha_ing, estado)
                    VALUES (?,?,?,?,GETDATE(),?,GETDATE(),1)";
            $stmtDetalle = $pdo->prepare($sqlDetalle);
            if($stmtDetalle->execute(array($id_solicitud, $audiovisuales, $mobiliario, $_SESSION["id_persona"], $_SESSION["id_persona"]))){
                echo json_encode(array(
                    "status" => true,
                    "msg" => "Ok"
                ));
            }else{
                echo json_encode(array(
                    "status" => false,
                    "msg" => "Error"
                ));
            }          
        }else{
            echo json_encode(array(
                "status" => true,
                "msg" => "Ok"
            ));
        }
    }else{
        echo json_encode(array(
            "status" => false,
            "msg" => "Error"
        ));
    }
    Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
