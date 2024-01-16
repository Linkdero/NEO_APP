<?php
include_once '../../../../inc/functions.php';
// set_time_limit(0);

$id_pantalla=$_POST['id_modulo'];
$desc=$_POST['desc'];
$pant=$_POST['pant'];
$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$log = "VALUES(".$id_pantalla.", '".$desc."', '".$pant."', 1, 0, '', 1, 1)";
$sql = "INSERT INTO [SAAS_APP].[dbo].[tbl_pantallas]
        (id_sistema, descripcion, descrip_corta, flag_mostrar_menu, posicion, icono, id_activo, id_solicita_permiso)
        ".$log;
$q = $pdo->prepare($sql);
$q->execute();
Database::disconnect_sqlsrv();

 ?>
