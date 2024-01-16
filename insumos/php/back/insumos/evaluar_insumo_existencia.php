<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_producto=$_POST['id_producto'];
  $cantidad=$_POST['cantidad'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                 a.id_prod_ins_detalle, a.id_status,
                 a.numero_serie, a.flag_asignado, a.flag_resguardo,a.flag_uso_compartido,
                 a.id_propietario,a.existencia,b.id_tipo_insumo,a.existencia

          FROM inv_producto_insumo_detalle a
          INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
          INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
         WHERE a.id_prod_ins_detalle=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($id_producto));// 65 es el id de aplicaciones
  $producto = $p->fetch();
  Database::disconnect_sqlsrv();

  if($cantidad<=$producto['existencia']){
    echo 'true';
  }else{
    echo 'false';
  }






else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
