<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../../empleados/php/back/functions.php';
  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');
  $clase = new viaticos;

  $vt_nombramiento=$_POST['id_viatico'];
  $id_persona=$_POST['id_persona'];

  //$parametros=str_replace("'","()",$renglon);
  //$bln_confirma=$_POST['confirma'];

  $parametros = substr($id_persona, 1);

  $yes = '';

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reintegro_hospedaje=$_POST['id_reintegro_hospedaje'];
    $reintegro_alimentacion=$_POST['id_reintegro_alimentacion'];
    $sql = "UPDATE vt_nombramiento_detalle
              SET
                hospedaje=?,
                reintegro_alimentacion=?

              WHERE vt_nombramiento=? AND id_empleado IN ($parametros)";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $reintegro_hospedaje,
        $reintegro_alimentacion,
        $vt_nombramiento
      ));

    $pdo->commit();
    $yes = array('msg'=>'OK','message'=>'Facturas actualizadas');
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
