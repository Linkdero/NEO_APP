<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);

$id_persona=$_POST['id_persona'];
$puerta=$_POST['puerta'];


$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "UPDATE tbl_control_puerta_persona SET id_puerta=?
        WHERE id_persona=?";
$stmt = $pdo->prepare($sql);
try{
  $stmt->execute(array($puerta,$id_persona));
}
catch (Exception $e){
  echo $e;
}

//$response = $stmt->fetch();
Database::disconnect_sqlsrv();


 ?>
