<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../../../../../empleados/php/back/functions.php';
  include_once '../../functions.php';

  $clase = new empleado;
  $clased = new documento;
  $depto = '';
  $dir = '';

  $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
  if (!empty($e['id_dirf'])) {
    if ($e['id_subdireccion_funcional'] == 37) {
      $depto = 207; //subdireccion de mantenimiento y servicios generales
    } else {
      $depto = $e['id_dirf'];
    }
  } else {
    if (!empty($e['id_subsecre'])) {
      $depto = $e['id_subsecre'];
    } else {
      $depto = $e['id_secre'];
    }
  }

  $dir = $clased->devuelve_direccion_app_pos($depto);
  if ($dir == 6) {
    $dir = 207;
  }

  $response = array();
  $filtro = $_GET['q'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT a.pac_id, a.pac_nombre, a.pac_detalle, a.id_status, a.id_direccion, a.uni_cor
           FROM APP_POS.dbo.PAC_E a
           WHERE a.id_status IN (?,?) AND a.id_direccion = ? AND a.pac_nombre LIKE '%".$_GET['q']."%'
           AND pac_ejercicio_fiscal = ?

           ORDER BY a.pac_id ASC
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(1,3,$depto, date('Y')));
  $planes = $q0->fetchAll();

  $data = array();

  foreach($planes as $p){
    $sub_array = array(
      'pac_id'=>$p['pac_id'],
      //'Pedd_can'=>number_format($p['Pedd_can'],0,'',''),
      'pac_nombre'=>$p['pac_nombre'],
      'pac_detalle'=>$p['pac_detalle'],
      'id_status'=>$p['id_status'],
      'id_direccion'=>$p['id_direccion'],
      'uni_cor'=>$p['uni_cor']
    );
    //$data[] = $sub_array;

     $data[] = ['id'=>$p['pac_id'], 'text'=>$p['pac_nombre'].' - '.$p['pac_detalle']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
