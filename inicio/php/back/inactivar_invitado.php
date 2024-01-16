<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
//$data = array();
//$fecha = date('Y-m-d H:i:s');
$invitado=$_POST['invitado'];
$estado=$_POST['estado'];
//$observaciones=$_POST['observaciones'];

$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$query = "UPDATE INVITADO SET Inv_activo=? WHERE Inv_id=?";
$statement = $pdo->prepare($query);
$statement->execute(array($estado,$invitado));

Database::disconnect_sqlsrv();
?>
