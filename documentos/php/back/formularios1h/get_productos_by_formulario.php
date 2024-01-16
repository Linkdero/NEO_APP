<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $env_tra = $_GET['env_tra'];

  $productos = documento::get_productos_by_formulario($env_tra);
  $data = array();

  foreach($productos as $p){
    $sub_array = array(
      'Envd_can'=>number_format($p['Envd_can'],2,'.',','),
      'Pro_des'=>$p['Pro_des'],
      'Renglon_PPR'=>$p['Renglon_PPR'],
      'Med_nom'=>$p['Med_nom'],
    );
    $data[] = $sub_array;
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
