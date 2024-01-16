<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../../empleados/php/back/functions.php';
  //include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  //$solicitante = $_SESSION['id_persona'];

  /*$clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($id_persona);*/

  $vt_nombramiento=$_GET['id_viatico'];
  $id_persona=$_GET['id_persona'];
  $parametros = substr($id_persona, 1);
  //$id_empleado_sustituye=$_POST['empleado_sustituye'];


    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0="SELECT * FROM vt_nombramiento_detalle
               WHERE vt_nombramiento = ? AND id_empleado = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($vt_nombramiento,$parametros));
    $detalle = $q0->fetch();


    $data = array(
      'vt_nombramiento'=>$detalle['vt_nombramiento'],
      'id_persona'=>$detalle['id_empleado'],
      'reintegro_alimentacion'=>number_format($detalle['reintegro_alimentacion'],2,'.',','),
    );

    echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
