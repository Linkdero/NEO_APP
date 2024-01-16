<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona = $_POST['id_persona'];
  $HORARIO = new Horario();
  $data = $HORARIO->get_name($id_persona);
  
  echo $data['nombre'];

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
