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
  $id_empleado=$_POST['id_empleado'];
  $bln_cheque=$_POST['bln_cheque'];
  $sueldo=5000;

  $clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($id_empleado);

  if($e['id_tipo']==1){
    //011
    $sueldo=11;
  }else
  if($e['id_tipo']==2){
    //031
    $sueldo=31;
  }

  //echo $id_bodega;
  /*$pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO vt_nombramiento_detalle
                      (
                        vt_nombramiento,
                        reng_num,
                        id_empleado,
                        bln_confirma,
                        bln_cheque,
                        sueldo
                      )
                      VALUES(?,?,?,?,?,?)";
   $q = $pdo->prepare($sql);
   $q->execute(
     array(
       $vt_nombramiento,
       $reng_num,
       $id_empleado,
       1,
       $bln_cheque,
       $sueldo
     ));*/

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "EXEC sp_ins_detalle_nombramiento @nombramiento=?, @empleado=?, @cheque=?,@reemplazo=?,@p=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($vt_nombramiento,$id_empleado,$bln_cheque,0,0));// 65 es el id de aplicaciones
    //kardex = $p->fetchAll();
    Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
