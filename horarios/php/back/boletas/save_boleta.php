<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');

    $id_persona = $_POST['id_persona'];
    $tipo = $_POST['tipo'];
    $observaciones = $_POST['observaciones'];
    $inicio = date("Y-m-d H:i", strtotime($_POST['inicio']));
    $fin = date("Y-m-d H:i", strtotime($_POST['fin']));
    $estado = $_POST['estado'];


    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO [SAAS_APP].[dbo].[tbl_control_permisos] (id_persona, id_catalogo, fecha_inicio, fecha_fin, id_persona_mod, id_persona_ing, fecha_mod, fecha_ing, observaciones, estado) 
            VALUES(".$id_persona.", ".$tipo.", CONVERT(DATETIME, '".$inicio."', 102), CONVERT(DATETIME, '".$fin."', 102), ".$_SESSION['id_persona'].", ".$_SESSION['id_persona'].", GETDATE(), GETDATE(), '".$observaciones."', 1)";

    // echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
