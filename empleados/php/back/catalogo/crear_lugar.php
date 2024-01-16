<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $id_municipio=$_POST['municipio'];
  $id_lugar=$_POST['poblado'];
  $id_tipo_lugar=$_POST['id_tipo_lugar'];

  $year = date('Y');

  $clase=new empleado;
  $pdo = Database::connect_sqlsrv();
  $yes = '';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO tbl_aldea (id_municipio, nombre, id_tipo_lugar)
             VALUES (?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_municipio,$id_lugar,$id_tipo_lugar));

    $yes = array('msg'=>'OK','id'=>'');

    $pdo->commit();
    }catch (PDOException $e){
      $yes = array('msg'=>'ERROR','id'=>$e);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','id'=>$e2);
      }
    }

    echo json_encode($yes);
  else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
  endif;


?>
