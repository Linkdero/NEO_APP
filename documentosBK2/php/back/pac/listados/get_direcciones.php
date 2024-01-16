<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../functions.php';

    $data = array();
    $u = usuarioPrivilegiado();
    if ($u->hasPrivilege(302) && $u->hasPrivilege(325) || $u->hasPrivilege(301) && $u->hasPrivilege(325)) {
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql0 = "SELECT id_direccion, descripcion FROM rrhh_direcciones WHERE id_direccion IN (9,12,6,207,1,10,5,669,11,8)";
      //if($e['id_de'])
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array());
      $direcciones = $q0->fetchAll();
      Database::disconnect_sqlsrv();

      $sub_array = array(
        'id_item'=>0,
        'item_string'=>'-- TODOS --',
      );
      $data[] = $sub_array;

    }

    foreach ($direcciones AS $d) {
      $sub_array = array(
        'id_item'=>$d['id_direccion'],
        'item_string'=>$d['descripcion'],
      );
      $data[] = $sub_array;
    }
  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
?>
