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
  $id_empleado_actual=$_POST['empleado_actual'];
  $renglon_1=$_POST['id_renglon'];
  $id_empleado_sustituye=$_POST['empleado_sustituye'];

  if($id_empleado_actual==$id_empleado_sustituye){
    echo '1';
  }else{
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT COUNT(id_empleado) AS conteo FROM vt_nombramiento_detalle
            WHERE vt_nombramiento=? AND id_empleado=? AND bln_confirma=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($vt_nombramiento,$id_empleado_sustituye,1));
    $persona = $q->fetch();
    if($persona['conteo']>0){
      echo '2';
    }else{
      $sql0="UPDATE vt_nombramiento_detalle SET bln_confirma=?
                 WHERE reng_num=? and vt_nombramiento=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array(0,$renglon_1,$vt_nombramiento));

      $sql = "UPDATE vt_nombramiento_detalle SET nro_frm_vt_cons = nro_frm_vt_ant, nro_frm_vt_liq = nro_frm_vt_ant
      WHERE reng_num = ? AND vt_nombramiento=? AND ISNULL(nro_frm_vt_ant, 0) > 0 AND ISNULL(nro_frm_vt_liq,0) = 0";
      $q = $pdo->prepare($sql);
      $q->execute(array($renglon_1,$vt_nombramiento));

      $sql_="SELECT nro_frm_vt_ant,bln_anticipo,nro_nombramiento FROM vt_nombramiento_detalle
                 WHERE reng_num=? and vt_nombramiento=?";
      $q_ = $pdo->prepare($sql_);
      $q_->execute(array($renglon_1,$vt_nombramiento));
      $va=$q_->fetch();

      $p=420;
      $sql1 = "EXEC sp_ins_detalle_nombramiento ?, ?, ?, ?, ?,?";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array($vt_nombramiento,$id_empleado_sustituye,0,$renglon_1,$p,$va['nro_nombramiento']));

      $sql2="UPDATE vt_nombramiento_detalle SET monto_asignado=?*porcentaje_proyectado, nro_frm_vt_ant=?, bln_anticipo=?
                 WHERE id_empleado=? AND vt_nombramiento=? AND bln_confirma=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array(420,$va['nro_frm_vt_ant'],$va['bln_anticipo'],$id_empleado_sustituye,$vt_nombramiento,1));
      echo 'ok';

    }
  }



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
