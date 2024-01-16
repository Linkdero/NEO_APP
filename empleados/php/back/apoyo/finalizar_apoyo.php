<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_contratos.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona=$_POST['id_persona'];
  $tipo_persona = $_POST['tipo_persona'];
  //echo $id_empleado;
  $clase=new contrato;
  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($tipo_persona == 1052){
      $sql1 = "UPDATE rrhh_persona
                SET id_status=?
                WHERE id_persona=?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array(5611, $id_persona));
    }else if($tipo_persona == 9103){
      //inicio
      $sql1 = "UPDATE rrhh_persona
                SET id_status=?
                WHERE id_persona=?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array(9189, $id_persona));      
      //fin
    }


  $pdo->commit();
  }catch (PDOException $e){
    echo $e;
    try{ $pdo->rollBack();}catch(Exception $e2){
      echo $e2;
    }
  }

  Database::disconnect_sqlsrv();

  else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
