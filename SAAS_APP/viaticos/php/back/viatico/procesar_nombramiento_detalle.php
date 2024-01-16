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

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "UPDATE vt_nombramiento_detalle
          SET
            id_estado_descuento=?,
            monto_descuento_anticipo=?,
            porcentaje_proyectado=?,
            monto_asignado=?,
            bln_anticipo=?
          WHERE vt_nombramiento=? AND reng_num=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(1186,0,$porcentaje,$monto,$anticipo,$vt_nombramiento,$reng_num));


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
