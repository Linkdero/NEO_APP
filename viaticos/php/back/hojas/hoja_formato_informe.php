
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();

  include_once '../../../../empleados/php/back/functions.php';
  $empleado = empleado::get_empleado_by_id_ficha($_SESSION['id_persona']);

  $direccion = $empleado['id_dirf'];
  if ($empleado['id_subdireccion_funcional'] == 34) {
    $direccion = 207;
  }


  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //$sql = "EXEC sp_sel_imprimible_viatico_anticipo @correlativo=?";
  $sql = "SELECT TOP 1 id_rrhh_direccion, usr_autoriza
          FROM vt_nombramiento WHERE ISNULL(usr_autoriza,0) <> ? AND id_rrhh_direccion = ?
          ORDER BY vt_nombramiento DESC";

  $p = $pdo->prepare($sql);
  $p->execute(array(0, $direccion));
  $datos = $p->fetch();

  $sql1 = "SELECT id_persona, isnull(primer_nombre,'')+' '+isnull(segundo_nombre,'')+' '+isnull(tercer_nombre,'')+ ' '+isnull(primer_apellido,'')+' '+isnull(segundo_apellido,'')+' '+isnull(tercer_apellido,'') AS director
          FROM rrhh_persona WHERE id_persona = ?
  ";

  $p1 = $pdo->prepare($sql1);
  $p1->execute(array($datos['usr_autoriza']));
  $datos1 = $p1->fetch();

  $data = array();

  $sss = '';
  if ($datos['usr_autoriza'] == 8362) {
    $sss = 'SSS';
  } else if ($datos['usr_autoriza'] == 8449) {
    $sss = 'SSA';
  }
  $data = array(
    'director' => $datos1['director'],
    'direccion' => $empleado['dir_funcional'],
    'sss' => $sss
  );

  echo json_encode($data);

else :
  header("Location: ../index.php");
endif;

?>
