<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../../../../empleados/php/back/functions.php';

  $response = array();

  $clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
  //$depto=(!empty($e['id_depto_funcional']))?$e['id_depto_funcional']:0;
  $dir = '';
  if(!empty($e['id_dirf'])){
    $dir = $e['id_dirf'];
  }else{
    if(!empty($e['id_subsecre'])){
      $e['id_subsecre'];
    }else{
      $dir = $e['id_secre'];
    }

  }
  //$dir=(!empty($e['id_dirf']))?$e['id_dirf']:0;
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
  if($_SESSION['id_persona']== 5449){
    $sub_array = array(
      'id_departamento'=>'7898',
      'nombre'=>'HANGAR PRESIDENCIAL'
    );
    $data[] = $sub_array;
  }
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
