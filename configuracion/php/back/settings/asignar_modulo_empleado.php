<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);

$persona=$_POST['persona'];
$modulo=$_POST['modulo'];

$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//creando el nuevo acceso
$sql_1 = "INSERT INTO tbl_accesos_usuarios
          (id_sistema,id_persona,id_tipo_usuario,id_status)
          VALUES(?,?,?,?)";
$q_1 = $pdo->prepare($sql_1);
$q_1->execute(array($modulo,$persona,1117,1119)); //1117 Usuario Operador, 1120 status Activo

//seleccionando el acceso mas reciente del empleado
$sql_2 = "SELECT TOP 1 id_acceso FROM tbl_accesos_usuarios
          WHERE id_persona=? ORDER BY id_acceso DESC";
$q_2 = $pdo->prepare($sql_2);
$q_2->execute(array($persona));
$acceso = $q_2->fetch();

//Obtener las pantallas del modulo a asignar
$sql_3 = "SELECT id_pantalla FROM tbl_pantallas WHERE id_sistema=?";
$q_3 = $pdo->prepare($sql_3);
$q_3->execute(array($modulo));
$pantallas = $q_3->fetchAll();

//generando las pantallas por acceso
$x=0;
foreach($pantallas as $p){
  $x+=1;
  $sql_4 = "INSERT INTO tbl_accesos_usuarios_det(id_acceso,reng_num,id_pantalla)
            VALUES(?,?,?)";
  $q_4 = $pdo->prepare($sql_4);
  $q_4->execute(array($acceso['id_acceso'],$x,$p['id_pantalla']));

}

Database::disconnect_sqlsrv();
echo 'Message script';

 ?>
