
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $id_tipo = $_GET['id_tipo'];
    $clase = new vehiculos;
    $destino = array();
    $destino = $clase->get_destino($id_tipo);
     $data = array();
     $sub_array = array(
        'item_string' => '- Seleccionar -',
        'id_item' => ''
      );
      $data[] = $sub_array;
    foreach ($destino as $des){ 

        $sub_array = array(
          'item_string' => $des['descripcion'],
          'id_item' => $des['id_item']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>


