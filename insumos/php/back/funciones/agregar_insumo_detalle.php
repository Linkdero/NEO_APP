<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_prod_insumo=$_POST['id_producto_insumo'];
  $serie=$_POST['serie'];
  $codigo_inventarios=$_POST['sicoin'];
  $existencia=$_POST['existencia'];

  $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
  $id_bodega='';
  foreach($datos AS $d){
    $id_bodega = $d['id_bodega_insumo'];
  }
  $data = array();

  //$datos_=insumo::get_datos_insumo_by_id($id_insumo_detalle);

  //echo $id_bodega;
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO inv_producto_insumo_detalle
                      (
                        id_bodega_insumo,
                        id_prod_insumo,
                        id_status,
                        numero_serie,
                        codigo_inventarios,
                        existencia,
                        fecha_creacion
                      )
                      VALUES (?,?,?,?,?,?,?)";
  $q = $pdo->prepare($sql);
  $q->execute(array($id_bodega,
                    $id_prod_insumo,
                    5337,
                    $serie,
                    $codigo_inventarios,
                    $existencia,
                    date('Y-m-d H:i:s')));

                    $sql2 = "SELECT MAX(id_prod_ins_detalle) AS id FROM inv_producto_insumo_detalle
                             WHERE id_bodega_insumo=? AND id_prod_insumo=?";
                    $q2 = $pdo->prepare($sql2);
                    $q2->execute(array($id_bodega,$id_prod_insumo));
                    $codigo = $q2->fetch();


                    $code = $codigo['id'];
                    Database::disconnect_sqlsrv();
                    echo $code;


  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
