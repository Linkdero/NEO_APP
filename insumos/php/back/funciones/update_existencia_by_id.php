<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_producto=$_POST['pk'];
  $existencia=$_POST['value'];
  //$tipo=$_POST['tipo'];

  $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
  $id_bodega;
  foreach($datos AS $d){
    $id_bodega = $d['id_bodega_insumo'];
  }


  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE inv_producto_insumo_detalle SET existencia=? WHERE id_prod_ins_detalle=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(
    $existencia,
    $id_producto
  ));

  Database::disconnect_sqlsrv();
  echo $existencia;

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
