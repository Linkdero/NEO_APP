<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    $data = array();
    for ($x=1; $x<=12; $x++) {
      $sub_array = array(
        'id_item'=>$x,
        'id_mes'=>$x,
        'item_string'=>User::get_nombre_mes($x),
        'cantidad'=>'',
        'monto'=>'',
        'checked'=>false
      );
      $data[] = $sub_array;
    }
  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
?>
