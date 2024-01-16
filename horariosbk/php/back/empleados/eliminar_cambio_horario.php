<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $id_control = $_POST['id_control'];
    $flag_fijo = $_POST['flag_fijo'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = ($flag_fijo == 63 || $flag_fijo == 64) ? "UPDATE [SAAS_APP].[dbo].[tbl_control_permisos] SET estado = 7 WHERE id_control = " . $id_control : "UPDATE [SAAS_APP].[dbo].[tbl_persona_horario] SET estado = 0 WHERE id_control = " . $id_control;
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
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
