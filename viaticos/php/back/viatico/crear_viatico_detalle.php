<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
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
  $direccion = $_POST['direccion'];
  $sueldo=5000;


  if (is_numeric($id_empleado)) {
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

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT  MAX (a.nro_nombramiento) AS id FROM vt_nombramiento_detalle a
             INNER JOIN vt_nombramiento b ON a.vt_nombramiento=b.vt_nombramiento
             WHERE b.id_rrhh_direccion=? AND YEAR(b.fecha)=?
             GROUP BY b.id_rrhh_direccion, YEAR(b.fecha)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($direccion,date('Y')));
    $nombramiento_max = $q0->fetch();

    $nro_nombramiento;
    if(($nombramiento_max['id']!='')){
      $nro_nombramiento=$nombramiento_max['id']+1;
    }else{
      $nro_nombramiento=1;
    }


    $sql = "EXEC sp_ins_detalle_nombramiento @nombramiento=?, @empleado=?, @cheque=?,@reemplazo=?,@p=?, @num=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($vt_nombramiento,$id_empleado,$bln_cheque,0,0,$nro_nombramiento));// 65 es el id de aplicaciones
    //kardex = $p->fetchAll();


    Database::disconnect_sqlsrv();
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



}else{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
