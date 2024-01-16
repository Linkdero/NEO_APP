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
        'refer_str' => '- Seleccionar -',
        'id_refer' => ''
      );
      $data[] = $sub_array;
    foreach ($empleados as $empleado){ 

        $sub_array = array(
          'refer_str' => $empleado['nombre'],
          'id_refer' => $empleado['id_persona']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>