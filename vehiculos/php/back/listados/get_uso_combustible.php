
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $combus = array();
    $combus = $clase->get_uso_combustible();

     $data = array();
     $sub_array = array(
        'combus_str' => '- Seleccionar -',
        'id_combus' => ''
      );
      $data[] = $sub_array;
    foreach ($combust as $combus){ 

        $sub_array = array(
          'combus_str' => $combus['descripcion'],
          'id_combus' => $combus['id_item']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>


