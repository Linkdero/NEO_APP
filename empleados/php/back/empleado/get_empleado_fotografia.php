<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona='';
	if(isset($_GET['id_persona'])){
		$id_persona=$_GET['id_persona'];
	}
  $e = array();
  $e = empleado::get_empleado_fotografia($id_persona);
  $data = array();

  $encoded_image = base64_encode($e['fotografia']);
  $cambio = false;
  if(evaluar_flag($_SESSION['id_persona'],1163,38,'flag_actualizar')==1){
    $cambio = true;
  }


  $data = array(
    'fotografia'=>$encoded_image,
    'foto'=>'data:image/jpeg;base64,'.$encoded_image,
    'cambio'=>$cambio
  );




    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
