<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona='';
	if(isset($_GET['id_persona'])){
		$id_persona=$_GET['id_persona'];
	}

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT id_empleado FROM rrhh_empleado WHERE id_persona=?";
  $p = $pdo->prepare($sql);
  $p->execute(array($id_persona));
  $empleado = $p->fetch();
  Database::disconnect_sqlsrv();

  $data = array(
    'id_empleado'=>$empleado['id_empleado']
  );

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
