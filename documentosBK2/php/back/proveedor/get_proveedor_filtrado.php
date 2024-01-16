<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $filtro = $_GET['q'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT a.Prov_id, a.Prov_nom
           FROM APP_POS.dbo.PROVEEDOR a
           WHERE a.Prov_est=? AND a.Prov_id LIKE '%".$_GET['q']."%' OR a.Prov_est=? AND a.Prov_nom LIKE '%".$_GET['q']."%'
           ORDER BY a.Prov_id ASC
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(1,1));
  $proveedores = $q0->fetchAll();

  $data = array();

  foreach($proveedores as $p){

     $data[] = ['id'=>$p['Prov_id'], 'text'=>$p['Prov_id'].' * '.$p['Prov_nom']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
