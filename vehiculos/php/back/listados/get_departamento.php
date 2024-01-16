<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $deptos = array();
    $deptos = $clase->get_departamentos('GT');

     $data = array();
     $sub_array = array(
        'depto_str' => '- Seleccionar -',
        'id_depto' => ''
      );
      $data[] = $sub_array;
    foreach ($deptos as $depto){ 

        $sub_array = array(
          'depto_str' => $depto['nombre'],
          'id_depto' => $depto['id_departamento']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
