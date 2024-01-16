<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);

$acceso=$_POST['acceso'];
$tipo=$_POST['tipo'];
$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = ($tipo==0 ?  "UPDATE tbl_accesos_usuarios SET id_status = 1120 WHERE id_acceso=?" : "UPDATE tbl_accesos_usuarios SET id_status = 1119 WHERE id_acceso=?");
$q = $pdo->prepare($sql);
$q->execute(array($acceso));
Database::disconnect_sqlsrv();

 ?>
