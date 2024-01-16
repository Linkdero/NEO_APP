<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../../../../empleados/php/back/functions.php';

  $response = array();
  $id_direccion = $_GET['id_direccion'];

  $clase = new empleado;

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT c.id_direccion, c.descripcion AS nombre
           FROM  rrhh_direcciones c
           WHERE c.id_direccion = ?
           ";

  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($id_direccion));
  $direccion = $q0->fetch();
  Database::disconnect_sqlsrv();

  $data = array(
    'id_direccion'=>$direccion['id_direccion'],
    'nombre'=>$direccion['nombre']
  );

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
