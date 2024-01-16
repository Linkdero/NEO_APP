<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $id_tipo_curso=$_POST['id_tipo_curso'];
  $id_centro_capacitacion=$_POST['id_centro_capacitacion'];
  $id_nombre_curso=strtoupper($_POST['id_nombre_curso']);
  $year = date('Y');

  $clase=new empleado;
  $pdo = Database::connect_sqlsrv();
  $yes = '';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "INSERT INTO rrhh_persona_curso (id_tipo_curso,ubica_curso,nombre_curso)
             VALUES (?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_tipo_curso,$id_centro_capacitacion,$id_nombre_curso));

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
