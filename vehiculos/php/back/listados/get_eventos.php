
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $eventos = array();
    $eventos = $clase->get_eventos();

     $data = array();
     $sub_array = array(
        'evento_str' => '- Seleccionar -',
        'id_evento' => ''
      );
      $data[] = $sub_array;
    foreach ($eventos as $e){ 

        $sub_array = array(
          'evento_str' => $e['descripcion'],
          'id_evento' => $e['id_evento']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>