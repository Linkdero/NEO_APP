<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_tipo_insumo=$_POST['id_tipo_insumo'];
  $id_sub_tipo_insumo=$_POST['id_sub_tipo_insumo'];
  $id_marca=$_POST['id_marca'];
  $id_modelo=$_POST['id_modelo'];
  $descripcion =$_POST['descripcion'];
  //$tipo=$_POST['tipo'];

    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    $id_bodega;
    foreach($datos AS $d){
      $id_bodega = $d['id_bodega_insumo'];

    }
    $data = array();


        //echo $id_bodega;
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO inv_producto_insumo
                (id_bodega_insumo,
                 descripcion,
                 descripcion_corta,
                 id_marca,
                 id_modelo,
                 id_status,
                 id_tipo_insumo,
                 id_sub_tipo_insumo
               )VALUES(?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array(
          $id_bodega,
          $descripcion,
          $descripcion,
          $id_marca,
          $id_modelo,
          5337,
          $id_tipo_insumo,
          $id_sub_tipo_insumo
        ));

        $sql2 = "SELECT MAX(id_producto_insumo) AS id FROM inv_producto_insumo
                 WHERE id_bodega_insumo=?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($id_bodega));
        $codigo = $q2->fetch();


        $code = $codigo['id'];
        Database::disconnect_sqlsrv();
        echo $code;






else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
