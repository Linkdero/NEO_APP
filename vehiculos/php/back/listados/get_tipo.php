
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $id_vehiculo='';
  if(isset($_GET['id_vehiculo'])){
    $id_vehiculo=$_GET['id_vehiculo'];
  }

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $tipos = array();
    $vehiculo = $clase->get_placa_by_placa($id_vehiculo);
    $id_tipo = (!empty($vehiculo['id_tipo_combustible'])) ? $vehiculo['id_tipo_combustible'] : 0;
    $tipos = $clase->get_tipo($id_tipo);
    

     $data = array();
     $sub_array = array(
        'item_string' => '- Seleccionar -',
        'id_item' => ''
      );
      $data[] = $sub_array;
    foreach ($tipos as $t){ 

        $sub_array = array(
          'item_string' => $t['descripcion'],
          'id_item' => $t['id_producto_insumo']
        );
        $data[] = $sub_array;
      
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

?>