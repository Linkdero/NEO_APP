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
  $renglon=$_POST['id_renglon'];
  $parametros=str_replace("'","()",$renglon);
  //$id_empleado_sustituye=$_POST['empleado_sustituye'];


    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0="UPDATE vt_nombramiento_detalle SET bln_confirma=?
               WHERE reng_num IN ($parametros) and vt_nombramiento=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(0,$vt_nombramiento));

    $sql = "UPDATE vt_nombramiento_detalle SET nro_frm_vt_cons = nro_frm_vt_ant, nro_frm_vt_liq = nro_frm_vt_ant
    WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND ISNULL(nro_frm_vt_ant, 0) > 0 AND ISNULL(nro_frm_vt_liq,0) = 0";
    $q = $pdo->prepare($sql);
    $q->execute(array($vt_nombramiento));





else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
