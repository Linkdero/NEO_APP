<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $docto_id='';
  if(isset($_GET['docto_id'])){
    $docto_id=$_GET['docto_id'];
  }

  $dictamenes = documento::get_dictamenes_by_docto($docto_id);
  $data = array();

  foreach($dictamenes as $d){
    $sub_array = array(
      'docto_id'=>$d['docto_id'],
      'reng_num'=>$d['reng_num'],
      'docto_dictamen'=>$d['docto_dictamen'],
      'docto_fecha'=>fecha_dmy($d['docto_fecha']),
      'status'=>$d['status']
    );
    $data[] = $sub_array;
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
