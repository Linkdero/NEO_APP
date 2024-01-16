<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../functions.php';
    date_default_timezone_set('America/Guatemala');

    $estado = $_POST['estado'];
    $id_solicitud = $_POST['id_solicitud'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE sal_solicitudes
            SET estado = ?, id_usuario_mod = ?, fecha_mod = GETDATE()
            WHERE id_solicitud = ?;";

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($estado, $_SESSION["id_persona"], $id_solicitud))) {
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

else :
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
