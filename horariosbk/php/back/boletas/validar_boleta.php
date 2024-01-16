<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $id_control = $_POST['id_control'];
    $control_id = $_POST['control_id'];
    $obs = $_POST['obs'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($obs == '') {
        $sql = "UPDATE  [SAAS_APP].[dbo].[tbl_control_permisos] SET estado = " . $control_id . " WHERE id_control = " . $id_control;
    } else {
        $obs = "'" . $obs . "'";
        $sql = "UPDATE  [SAAS_APP].[dbo].[tbl_control_permisos] SET estado = " . $control_id . ", observaciones =  " . $obs . " WHERE id_control = " . $id_control;
    }


    // echo $sql;
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
