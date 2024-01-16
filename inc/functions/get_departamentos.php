<?php
include_once '../functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $dir ='';
  if(isset($_GET['id_direccion'])){
    $dir=$_GET['id_direccion'];
  }

  $response = array();
  $dir=($_SESSION['id_persona'] == 8678) ? 207 : $dir;
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT a.id_departamento, a.descripcion AS nombre
           FROM rrhh_departamentos a
           INNER JOIN rrhh_subdirecciones b ON a.id_subdireccion=b.id_subdireccion
           INNER JOIN rrhh_direcciones c ON b.id_direccion =c.id_direccion
		       INNER JOIN APP_POS.dbo.UNIDAD d ON a.id_departamento=d.id_departamento
           WHERE c.id_direccion=?
           UNION
           SELECT a.id_subdireccion AS id_departamento, a.descripcion AS nombre
           FROM rrhh_subdirecciones a
           INNER JOIN rrhh_direcciones b ON a.id_direccion= b.id_direccion
           WHERE a.id_direccion=?
           UNION
           SELECT a.id_direccion AS id_departamento, a.descripcion AS nombre
           FROM rrhh_direcciones a
           WHERE a.id_direccion=?";

  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($dir,$dir,$dir));
  $unidades = $q0->fetchAll();
  Database::disconnect_sqlsrv();

  $data = array();
  $sub_array = array(
    'id_departamento'=>'',
    'nombre'=>'-- Seleccionar -- '
  );
  $data[] = $sub_array;
  foreach ($unidades as $u){
    $respuesta='';
    $sub_array = array(
      'id_departamento'=>$u['id_departamento'],
      'nombre'=>$u['nombre']
    );
    $data[] = $sub_array;

  }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
