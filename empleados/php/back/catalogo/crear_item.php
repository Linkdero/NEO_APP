<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $id_catalogo=$_POST['id_catalogo'];
  $item_name=$_POST['item_name'];

  $year = date('Y');

  $clase=new empleado;
  $pdo = Database::connect_sqlsrv();
  $yes = '';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "INSERT INTO tbl_catalogo_detalle (id_catalogo, id_status, descripcion, descripcion_corta, id_ref_tipo)
             VALUES (?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_catalogo,1,$item_name,$item_name, 1174));

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
