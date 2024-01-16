<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');

  $cod_proveedor=$_POST['cod_proveedor'];
  $nombre_proveedor=$_POST['nombre_proveedor'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO vt_nombramiento_lugar (lugarNit, lugarNombre, lugarStatus) VALUES (?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $cod_proveedor,
        $nombre_proveedor,
        1
      ));

    $pdo->commit();
    $yes = array('msg'=>'OK','message'=>'Proveedor agregado');
  }catch (PDOException $e){
    $yes = array('msg'=>'ERROR','message'=>$e);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','message'=>$e2);
    }
  }

  Database::disconnect_sqlsrv();

  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
