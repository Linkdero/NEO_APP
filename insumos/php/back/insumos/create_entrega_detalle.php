<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_doc_insumo=$_POST['id_doc_insumo'];
  $id_insumo_detalle=$_POST['id_insumo_detalle'];
  $id_doc_insumo_anterior=$_POST['id_doc_insumo_anterior'];
  $cantidad=$_POST['cantidad'];
  //$anotacion=$_POST['anotacion'];
  //$tipo=$_POST['tipo'];

  $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
  $id_bodega;
  foreach($datos AS $d){
    $id_bodega = $d['id_bodega_insumo'];
  }
  $data = array();

  $datos_=insumo::get_datos_insumo_by_id($id_insumo_detalle);

  //echo $id_bodega;
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO inv_movimiento_detalle
                      (
                        id_bodega_insumo,
                        id_doc_insumo,
                        id_prod_insumo,
                        id_prod_insumo_detalle,
                        cantidad_entregada
                      )
                      VALUES (?,?,?,?,?)";
  $q = $pdo->prepare($sql);
  $q->execute(array($id_bodega,$id_doc_insumo,$datos_['id_prod_insumo'],$id_insumo_detalle,$cantidad));

  $sql2 = "SELECT id_doc_insumo, id_tipo_movimiento FROM inv_movimiento_encabezado
           WHERE id_doc_insumo=?";
  $q2 = $pdo->prepare($sql2);
  $q2->execute(array($id_doc_insumo));
  $t_m = $q2->fetch();

  $tipo_asignacion;
  if($t_m['id_tipo_movimiento']==2 || $t_m['id_tipo_movimiento']==7){
    $flag_resguardo=0;
    $tipo_asignacion=5338;
    $sql5 = "UPDATE inv_movimiento_detalle
            SET cantidad_entregada=(ISNULL(cantidad_entregada,0)+?), flag_resguardo=?
            WHERE id_doc_insumo=? AND id_prod_insumo_detalle=?";
    $q5 = $pdo->prepare($sql5);
    $q5->execute(array($cantidad,$flag_resguardo,$id_doc_insumo_anterior,$id_insumo_detalle));
  }else
  if($t_m['id_tipo_movimiento']==3)
  {
    $tipo_asignacion=5339;
  }

  $sql1 = "UPDATE inv_producto_insumo_detalle
           SET id_status=?
           FROM inv_producto_insumo_detalle
           INNER JOIN inv_producto_insumo
           ON (inv_producto_insumo_detalle.id_prod_insumo = inv_producto_insumo.id_producto_insumo)
           WHERE inv_producto_insumo_detalle.id_prod_ins_detalle=?
           AND inv_producto_insumo.id_tipo_insumo  NOT IN (10,11,12,18,31,34,35,40,41,42,49)"; //que no sean cargadores audifonos etc
  $q1 = $pdo->prepare($sql1);
  $q1->execute(array($tipo_asignacion,$id_insumo_detalle));



  /*$sql__1 = "UPDATE inv_producto_insumo_detalle
           SET existencia=(ISNULL(existencia,0)-?)

           WHERE id_prod_ins_detalle=?
           ";
  $q__1 = $pdo->prepare($sql__1);
  $q__1->execute(array($cantidad,$id_insumo_detalle));*/

  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
