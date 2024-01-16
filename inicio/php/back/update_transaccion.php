<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$data = array();
$fecha = date('Y-m-d H:i:s');
$invitado=$_POST['invitado'];
$nombre=$_POST['nombre'];
$institucion=$_POST['institucion'];

$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = "UPDATE INVITADO SET Inv_nom=?, Inv_pro=?, Inv_activo=?
          WHERE Inv_id=?";
$statement = $pdo->prepare($query);
$statement->execute(array($nombre,$institucion,1,$invitado));

Database::disconnect_sqlsrv();
?>
