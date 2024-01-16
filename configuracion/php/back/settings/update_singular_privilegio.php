<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);

$pantalla_acceso=$_POST['pantalla_acceso'];
$flag=$_POST['flag'];
$valor=$_POST['valor'];
$parts = explode("-",$pantalla_acceso);
$acceso = $parts['0'];
$pantalla =$parts['1'];

$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "UPDATE tbl_accesos_usuarios_det SET $flag = ? WHERE id_acceso=? AND id_pantalla=?";
$q = $pdo->prepare($sql);
$q->execute(array($valor,$acceso,$pantalla));
Database::disconnect_sqlsrv();

 ?>
