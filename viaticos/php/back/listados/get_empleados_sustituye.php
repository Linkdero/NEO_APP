<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $nombramiento=$_GET['vt_nombramiento'];
    $empleados = array();

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_rrhh_direccion FROM vt_nombramiento WHERE vt_nombramiento=?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($nombramiento));
        $direccion = $stmt->fetch();
        $empleados = viaticos::get_empleados_por_direccion($direccion['id_rrhh_direccion']);

    $data = array();
    foreach($empleados as $e){
        //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
        $sub_array = array(
          'id_persona'=>$e['id_persona'],
          //'foto'=>$e['id_persona'],
          'empleado'=>$e['nombre_completo'],
          'status' =>'1'
        );
        $data[] = $sub_array;
    }

    echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
