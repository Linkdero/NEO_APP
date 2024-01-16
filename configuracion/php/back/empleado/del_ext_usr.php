<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');

    $id_persona=$_POST['persona'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sqlGafete = "UPDATE tbl_tel_empleado SET empleado_ext=0
                WHERE id_persona=?";
  $p0 = $pdo->prepare($sqlGafete);
  $p0->execute(array($id_persona));


  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
