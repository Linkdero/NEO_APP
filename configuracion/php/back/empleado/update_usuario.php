<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);

$id_persona=$_POST['id_persona'];
$tipo=$_POST['tipo'];
$flag=$_POST['flag'];
echo(json_encode($tipo));
$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = ($tipo=='status' ?  "UPDATE [SAAS_APP].[dbo].[rrhh_persona_usuario] SET status = ? WHERE id_persona=?" : "UPDATE [SAAS_APP].[dbo].[rrhh_persona_usuario] SET valida_ldap = ? WHERE id_persona=?");
$q = $pdo->prepare($sql);
$q->execute(array($flag, $id_persona));
Database::disconnect_sqlsrv();

 ?>
