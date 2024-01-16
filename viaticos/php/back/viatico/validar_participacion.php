<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona=$_POST['id_persona'];
  $fecha=date('Y-m-d', strtotime($_POST['fecha']));
  $hora=$_POST['hora'];
  //echo $fecha;
  $f="'".$fecha."'";

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0="Exec valida_participacion $id_persona, $f, $hora";
  echo $sql0;
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array());
  $validacion= $q0->fetch();

  if($validacion['resultado']==1){
    echo 'error';
  }else{
    echo 'ok';
  }




else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
