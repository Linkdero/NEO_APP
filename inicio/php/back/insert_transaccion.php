<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$data = array();
$fecha = date('Y-m-d H:i:s');
$invitado=$_POST['invitado'];
$observaciones=$_POST['observaciones'];

$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query0 = "SELECT COUNT(*) AS CONTEO
          FROM TRANSACCION
          WHERE Inv_id=? AND CONVERT(date, Tra_ent)=?";
$statement0 = $pdo->prepare($query0);
$statement0->execute(array($invitado,date('Y-m-d')));
$guest = $statement0->fetch();
Database::disconnect_sqlsrv();
$valor=$guest['CONTEO']+1;
$tipo=0;
//if ($valor%2==0){
if($valor==3 || $valor==4){
  $tipo=1;
}

$query = "INSERT INTO TRANSACCION (Inv_id, Tra_ent,Tra_obs,Pnt_id, Tra_sta,tipo_registro)
          VALUES (?,?,?,?,?,?)";
$statement = $pdo->prepare($query);
$statement->execute(array($invitado,$fecha,$observaciones,$_SESSION['Punto'],1,$tipo));

Database::disconnect_sqlsrv();
?>
