<?php
include_once '../../../../inc/functions.php';
sec_session_start();

if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';


    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_control = $_POST['pk'];
    $val = $_POST['value'];
    $opcion = $_POST['name'];
    if ($opcion == 1) {
        $sql = "UPDATE  [SAAS_APP].[dbo].[tbl_control_permisos] SET nro_boleta = " . $val . " WHERE id_control = " . $id_control;
    } else if ($opcion == 2) {
        $val = "'" . $val . "'";
        $sql = "UPDATE  [SAAS_APP].[dbo].[tbl_control_permisos] SET observaciones = " . $val . " WHERE id_control = " . $id_control;
    } else if ($opcion == 3) {
    } else if ($opcion == 4) {
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

else : ?>
    <script type='text/javascript'>
        window.location = 'principal';
    </script>
<?php endif; ?>