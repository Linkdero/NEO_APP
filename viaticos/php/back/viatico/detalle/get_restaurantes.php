<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  //include_once '../functions.php';


  $response = array();
  $filtro = $_GET['q'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT lugarId, lugarNit, lugarNombre
           FROM vt_nombramiento_lugar
           WHERE lugarStatus=? AND lugarNit LIKE '%".$_GET['q']."%' OR lugarStatus=? AND lugarNombre LIKE '%".$_GET['q']."%'
           ORDER BY lugarId ASC
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(1,1));
  $insumos = $q0->fetchAll();

  $data = array();

  foreach($insumos as $i){
    $sub_array = array(
      'lugarId'=>$i['lugarId'],
      //'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'lugarNit'=>$i['lugarNit'],
      'lugarNombre'=>$i['lugarNombre'],

    );
    //$data[] = $sub_array;

     $data[] = ['id'=>$i['lugarId'], 'text'=>$i['lugarNit'].' - '.$i['lugarNombre']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
