<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $id_departamento = $_GET["id_departamento"];


    $clase = new vehiculos;
    $munis = array();
    $munis = $clase->get_municipios($id_departamento);

     $data = array();
     $sub_array = array(
        'muni_str' => '- Seleccionar -',
        'id_muni' => ''
      );
      $data[] = $sub_array;
    foreach ($munis as $muni){ 

        $sub_array = array(
          'muni_str' => $muni['nombre'],
          'id_muni' => $muni['id_municipio']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>