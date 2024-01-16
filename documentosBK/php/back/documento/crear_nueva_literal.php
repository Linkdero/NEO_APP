<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  //include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $docto_id=$_POST['docto_id'];
  $literal=$_POST['literal'];
  $titulo=$_POST['titulo'];
  $descripcion=$_POST['descripcion'];

  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //insert literal nueva
    $sql0="INSERT INTO docto_base_literal (base_id, base_literal_nom, base_literal_titulo, base_literal_descripcion, base_literal_status)
          VALUES(?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(8058,$literal,$titulo,$descripcion,1));
    //fin insert literal

    $literal_id = $pdo->lastInsertId();

    //asignar la nueva literal al documento
    $sql0="INSERT INTO docto_base_literal_asignacion (docto_id, base_id, base_literal_id)
          VALUES(?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($docto_id,8058,$literal_id));
    //fin asignación

    $pdo->commit();
  }catch (PDOException $e){
    echo $e;
    try{
      $pdo->rollBack();
    }catch(Exception $e2){
      echo $e2;
    }
  }

  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
