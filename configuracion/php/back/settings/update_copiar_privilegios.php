<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);
$modulo=$_POST['modulo'];
$acceso_origen=$_POST['acceso_origen'];
$acceso_destino=$_POST['acceso_destino'];

$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql1 ="SELECT id_acceso, reng_num, id_pantalla, flag_es_menu,
              flag_insertar,flag_eliminar,flag_actualizar,flag_imprimir,
              flag_acceso,flag_autoriza,flag_descarga
       FROM tbl_accesos_usuarios_det
       WHERE id_acceso=?";
$q1 = $pdo->prepare($sql1);
$q1->execute(array($acceso_origen));
$privilegios = $q1->fetchAll();

foreach($privilegios as $p){
  $sql ="UPDATE tbl_accesos_usuarios_det
         SET flag_es_menu=?,
             flag_insertar=?,
             flag_eliminar=?,
             flag_actualizar=?,
             flag_imprimir=?,
             flag_acceso=?,
             flag_autoriza=?,
             flag_descarga=?
         WHERE id_acceso=?
         AND id_pantalla=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(
                  $p['flag_es_menu'],
                  $p['flag_insertar'],
                  $p['flag_eliminar'],
                  $p['flag_actualizar'],
                  $p['flag_imprimir'],
                  $p['flag_acceso'],
                  $p['flag_autoriza'],
                  $p['flag_descarga'],
                  $acceso_destino,
                  $p['id_pantalla'],));
}

Database::disconnect_sqlsrv();
//echo 'Message '.$acceso_origen.' - - - - '.$acceso_destino;

 ?>
