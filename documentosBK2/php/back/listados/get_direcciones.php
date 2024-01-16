<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../../../../empleados/php/back/functions.php';

  $response = array();

  $clase = new empleado;

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT c.id_direccion, c.descripcion AS nombre
           FROM  rrhh_direcciones c
           WHERE c.id_tipo =? AND c.id_nivel IN (4,3)
           ";

  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(887));
  $unidades = $q0->fetchAll();
  Database::disconnect_sqlsrv();

  $data = array();
  $sub_array = array(
    'id_item'=>'',
    'item_string'=>'-- Seleccionar -- '
  );
  $data[] = $sub_array;
  foreach ($unidades as $u){
    $respuesta='';
    $sub_array = array(
      'id_item'=>$u['id_direccion'],
      'item_string'=>$u['nombre']
    );
    $data[] = $sub_array;

  }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
