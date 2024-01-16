<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $ped_tra='';
  if(isset($_GET['ped_tra'])){
    $ped_tra=$_GET['ped_tra'];
  }
  $clased = new documento;

  $p = $clased->get_persona_asignada($ped_tra);
  $data = array();

  $data = array(
    'id_persona_asignada'=>$p['id_persona_asignada'],
    'persona_asignada'=>$p['persona'],
    'validacion'=>($p['id_persona_asignada'] == $_SESSION['id_persona']) ? true : false
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
