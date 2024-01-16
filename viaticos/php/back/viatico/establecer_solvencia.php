<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  //$solicitante = $_SESSION['id_persona'];

  /*$clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($id_persona);*/

  $vt_nombramiento=$_POST['vt_nombramiento'];
  $id_empleado=$_POST['id_persona'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE vt_nombramiento_detalle SET solvencia=? WHERE vt_nombramiento=? AND id_empleado=?";
    $p = $pdo->prepare($sql);
    $p->execute(array(1,$vt_nombramiento,$id_empleado));// 65 es el id de aplicaciones
    //kardex = $p->fetchAll();
    Database::disconnect_sqlsrv();
  



}else{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
