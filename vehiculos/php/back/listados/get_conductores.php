<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $empleados = array();
    $empleados = $clase->get_empleados_activos();

     $data = array();
     $sub_array = array(
        'item_string' => '- Seleccionar -',
        'id_item' => '',
        'id_secre' => '',
        'id_subsecre' => '',
        'id_direc' => ''
      );
      $data[] = $sub_array;
    foreach ($empleados as $empleado){ 

        $sub_array = array(
          'item_string' => $empleado['nombre'],
          'id_item' => $empleado['id_persona'],
          'id_secre' => $empleado['id_secre'],
          'id_subsecre' => $empleado['id_subsecre'],
          'id_direc' => $empleado['id_direc']
          );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>