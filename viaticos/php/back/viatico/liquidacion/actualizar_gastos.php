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

    $sql = "SELECT SUM(a.factura_monto) AS factura_monto
            FROM vt_nombramiento_factura a
            INNER JOIN vt_nombramiento_dia_comision b ON a.dia_id = b.dia_id

              WHERE b.vt_nombramiento=? AND b.id_empleado IN ($parametros) AND a.bln_confirma = ? AND a.factura_tipo = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $vt_nombramiento, 1, 3
      ));
    $fact = $q->fetch();

    $sql = "UPDATE vt_nombramiento_detalle
              SET
                otros_gastos = ?

              WHERE vt_nombramiento=? AND id_empleado IN ($parametros)";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $fact['factura_monto'], $vt_nombramiento
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
