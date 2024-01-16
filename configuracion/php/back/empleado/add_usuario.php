<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $correo = $_POST['correo'];
  $id_persona = $_POST['persona'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sqlGafete = "INSERT INTO rrhh_persona_usuario VALUES (?,?,?,?,?,?,?,?,?,?)";
  $p0 = $pdo->prepare($sqlGafete);
  $p0->execute(array(
    $id_persona,
    $correo,
    'a2334fac7c7a32b656a62372af89753d',
    'd',
    1,
    $_SESSION['id_persona'],
    date('Y-m-d H:i:s'),
    1,
    1,
    0
  ));

  Database::disconnect_sqlsrv();

else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
