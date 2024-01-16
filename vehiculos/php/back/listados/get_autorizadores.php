<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../../../empleados/php/back/functions.php';
    date_default_timezone_set('America/Guatemala');

      $autorizadores = array();

      $empleados = array();
      $empleados = empleado::get_empleados_autoriza(7);
      //$cupones = $clase->get_devolCuponesDet($id_documento);
      $data = array();

      foreach ($empleados as $e){

        $sub_array = array(
          'id_item'=>$e['id_persona'],
          'item_string'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        );

        $data[]=$sub_array;
      }



    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
