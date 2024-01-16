<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $correla = array();
    $correla = $clase->get_correla();

     $data = array();
     $sub_array = array(
        'correl' => $correla['correl']
      );
     

  echo json_encode($sub_array);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
