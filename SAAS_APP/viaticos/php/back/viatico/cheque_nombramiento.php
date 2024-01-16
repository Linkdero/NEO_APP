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
  $tipo=$_POST['tipo'];
  $vt_nombramiento=$_POST['vt_nombramiento'];
  $estado=$_POST['estado'];
  $cheque=$_POST['cheque'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($tipo==1){
    $sql0 = "EXEC sp_sel_valida_cheque ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
       array(
         $cheque
       ));
    $cheque_ = $q0->fetchAll();

    if(count($cheque_)>0){
      echo 'El número de cheque ya fue entregado';
    }else{
      echo 'ok';
      $sql = "UPDATE vt_nombramiento SET id_status=?, nro_cheque=? WHERE vt_nombramiento=?";
       $q = $pdo->prepare($sql);
       $q->execute(array($estado,$cheque,$vt_nombramiento));


    }

  }else{
    echo 'ok';
    $sql = "UPDATE vt_nombramiento SET id_status=?, nro_cheque=? WHERE vt_nombramiento=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($estado,$cheque,$vt_nombramiento));

  }



  //echo $id_bodega;



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
