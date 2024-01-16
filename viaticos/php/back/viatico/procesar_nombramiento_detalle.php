<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  //$solicitante = $_SESSION['id_persona'];

  /*$clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($id_persona);*/

  $vt_nombramiento=$_POST['vt_nombramiento'];
  $reng_num=$_POST['reng_num'];
  $porcentaje=$_POST['porcentaje'];
  $monto=$_POST['monto'];
  $anticipo=$_POST['anticipo'];
  $cheque=$_POST['cheque'];

  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE vt_nombramiento_detalle
            SET
              id_estado_descuento=?,
              monto_descuento_anticipo=?,
              porcentaje_proyectado=?,
              monto_asignado=?,
              bln_anticipo=?,
              nro_cheque=?
            WHERE vt_nombramiento=? AND reng_num=?";
    $q = $pdo->prepare($sql);
    $q->execute(array(1186,0,$porcentaje,$monto,$anticipo,$cheque,$vt_nombramiento,$reng_num));

    $sql = "UPDATE vt_nombramiento
            SET fecha_procesado=?
            WHERE vt_nombramiento=?";
    $q = $pdo->prepare($sql);
    $q->execute(array(date('Y-m-d H:i:s'),$vt_nombramiento));

    $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  //echo json_encode($yes);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
