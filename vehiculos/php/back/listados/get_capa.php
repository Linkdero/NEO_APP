
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    

    $id_vehiculo = $_GET['id_vehiculo'];
    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $capaTanque = array();
    $capaTanque = $clase->get_capa_tanque($id_vehiculo);

    $data = array();
    $data = array(
        'id_vehiculo' => $capaTanque['id_vehiculo'],
        'capaT' => (!empty($capaTanque['capaT']))?number_format($capaTanque['capaT'],2,'.',','):'0.00'
    );

//     $tipos = $clase->get_tipo($vehiculo['id_tipo_combustible']);
    

//        $data[] = array();
//      $sub_array = array(
//         'combust_str' => '- Seleccionar -',
//         'id_tipo' => ''
//       );
//       $data[] = $sub_array;
//     foreach ($tipos as $t){ 

//         $sub_array = array(
//           'combust_str' => $t['descripcion'],
//           'id_tipo' => $t['id_producto_insumo']
//         );
//         $data[] = $sub_array;
      
//     }

   echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

?>