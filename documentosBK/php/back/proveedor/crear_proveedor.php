<?php


ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $proveedor_nit=$_POST['proveedor_nit'];
  $proveedor_nombre=$_POST['proveedor_nombre'];
  $id_tipo_proveedor=$_POST['id_tipo_proveedor'];

  //$uni_cor = documento::get_unidad_pos($unidad);
  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO APP_POS.dbo.PROVEEDOR (
      Prov_id, Prov_nom, Prov_est, Prov_tipo
    )
    VALUES(?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $proveedor_nit,
        $proveedor_nombre,
        1,
        $id_tipo_proveedor
      )
    );

    $yes = array('msg'=>'OK');
    echo json_encode($yes);
    $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  //echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
