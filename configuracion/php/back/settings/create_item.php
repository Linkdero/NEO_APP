<?php
include_once '../../../../inc/functions.php';
// set_time_limit(0);

$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$log = "VALUES(65, '".$nombre."', '".$descripcion."');";
$sql = "INSERT INTO [SAAS_APP].[dbo].[tbl_catalogo_detalle]
(id_catalogo, descripcion_corta, descripcion)
".$log;
$q = $pdo->prepare($sql);
$q->execute(array($nombre,$descripcion));
Database::disconnect_sqlsrv();

 ?>
