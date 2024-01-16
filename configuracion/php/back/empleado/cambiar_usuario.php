<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');

    $password=$_POST['password'];
    $id_persona=$_POST['persona'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sqlGafete = "UPDATE rrhh_persona_usuario SET persona_user=?
                WHERE id_persona=?";
  $p0 = $pdo->prepare($sqlGafete);
  $p0->execute(array($password,
    $id_persona
  ));


  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
